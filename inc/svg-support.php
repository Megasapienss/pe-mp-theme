<?php
/**
 * SVG Upload Support
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add SVG mime type support
 */
function pe_mp_allow_svg_upload($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'pe_mp_allow_svg_upload');

/**
 * Sanitize SVG uploads for security
 */
function pe_mp_sanitize_svg($file)
{
    if ($file['type'] === 'image/svg+xml') {
        if (!function_exists('simplexml_load_file')) {
            return $file;
        }

        // Check if the file is actually an SVG
        $file_content = file_get_contents($file['tmp_name']);
        if (strpos($file_content, '<svg') === false) {
            $file['error'] = 'Invalid SVG file.';
            return $file;
        }

        // Basic security check - remove potentially dangerous elements
        $file_content = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $file_content);
        $file_content = preg_replace('/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/mi', '', $file_content);
        $file_content = preg_replace('/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/mi', '', $file_content);
        $file_content = preg_replace('/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi', '', $file_content);
        $file_content = preg_replace('/<link\b[^<]*(?:(?!<\/link>)<[^<]*)*<\/link>/mi', '', $file_content);
        $file_content = preg_replace('/<meta\b[^<]*(?:(?!<\/meta>)<[^<]*)*<\/meta>/mi', '', $file_content);
        $file_content = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/mi', '', $file_content);

        // Write the sanitized content back to the file
        file_put_contents($file['tmp_name'], $file_content);
    }
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'pe_mp_sanitize_svg');

/**
 * Fix SVG display in media library
 */
function pe_mp_fix_svg_display()
{
    echo '<style>
        .attachment-266x266, .thumbnail img {
            width: 100% !important;
            height: auto !important;
        }
        .wp-admin .attachment-preview .thumbnail img {
            width: 100% !important;
            height: auto !important;
        }
    </style>';
}
add_action('admin_head', 'pe_mp_fix_svg_display'); 