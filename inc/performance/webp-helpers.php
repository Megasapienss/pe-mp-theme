<?php

/**
 * WebP Helper Functions for PE Media Portal Theme
 * Provides easy-to-use functions for WebP image handling
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get WebP URL for an attachment
 * 
 * @param int $attachment_id The attachment ID
 * @return string|false WebP URL if available, false otherwise
 */
function pe_mp_get_webp_url($attachment_id)
{
    if (!$attachment_id) {
        return false;
    }

    $metadata = wp_get_attachment_metadata($attachment_id);
    if (!isset($metadata['webp_url'])) {
        return false;
    }

    // Check if WebP file exists
    $upload_dir = wp_upload_dir();
    $webp_file_path = $upload_dir['basedir'] . '/' . $metadata['webp_file'];
    
    return file_exists($webp_file_path) ? $metadata['webp_url'] : false;
}

/**
 * Get picture element HTML for an attachment
 * 
 * @param int $attachment_id The attachment ID
 * @param string $size Image size
 * @param array $attr Additional attributes
 * @return string Picture element HTML
 */
function pe_mp_get_picture_element($attachment_id, $size = 'full', $attr = array())
{
    $webp_url = pe_mp_get_webp_url($attachment_id);
    $original_image = wp_get_attachment_image_src($attachment_id, $size);
    
    if (!$original_image) {
        return '';
    }

    $original_url = $original_image[0];
    $width = $original_image[1];
    $height = $original_image[2];

    // Build attributes string
    $attr_string = '';
    foreach ($attr as $key => $value) {
        $attr_string .= ' ' . $key . '="' . esc_attr($value) . '"';
    }

    if ($webp_url && pe_mp_browser_supports_webp()) {
        return sprintf(
            '<picture><source srcset="%s" type="image/webp"><img src="%s" width="%d" height="%d"%s></picture>',
            esc_url($webp_url),
            esc_url($original_url),
            $width,
            $height,
            $attr_string
        );
    } else {
        return sprintf(
            '<img src="%s" width="%d" height="%d"%s>',
            esc_url($original_url),
            $width,
            $height,
            $attr_string
        );
    }
}

/**
 * Echo picture element for an attachment
 * 
 * @param int $attachment_id The attachment ID
 * @param string $size Image size
 * @param array $attr Additional attributes
 */
function pe_mp_picture_element($attachment_id, $size = 'full', $attr = array())
{
    echo pe_mp_get_picture_element($attachment_id, $size, $attr);
}

/**
 * Get responsive image HTML with WebP support
 * 
 * @param int $attachment_id The attachment ID
 * @param array $sizes Array of sizes to generate
 * @param array $attr Additional attributes
 * @return string Responsive image HTML
 */
function pe_mp_get_responsive_image($attachment_id, $sizes = array(), $attr = array())
{
    if (!$attachment_id) {
        return '';
    }

    $original_image = wp_get_attachment_image_src($attachment_id, 'full');
    if (!$original_image) {
        return '';
    }

    $original_url = $original_image[0];
    $width = $original_image[1];
    $height = $original_image[2];

    // Build srcset for original image
    $srcset_string = '';
    $webp_srcset_string = '';
    
    foreach ($sizes as $size_name => $size_width) {
        $size_image = wp_get_attachment_image_src($attachment_id, $size_name);
        if ($size_image) {
            if ($srcset_string) {
                $srcset_string .= ', ';
            }
            $srcset_string .= $size_image[0] . ' ' . $size_width . 'w';
            
            // Get WebP version for this size
            $webp_url = pe_mp_get_webp_url($attachment_id);
            if ($webp_url) {
                // Replace extension in URL to get size-specific WebP
                $webp_size_url = str_replace('.webp', '-' . $size_name . '.webp', $webp_url);
                if ($webp_srcset_string) {
                    $webp_srcset_string .= ', ';
                }
                $webp_srcset_string .= $webp_size_url . ' ' . $size_width . 'w';
            }
        }
    }

    // Build attributes string
    $attr_string = '';
    foreach ($attr as $key => $value) {
        if ($key !== 'sizes') { // Skip sizes as it will be handled separately
            $attr_string .= ' ' . $key . '="' . esc_attr($value) . '"';
        }
    }

    if ($webp_srcset_string && pe_mp_browser_supports_webp()) {
        return sprintf(
            '<picture><source srcset="%s" sizes="%s" type="image/webp"><img src="%s" srcset="%s" sizes="%s" width="%d" height="%d"%s></picture>',
            esc_attr($webp_srcset_string),
            esc_attr($attr['sizes'] ?? '100vw'),
            esc_url($original_url),
            esc_attr($srcset_string),
            esc_attr($attr['sizes'] ?? '100vw'),
            $width,
            $height,
            $attr_string
        );
    } else {
        return sprintf(
            '<img src="%s" srcset="%s" sizes="%s" width="%d" height="%d"%s>',
            esc_url($original_url),
            esc_attr($srcset_string),
            esc_attr($attr['sizes'] ?? '100vw'),
            $width,
            $height,
            $attr_string
        );
    }
}

/**
 * Echo responsive image with WebP support
 * 
 * @param int $attachment_id The attachment ID
 * @param array $sizes Array of sizes to generate
 * @param array $attr Additional attributes
 */
function pe_mp_responsive_image($attachment_id, $sizes = array(), $attr = array())
{
    echo pe_mp_get_responsive_image($attachment_id, $sizes, $attr);
}

/**
 * Check if WebP is supported by the browser
 * 
 * @return bool True if WebP is supported
 */
