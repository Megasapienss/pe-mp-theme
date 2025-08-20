<?php
/**
 * Script and Style Enqueuing
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue scripts and styles
 */
function pe_mp_theme_scripts()
{
    // Get file modification times for cache busting
    $css_version = file_exists(PE_MP_CSS_FILE) ? filemtime(PE_MP_CSS_FILE) : PE_MP_THEME_VERSION;
    $js_version = file_exists(PE_MP_JS_FILE) ? filemtime(PE_MP_JS_FILE) : PE_MP_THEME_VERSION;

    // Enqueue main stylesheet
    wp_enqueue_style(
        'pe-mp-theme-style',
        PE_MP_CSS_URI,
        array(),
        $css_version
    );

    // Enqueue main JavaScript file
    wp_enqueue_script(
        'pe-mp-theme-script',
        PE_MP_JS_URI,
        array('jquery'),
        $js_version,
        true
    );

    // Localize AJAX for provider filter
    wp_localize_script('pe-mp-theme-script', 'pe_mp_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('pe_mp_filter_nonce')
    ));

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

}
add_action('wp_enqueue_scripts', 'pe_mp_theme_scripts'); 