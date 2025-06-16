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

// Theme Setup
function pe_mp_theme_setup() {
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
function pe_mp_theme_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style(
        'pe-mp-theme-style',
        PE_MP_THEME_URI . '/dist/css/main.css',
        array(),
        PE_MP_THEME_VERSION
    );

    // Enqueue main JavaScript file
    wp_enqueue_script(
        'pe-mp-theme-script',
        PE_MP_THEME_URI . '/dist/js/main.js',
        array('jquery'),
        PE_MP_THEME_VERSION,
        true
    );

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Provider Single Page Script
    if (is_singular('provider')) {
        $js_file = get_template_directory() . '/dist/js/components/provider-single.js';
        $js_version = file_exists($js_file) ? filemtime($js_file) : PE_MP_THEME_VERSION;
        
        wp_enqueue_script(
            'pe-mp-provider-single',
            get_template_directory_uri() . '/dist/js/components/provider-single.js',
            array('jquery'),
            $js_version,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'pe_mp_theme_scripts'); 