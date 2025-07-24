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

    if ($webp_url) {
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
    $webp_url = pe_mp_get_webp_url($attachment_id);
    $original_image = wp_get_attachment_image_src($attachment_id, 'full');
    
    if (!$original_image) {
        return '';
    }

    $original_url = $original_image[0];
    $width = $original_image[1];
    $height = $original_image[2];

    // Build srcset for original image
    $srcset = array();
    $webp_srcset = array();
    
    foreach ($sizes as $size_name => $size_width) {
        $size_image = wp_get_attachment_image_src($attachment_id, $size_name);
        if ($size_image) {
            $srcset[] = $size_image[0] . ' ' . $size_width . 'w';
            
            // Get WebP version for this size
            $size_webp_url = pe_mp_get_webp_url($attachment_id);
            if ($size_webp_url) {
                // Replace size in WebP URL
                $size_webp_url = str_replace('.webp', '-' . $size_name . '.webp', $size_webp_url);
                $webp_srcset[] = $size_webp_url . ' ' . $size_width . 'w';
            }
        }
    }

    // Build attributes string
    $attr_string = '';
    foreach ($attr as $key => $value) {
        $attr_string .= ' ' . $key . '="' . esc_attr($value) . '"';
    }

    $srcset_string = implode(', ', $srcset);
    $webp_srcset_string = implode(', ', $webp_srcset);

    if ($webp_url && !empty($webp_srcset)) {
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