function pe_mp_browser_supports_webp()
{
    // Check if WebP support is already detected
    if (isset($_COOKIE['webp_support'])) {
        return $_COOKIE['webp_support'] === 'true';
    }

    // Check Accept header for WebP support
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        return strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
    }

    // Default to true for modern browsers
    return true;
}

/**
 * Add WebP detection script to head
 */
function pe_mp_webp_detection_script()
{
    ?>
    <script>
    (function() {
        var webp = new Image();
        webp.onload = webp.onerror = function() {
            var isSupported = webp.height === 2;
            document.cookie = 'webp_support=' + isSupported + '; path=/; max-age=31536000';
            
            // Trigger a custom event for other scripts
            var event = new CustomEvent('webpSupportDetected', { detail: { supported: isSupported } });
            document.dispatchEvent(event);
        };
        webp.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
    })();
    </script>
    <?php
}
add_action('wp_head', 'pe_mp_webp_detection_script');

/**
 * Get optimized image URL (WebP if supported, original otherwise)
 * 
 * @param int $attachment_id The attachment ID
 * @param string $size Image size
 * @return string Optimized image URL
 */
function pe_mp_get_optimized_image_url($attachment_id, $size = 'full')
{
    if (!$attachment_id) {
        return '';
    }

    if (pe_mp_browser_supports_webp()) {
        $webp_url = pe_mp_get_webp_url($attachment_id);
        if ($webp_url) {
            return $webp_url;
        }
    }
    
    $image = wp_get_attachment_image_src($attachment_id, $size);
    return $image ? $image[0] : '';
}

/**
 * Echo optimized image URL
 * 
 * @param int $attachment_id The attachment ID
 * @param string $size Image size
 */
function pe_mp_optimized_image_url($attachment_id, $size = 'full')
{
    echo esc_url(pe_mp_get_optimized_image_url($attachment_id, $size));
}

/**
 * Force WebP conversion for an attachment
 * 
 * @param int $attachment_id The attachment ID
 * @return bool True if conversion was successful
 */
function pe_mp_force_webp_conversion($attachment_id)
{
    if (!$attachment_id) {
        return false;
    }

    $file_path = get_attached_file($attachment_id);
    if (!$file_path || !file_exists($file_path)) {
        return false;
    }

    // Check if it's a supported image type
    $image_info = getimagesize($file_path);
    if (!$image_info || !in_array($image_info['mime'], array('image/jpeg', 'image/jpg', 'image/png'))) {
        return false;
    }

    // Get WebP path
    $path_info = pathinfo($file_path);
    $webp_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.webp';

    // Convert to WebP
    $result = pe_mp_convert_image_to_webp($file_path, $webp_path);
    
    if ($result) {
        // Update attachment metadata
        $metadata = wp_get_attachment_metadata($attachment_id);
        $upload_dir = wp_upload_dir();
        
        $webp_relative_path = str_replace($upload_dir['basedir'] . '/', '', $webp_path);
        $webp_url = $upload_dir['baseurl'] . '/' . $webp_relative_path;
        
        $metadata['webp_file'] = $webp_relative_path;
        $metadata['webp_url'] = $webp_url;
        
        wp_update_attachment_metadata($attachment_id, $metadata);
    }

    return $result;
}

/**
 * Convert image to WebP format
 * 
 * @param string $source_path Source image path
 * @param string $webp_path WebP output path
 * @return bool True if conversion was successful
 */
function pe_mp_convert_image_to_webp($source_path, $webp_path)
{
    if (!function_exists('imagewebp')) {
        return false;
    }

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

    // Convert to WebP
    $result = imagewebp($image, $webp_path, 85);
    
    // Clean up
    imagedestroy($image);
    
    return $result;
}

/**
 * Debug function to check WebP status for an image
 * 
 * @param int $attachment_id The attachment ID
 * @return array Debug information
 */
function pe_mp_debug_webp_status($attachment_id)
{
    $debug = array(
        'attachment_id' => $attachment_id,
        'file_exists' => false,
        'file_path' => '',
        'mime_type' => '',
        'webp_exists' => false,
        'webp_path' => '',
        'webp_url' => '',
        'metadata_has_webp' => false,
        'browser_supports_webp' => pe_mp_browser_supports_webp(),
        'server_supports_webp' => function_exists('imagewebp'),
        'errors' => array()
    );

    if (!$attachment_id) {
        $debug['errors'][] = 'No attachment ID provided';
        return $debug;
    }

    $file_path = get_attached_file($attachment_id);
    $debug['file_path'] = $file_path;
    $debug['file_exists'] = file_exists($file_path);

    if (!$file_path || !file_exists($file_path)) {
        $debug['errors'][] = 'File does not exist';
        return $debug;
    }

    $image_info = getimagesize($file_path);
    if ($image_info) {
        $debug['mime_type'] = $image_info['mime'];
    } else {
        $debug['errors'][] = 'Could not get image info';
        return $debug;
    }

    // Check if WebP file exists
    $path_info = pathinfo($file_path);
    $webp_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.webp';
    $debug['webp_path'] = $webp_path;
    $debug['webp_exists'] = file_exists($webp_path);

    // Check metadata
    $metadata = wp_get_attachment_metadata($attachment_id);
    $debug['metadata_has_webp'] = isset($metadata['webp_url']);
    if (isset($metadata['webp_url'])) {
        $debug['webp_url'] = $metadata['webp_url'];
    }

    return $debug;
}

/**
 * Get list of images that need WebP conversion
 * 
 * @param int $limit Number of images to return
 * @return array List of attachment IDs that need conversion
 */
function pe_mp_get_images_needing_webp_conversion($limit = 50)
{
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
        ),
        'fields' => 'ids'
    );

    return get_posts($args);
} 