<?php

/**
 * PE Media Portal Theme functions and definitions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define theme constants
define('PE_MP_THEME_VERSION', '1.0.0');
define('PE_MP_THEME_DIR', get_template_directory());
define('PE_MP_THEME_URI', get_template_directory_uri());

// Load custom post types
require_once PE_MP_THEME_DIR . '/inc/post-types/init.php';

// Load taxonomies
require_once PE_MP_THEME_DIR . '/inc/taxonomies/init.php';

// Load ACF fields
require_once PE_MP_THEME_DIR . '/inc/acf-fields/init.php';

// Include AJAX handlers
require_once get_template_directory() . '/inc/ajax-handlers.php';

// Load shortcodes
require_once get_template_directory() . '/inc/shortcodes.php';

// Load REST API
require_once get_template_directory() . '/inc/api/init.php';

// Load Performance Module (includes WebP functionality)
require_once get_template_directory() . '/inc/performance/init.php';

// Theme Setup
function pe_mp_theme_setup()
{
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'pe-mp-theme'),
        'footer' => esc_html__('Footer Menu', 'pe-mp-theme'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'pe_mp_theme_setup');

// Enqueue scripts and styles
function pe_mp_theme_scripts()
{
    // Get file modification times for cache busting
    $css_file = PE_MP_THEME_DIR . '/dist/css/main.css';
    $js_file = PE_MP_THEME_DIR . '/dist/js/scripts.js';

    $css_version = file_exists($css_file) ? filemtime($css_file) : PE_MP_THEME_VERSION;
    $js_version = file_exists($js_file) ? filemtime($js_file) : PE_MP_THEME_VERSION;

    // Enqueue main stylesheet
    wp_enqueue_style(
        'pe-mp-theme-style',
        PE_MP_THEME_URI . '/dist/css/main.css',
        array(),
        $css_version
    );

    // Enqueue main JavaScript file
    wp_enqueue_script(
        'pe-mp-theme-script',
        PE_MP_THEME_URI . '/dist/js/scripts.js',
        array('jquery'),
        $js_version,
        true
    );

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Provider Single Page Script
    if (is_singular('provider')) {
        $provider_js_file = PE_MP_THEME_DIR . '/dist/js/components/provider-single.js';
        $provider_js_version = file_exists($provider_js_file) ? filemtime($provider_js_file) : PE_MP_THEME_VERSION;

        wp_enqueue_script(
            'pe-mp-provider-single',
            PE_MP_THEME_URI . '/dist/js/components/provider-single.js',
            array('jquery'),
            $provider_js_version,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'pe_mp_theme_scripts');

/**
 * Get quiz link from theme options
 * 
 * @return string The quiz link URL
 */
function pe_mp_get_quiz_link()
{
    $quiz_link = get_field('quiz_link', 'option');
    return $quiz_link ? $quiz_link : 'https://thepsychedelics.guide/quiz/';
}

/**
 * Allow SVG uploads in WordPress
 */

// Add SVG mime type support
function pe_mp_allow_svg_upload($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'pe_mp_allow_svg_upload');

// Sanitize SVG uploads for security
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

// Fix SVG display in media library
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
