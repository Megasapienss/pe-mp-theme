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
 * Usage: [quiz_banner] or [quiz_banner class="custom-class" link="https://example.com" title="Custom Title" description="Custom description"]
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
        'title' => '',
        'description' => '',
    ), $atts, 'quiz_banner');

    // Priority system for link:
    // 1. Shortcode argument (highest priority)
    // 2. Assigned page from ACF field
    // 3. Hardcoded link (lowest priority)
    $link = '';
    
    if (!empty($atts['link'])) {
        // Priority 1: Shortcode argument
        $link = $atts['link'];
    } else {
        // Priority 2: Assigned page from ACF field
        $related_test_url = pe_mp_get_related_test_page_url();
        if (!empty($related_test_url)) {
            $link = $related_test_url;
        } else {
            // Priority 3: Hardcoded link
            $link = PE_MP_QUIZ_DEFAULT_LINK;
        }
    }

    // Start output buffering to capture the template part
    ob_start();

    // Include the quiz banner template part
    get_template_part('template-parts/banners/quiz', null, array(
        'class' => $atts['class'],
        'link' => $link,
        'title' => $atts['title'],
        'description' => $atts['description']
    ));

    // Return the buffered content
    return ob_get_clean();
}
add_shortcode('quiz_banner', 'pe_mp_quiz_banner_shortcode');

/**
 * Newsletter Banner Shortcode
 * 
 * Usage: [newsletter_banner] or [newsletter_banner class="custom-class" link="https://example.com"]
 *  
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function pe_mp_newsletter_banner_shortcode($atts)
{
    // Parse shortcode attributes
    $atts = shortcode_atts(array(
        'class' => '',
    ), $atts, 'newsletter_banner');

    // Start output buffering to capture the template part
    ob_start();

    // Include the quiz banner template part
    get_template_part('template-parts/banners/newsletter', null, array(
        'class' => $atts['class'],
    ));

    // Return the buffered content
    return ob_get_clean();
}
add_shortcode('newsletter_banner', 'pe_mp_newsletter_banner_shortcode');

/**
 * Provider Card Shortcode
 * 
 * Usage: [provider_card id="123"] or [provider_card id="123" size="large" orientation="horizontal"]
 *  
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function pe_mp_provider_card_shortcode($atts)
{
    // Parse shortcode attributes
    $atts = shortcode_atts(array(
        'id' => 0,
        'size' => 'compact',
        'orientation' => '',
    ), $atts, 'provider_card');

    // Validate ID
    $provider_id = intval($atts['id']);
    if (!$provider_id || get_post_type($provider_id) !== 'provider') {
        return '<p>Error: Invalid provider ID or provider not found.</p>';
    }

    // Get the provider post object
    $provider = get_post($provider_id);
    if (!$provider) {
        return '<p>Error: Provider not found.</p>';
    }

    // Start output buffering to capture the template part
    ob_start();

    // Include the provider card template part
    get_template_part('template-parts/cards/provider', '', array(
        'post' => $provider,
        'size' => $atts['size'],
        'orientation' => $atts['orientation'],
    ));

    // Return the buffered content
    return ob_get_clean();
}
add_shortcode('provider_card', 'pe_mp_provider_card_shortcode');
