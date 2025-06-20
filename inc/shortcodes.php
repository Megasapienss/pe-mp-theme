<?php

/**
 * Shortcodes for PE Media Portal Theme
 *
 * @package PE_MP_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Quiz Banner Shortcode
 * 
 * Usage: [quiz_banner] or [quiz_banner class="custom-class" link="https://example.com"]
 * 
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function pe_mp_quiz_banner_shortcode($atts)
{
    // Parse shortcode attributes
    $atts = shortcode_atts(array(
        'class' => '',
        'link' => '',
    ), $atts, 'quiz_banner');

    // Use provided link or fall back to default quiz link
    $link = !empty($atts['link']) ? $atts['link'] : pe_mp_get_quiz_link();

    // Start output buffering to capture the template part
    ob_start();

    // Include the quiz banner template part
    get_template_part('template-parts/banners/quiz', null, array(
        'class' => $atts['class'],
        'link' => $link
    ));

    // Return the buffered content
    return ob_get_clean();
}
add_shortcode('quiz_banner', 'pe_mp_quiz_banner_shortcode');
