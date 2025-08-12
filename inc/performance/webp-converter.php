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

            // Always create picture element for better fallback handling
            if ($webp_url && function_exists('pe_mp_browser_supports_webp') && pe_mp_browser_supports_webp()) {
                // Replace with picture element
                $picture_element = $this->create_picture_element($img_tag, $webp_url, $image_url);
                $content = str_replace($img_tag, $picture_element, $content);
            } else {
                // If no WebP version exists, try to create one
                $attachment_id = attachment_url_to_postid($image_url);
                if ($attachment_id && function_exists('pe_mp_force_webp_conversion')) {
                    $converted = pe_mp_force_webp_conversion($attachment_id);
                    if ($converted) {
                        $webp_url = pe_mp_get_webp_url($attachment_id);
                        if ($webp_url) {
                            $picture_element = $this->create_picture_element($img_tag, $webp_url, $image_url);
                            $content = str_replace($img_tag, $picture_element, $content);
                        }
                    }
                }
            }
        }

        return $content;
    }

    /**
     * Convert existing images to WebP format
     * This method can be called manually or via AJAX
     */
    public function convert_existing_images_to_webp($limit = 50)
    {
        // Get all image attachments that don't have WebP versions
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => array('image/jpeg', 'image/jpg', 'image/png'),
            'post_status' => 'inherit',
            'posts_per_page' => $limit,
            'meta_query' => array(
                array(
                    'key' => '_wp_attachment_metadata',
                    'value' => '"webp_url"',
                    'compare' => 'NOT LIKE'
                )
            )
        );

        $attachments = get_posts($args);
        $converted = 0;
        $errors = array();

        foreach ($attachments as $attachment) {
            $file_path = get_attached_file($attachment->ID);
            
            if (!$file_path || !file_exists($file_path)) {
                continue;
            }

            // Check if WebP already exists
            $path_info = pathinfo($file_path);
            $webp_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.webp';
            
            if (file_exists($webp_path)) {
                // Update metadata if WebP exists but not in metadata
                $this->update_attachment_metadata($attachment->ID, $webp_path);
                $converted++;
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

        return array(
            'converted' => $converted,
            'errors' => $errors,
            'total_processed' => count($attachments)
        );
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
        // Handle force convert for specific image
        if (isset($_GET['force_convert']) && isset($_GET['_wpnonce'])) {
            $attachment_id = intval($_GET['force_convert']);
            if (wp_verify_nonce($_GET['_wpnonce'], 'force_convert_' . $attachment_id)) {
                if (function_exists('pe_mp_force_webp_conversion')) {
                    $result = pe_mp_force_webp_conversion($attachment_id);
                    $message = $result ? 'Image successfully converted to WebP!' : 'Failed to convert image to WebP.';
                }
            }
        }

        // Handle bulk conversion
        if (isset($_POST['action']) && $_POST['action'] === 'convert_existing_webp') {
            if (wp_verify_nonce($_POST['nonce'], 'convert_existing_webp')) {
                $result = $this->convert_existing_images_to_webp(100);
                $message = sprintf(
                    'Successfully converted %d images to WebP format. %d errors occurred.',
                    $result['converted'],
                    count($result['errors'])
                );
                if (!empty($result['errors'])) {
                    $message .= '<br>Errors: ' . implode(', ', array_slice($result['errors'], 0, 10));
                }
            }
        }

        // Get statistics
        $total_images = $this->get_image_statistics();
        ?>
        <div class="wrap">
            <h1>WebP Image Management</h1>
            
            <?php if (isset($message)): ?>
                <div class="notice notice-success">
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>

            <div class="card">
                <h2>Image Statistics</h2>
                <p><strong>Total Images:</strong> <?php echo $total_images['total']; ?></p>
                <p><strong>WebP Converted:</strong> <?php echo $total_images['webp_converted']; ?></p>
                <p><strong>Pending Conversion:</strong> <?php echo $total_images['pending']; ?></p>
                <p><strong>Conversion Rate:</strong> <?php echo round(($total_images['webp_converted'] / max(1, $total_images['total'])) * 100, 1); ?>%</p>
            </div>

            <div class="card">
                <h2>Convert Existing Images</h2>
                <p>Convert existing JPEG and PNG images to WebP format for better performance.</p>
                
                <form method="post">
                    <?php wp_nonce_field('convert_existing_webp', 'nonce'); ?>
                    <input type="hidden" name="action" value="convert_existing_webp">
                    <button type="submit" class="button button-primary">Convert Images to WebP</button>
                </form>
            </div>

            <div class="card">
                <h2>Manual Conversion</h2>
                <p>Convert images in batches of 100. This process may take some time for large image libraries.</p>
                
                <button id="batch-convert" class="button button-secondary">Start Batch Conversion</button>
                <div id="batch-status"></div>
            </div>

            <div class="card">
                <h2>WebP Support Check</h2>
                <p><strong>Server WebP Support:</strong> <?php echo $this->is_webp_supported() ? '✅ Supported' : '❌ Not Supported'; ?></p>
                <p><strong>GD Extension:</strong> <?php echo extension_loaded('gd') ? '✅ Available' : '❌ Not Available'; ?></p>
                <p><strong>imagewebp Function:</strong> <?php echo function_exists('imagewebp') ? '✅ Available' : '❌ Not Available'; ?></p>
            </div>

            <div class="card">
                <h2>Debug Information</h2>
                <p>Check specific images that might not be converting properly.</p>
                
                <form method="post">
                    <?php wp_nonce_field('debug_webp', 'debug_nonce'); ?>
                    <input type="hidden" name="action" value="debug_webp">
                    <label for="debug_attachment_id">Attachment ID:</label>
                    <input type="number" name="debug_attachment_id" id="debug_attachment_id" value="<?php echo isset($_POST['debug_attachment_id']) ? esc_attr($_POST['debug_attachment_id']) : ''; ?>">
                    <button type="submit" class="button">Debug Image</button>
                </form>

                <?php if (isset($_POST['action']) && $_POST['action'] === 'debug_webp' && wp_verify_nonce($_POST['debug_nonce'], 'debug_webp')): ?>
                    <?php 
                    $attachment_id = intval($_POST['debug_attachment_id']);
                    if ($attachment_id && function_exists('pe_mp_debug_webp_status')) {
                        $debug = pe_mp_debug_webp_status($attachment_id);
                    ?>
                        <div style="background: #f9f9f9; padding: 15px; margin-top: 15px; border-left: 4px solid #0073aa;">
                            <h3>Debug Results for Attachment ID: <?php echo $attachment_id; ?></h3>
                            <ul>
                                <li><strong>File Path:</strong> <?php echo esc_html($debug['file_path']); ?></li>
                                <li><strong>File Exists:</strong> <?php echo $debug['file_exists'] ? '✅ Yes' : '❌ No'; ?></li>
                                <li><strong>MIME Type:</strong> <?php echo esc_html($debug['mime_type']); ?></li>
                                <li><strong>WebP Path:</strong> <?php echo esc_html($debug['webp_path']); ?></li>
                                <li><strong>WebP File Exists:</strong> <?php echo $debug['webp_exists'] ? '✅ Yes' : '❌ No'; ?></li>
                                <li><strong>Metadata Has WebP:</strong> <?php echo $debug['metadata_has_webp'] ? '✅ Yes' : '❌ No'; ?></li>
                                <li><strong>WebP URL:</strong> <?php echo esc_html($debug['webp_url']); ?></li>
                                <li><strong>Browser Supports WebP:</strong> <?php echo $debug['browser_supports_webp'] ? '✅ Yes' : '❌ No'; ?></li>
                                <li><strong>Server Supports WebP:</strong> <?php echo $debug['server_supports_webp'] ? '✅ Yes' : '❌ No'; ?></li>
                            </ul>
                            <?php if (!empty($debug['errors'])): ?>
                                <h4>Errors:</h4>
                                <ul>
                                    <?php foreach ($debug['errors'] as $error): ?>
                                        <li style="color: red;"><?php echo esc_html($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            
                            <?php if (!$debug['webp_exists'] && $debug['file_exists'] && $debug['server_supports_webp']): ?>
                                <p><a href="<?php echo admin_url('admin.php?page=generate-webp&force_convert=' . $attachment_id . '&_wpnonce=' . wp_create_nonce('force_convert_' . $attachment_id)); ?>" class="button button-secondary">Force Convert This Image</a></p>
                            <?php endif; ?>
                        </div>
                    <?php } ?>
                <?php endif; ?>
            </div>

            <script>
            jQuery(document).ready(function($) {
                $('#batch-convert').click(function() {
                    var button = $(this);
                    var status = $('#batch-status');
                    var totalConverted = 0;
                    var totalErrors = 0;
                    
                    button.prop('disabled', true).text('Converting...');
                    status.html('<p>Starting batch conversion...</p>');
                    
                    function convertBatch() {
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'generate_webp_versions',
                                nonce: '<?php echo wp_create_nonce('generate_webp_versions'); ?>'
                            },
                            success: function(response) {
                                if (response.success) {
                                    totalConverted += response.data.converted;
                                    totalErrors += response.data.errors.length;
                                    
                                    status.html('<p>Converted: ' + totalConverted + ' images. Errors: ' + totalErrors + '</p>');
                                    
                                    if (response.data.converted > 0) {
                                        // Continue with next batch
                                        setTimeout(convertBatch, 1000);
                                    } else {
                                        status.append('<p><strong>Batch conversion completed!</strong></p>');
                                        button.prop('disabled', false).text('Start Batch Conversion');
                                        // Reload page to update statistics
                                        setTimeout(function() { location.reload(); }, 2000);
                                    }
                                } else {
                                    status.html('<p>Error: ' + response.data + '</p>');
                                    button.prop('disabled', false).text('Start Batch Conversion');
                                }
                            },
                            error: function() {
                                status.html('<p>Error occurred during conversion.</p>');
                                button.prop('disabled', false).text('Start Batch Conversion');
                            }
                        });
                    }
                    
                    convertBatch();
                });
            });
            </script>
        </div>
        <?php
    }

    /**
     * Get image statistics
     */
    private function get_image_statistics()
    {
        // Total images
        $total_args = array(
            'post_type' => 'attachment',
            'post_mime_type' => array('image/jpeg', 'image/jpg', 'image/png'),
            'post_status' => 'inherit',
            'posts_per_page' => -1,
            'fields' => 'ids'
        );
        $total_images = get_posts($total_args);
        $total = count($total_images);

        // WebP converted images
        $webp_args = array(
            'post_type' => 'attachment',
            'post_mime_type' => array('image/jpeg', 'image/jpg', 'image/png'),
            'post_status' => 'inherit',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_wp_attachment_metadata',
                    'value' => '"webp_url"',
                    'compare' => 'LIKE'
                )
            ),
            'fields' => 'ids'
        );
        $webp_converted = get_posts($webp_args);
        $webp_count = count($webp_converted);

        return array(
            'total' => $total,
            'webp_converted' => $webp_count,
            'pending' => $total - $webp_count
        );
    }
}

// Initialize WebP Converter
new PE_MP_WebP_Converter(); 