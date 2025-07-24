<?php

/**
 * WebP Image Converter for PE Media Portal Theme
 * Automatically converts uploaded images to WebP format
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PE_MP_WebP_Converter
{
    private $supported_types = array('image/jpeg', 'image/jpg', 'image/png');
    private $quality = 85; // WebP quality (0-100)

    public function __construct()
    {
        // Check if WebP is supported
        if (!$this->is_webp_supported()) {
            return;
        }

        // Hook into WordPress upload process
        add_filter('wp_handle_upload', array($this, 'convert_to_webp'), 10, 2);
        
        // Add WebP support to WordPress
        add_filter('upload_mimes', array($this, 'add_webp_support'));
        
        // Generate WebP versions of existing images
        add_action('wp_ajax_generate_webp_versions', array($this, 'generate_webp_versions'));
        
        // Add admin menu for WebP conversion
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Add WebP to image sizes
        add_filter('wp_get_attachment_image_src', array($this, 'replace_with_webp'), 10, 4);
        
        // Add WebP to content
        add_filter('the_content', array($this, 'replace_content_images'));
        add_filter('widget_text', array($this, 'replace_content_images'));
        
        // Add picture element support
        add_filter('wp_get_attachment_image', array($this, 'add_picture_element'), 10, 5);
    }

    /**
     * Check if WebP is supported on the server
     */
    private function is_webp_supported()
    {
        return function_exists('imagewebp') && function_exists('imagecreatefromjpeg') && function_exists('imagecreatefrompng');
    }

    /**
     * Add WebP mime type support
     */
    public function add_webp_support($mimes)
    {
        $mimes['webp'] = 'image/webp';
        return $mimes;
    }

    /**
     * Convert uploaded image to WebP
     */
    public function convert_to_webp($upload, $context)
    {
        // Only process images
        if (!in_array($upload['type'], $this->supported_types)) {
            return $upload;
        }

        $file_path = $upload['file'];
        $webp_path = $this->get_webp_path($file_path);

        // Convert to WebP
        if ($this->create_webp_version($file_path, $webp_path)) {
            // Store WebP path in attachment metadata
            $attachment_id = $this->get_attachment_id_from_path($file_path);
            if ($attachment_id) {
                $this->update_attachment_metadata($attachment_id, $webp_path);
            }
        }

        return $upload;
    }

    /**
     * Create WebP version of an image
     */
    private function create_webp_version($source_path, $webp_path)
    {
        // Get image info
        $image_info = getimagesize($source_path);
        if (!$image_info) {
            return false;
        }

        $width = $image_info[0];
        $height = $image_info[1];
        $mime_type = $image_info['mime'];

        // Create image resource based on type
        switch ($mime_type) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($source_path);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source_path);
                // Preserve transparency for PNG
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        // Create WebP image
        $result = imagewebp($image, $webp_path, $this->quality);

        // Clean up
        imagedestroy($image);

        return $result;
    }

    /**
     * Get WebP file path from original path
     */
    private function get_webp_path($original_path)
    {
        $path_info = pathinfo($original_path);
        return $path_info['dirname'] . '/' . $path_info['filename'] . '.webp';
    }

    /**
     * Get attachment ID from file path
     */
    private function get_attachment_id_from_path($file_path)
    {
        global $wpdb;
        
        $upload_dir = wp_upload_dir();
        $relative_path = str_replace($upload_dir['basedir'] . '/', '', $file_path);
        
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid LIKE %s;", '%' . $relative_path));
        
        return !empty($attachment[0]) ? $attachment[0] : false;
    }

    /**
     * Update attachment metadata with WebP information
     */
    private function update_attachment_metadata($attachment_id, $webp_path)
    {
        $metadata = wp_get_attachment_metadata($attachment_id);
        $upload_dir = wp_upload_dir();
        
        // Get relative path for WebP file
        $webp_relative_path = str_replace($upload_dir['basedir'] . '/', '', $webp_path);
        $webp_url = $upload_dir['baseurl'] . '/' . $webp_relative_path;
        
        // Add WebP information to metadata
        $metadata['webp_file'] = $webp_relative_path;
        $metadata['webp_url'] = $webp_url;
        
        wp_update_attachment_metadata($attachment_id, $metadata);
    }

    /**
     * Replace image with WebP version if available
     */
    public function replace_with_webp($image, $attachment_id, $size, $icon)
    {
        if (!$image || !$attachment_id) {
            return $image;
        }

        $webp_url = pe_mp_get_webp_url($attachment_id);
        
        if ($webp_url && function_exists('pe_mp_browser_supports_webp') && pe_mp_browser_supports_webp()) {
            $image[0] = $webp_url;
        }

        return $image;
    }

    /**
     * Replace images in content with WebP versions
     */
    public function replace_content_images($content)
    {
        if (empty($content)) {
            return $content;
        }

        // Find all img tags
        preg_match_all('/<img[^>]+>/i', $content, $matches);

        foreach ($matches[0] as $img_tag) {
            // Extract src attribute
            preg_match('/src=["\']([^"\']+)["\']/i', $img_tag, $src_match);
            
            if (empty($src_match[1])) {
                continue;
            }

            $image_url = $src_match[1];
            $webp_url = $this->get_webp_url_from_image_url($image_url);

            if ($webp_url && function_exists('pe_mp_browser_supports_webp') && pe_mp_browser_supports_webp()) {
                // Replace with picture element
                $picture_element = $this->create_picture_element($img_tag, $webp_url, $image_url);
                $content = str_replace($img_tag, $picture_element, $content);
            }
        }

        return $content;
    }

    /**
     * Get WebP URL from image URL
     */
    private function get_webp_url_from_image_url($image_url)
    {
        // Get attachment ID from URL
        $attachment_id = attachment_url_to_postid($image_url);
        
        if (!$attachment_id) {
            return false;
        }

        return pe_mp_get_webp_url($attachment_id);
    }

    /**
     * Create picture element with WebP support
     */
    private function create_picture_element($img_tag, $webp_src, $original_src)
    {
        // Extract attributes from img tag
        preg_match_all('/(\w+)=["\']([^"\']*)["\']/i', $img_tag, $attr_matches);
        
        $attributes = array();
        for ($i = 0; $i < count($attr_matches[1]); $i++) {
            $attributes[$attr_matches[1][$i]] = $attr_matches[2][$i];
        }

        // Build attributes string
        $attr_string = '';
        foreach ($attributes as $key => $value) {
            if ($key !== 'src') { // Skip src as it will be in the img tag
                $attr_string .= ' ' . $key . '="' . esc_attr($value) . '"';
            }
        }

        return sprintf(
            '<picture><source srcset="%s" type="image/webp"><img src="%s"%s></picture>',
            esc_url($webp_src),
            esc_url($original_src),
            $attr_string
        );
    }

    /**
     * Add picture element support to wp_get_attachment_image
     */
    public function add_picture_element($html, $attachment_id, $size, $icon, $attr)
    {
        $webp_url = pe_mp_get_webp_url($attachment_id);
        
        if (!$webp_url || !function_exists('pe_mp_browser_supports_webp') || !pe_mp_browser_supports_webp()) {
            return $html;
        }

        // Extract src from img tag
        preg_match('/src=["\']([^"\']+)["\']/i', $html, $src_match);
        
        if (empty($src_match[1])) {
            return $html;
        }

        $original_src = $src_match[1];
        
        // Create picture element
        $picture_html = sprintf(
            '<picture><source srcset="%s" type="image/webp">%s</picture>',
            esc_url($webp_url),
            $html
        );

        return $picture_html;
    }

    /**
     * Generate WebP versions for existing images
     */
    public function generate_webp_versions()
    {
        // Check nonce for security
        if (!wp_verify_nonce($_POST['nonce'], 'generate_webp_versions')) {
            wp_die('Security check failed');
        }

        // Get all image attachments
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => array('image/jpeg', 'image/jpg', 'image/png'),
            'post_status' => 'inherit',
            'posts_per_page' => -1,
        );

        $attachments = get_posts($args);
        $converted = 0;
        $errors = array();

        foreach ($attachments as $attachment) {
            $file_path = get_attached_file($attachment->ID);
            
            if (!$file_path || !file_exists($file_path)) {
                continue;
            }

            $webp_path = $this->get_webp_path($file_path);
            
            // Skip if WebP already exists
            if (file_exists($webp_path)) {
                continue;
            }

            // Convert to WebP
            if ($this->create_webp_version($file_path, $webp_path)) {
                $this->update_attachment_metadata($attachment->ID, $webp_path);
                $converted++;
            } else {
                $errors[] = $attachment->post_title;
            }
        }

        wp_send_json_success(array(
            'converted' => $converted,
            'errors' => $errors
        ));
    }

    /**
     * Add admin menu for WebP conversion
     */
    public function add_admin_menu()
    {
        add_management_page(
            'Generate WebP Versions',
            'Generate WebP',
            'manage_options',
            'generate-webp',
            array($this, 'admin_page')
        );
    }

    /**
     * Admin page for WebP conversion
     */
    public function admin_page()
    {
        ?>
        <div class="wrap">
            <h1>Generate WebP Versions</h1>
            <p>Convert existing images to WebP format for better performance.</p>
            
            <button id="generate-webp" class="button button-primary">Generate WebP Versions</button>
            <div id="webp-status"></div>

            <script>
            jQuery(document).ready(function($) {
                $('#generate-webp').click(function() {
                    var button = $(this);
                    var status = $('#webp-status');
                    
                    button.prop('disabled', true).text('Converting...');
                    status.html('<p>Converting images to WebP format...</p>');
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'generate_webp_versions',
                            nonce: '<?php echo wp_create_nonce('generate_webp_versions'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                status.html('<p>Successfully converted ' + response.data.converted + ' images to WebP format.</p>');
                                if (response.data.errors.length > 0) {
                                    status.append('<p>Errors: ' + response.data.errors.join(', ') + '</p>');
                                }
                            } else {
                                status.html('<p>Error: ' + response.data + '</p>');
                            }
                        },
                        error: function() {
                            status.html('<p>Error occurred during conversion.</p>');
                        },
                        complete: function() {
                            button.prop('disabled', false).text('Generate WebP Versions');
                        }
                    });
                });
            });
            </script>
        </div>
        <?php
    }
}

// Initialize WebP Converter
new PE_MP_WebP_Converter(); 