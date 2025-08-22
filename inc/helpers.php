<?php
/**
 * Helper Functions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get related test page URL for a post
 * 
 * @param int $post_id Post ID (optional, defaults to current post)
 * @return string The related test page URL or '/tests/assessment/' if not found
 */
function pe_mp_get_related_test_page_url($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $field_value = get_field('related_test_page', $post_id);
    
    if ($field_value) {
        // If it's already a URL, return it directly
        if (filter_var($field_value, FILTER_VALIDATE_URL)) {
            return $field_value;
        }
        
        // If it's an ID, get the permalink
        if (is_numeric($field_value)) {
            return get_permalink($field_value);
        }
    }
    
    return '/tests/assessment/';
}

/**
 * Get related test ID for a post
 * 
 * @param int $post_id Post ID (optional, defaults to current post)
 * @return string|false The related test ID as string or false if not found
 */
function pe_mp_get_related_test_id($post_id = null)
{
    $test_url = pe_mp_get_related_test_page_url($post_id);

    if (!$test_url) {
        return 'assessment';
    }

    // Strip domain name and get only the path
    $path = parse_url($test_url, PHP_URL_PATH);
    $path = rtrim($path, '/');

    switch ($path) {
        case '/tests/assessment':
            return 'assessment'; // Assessment test ID
        case '/tests/anxiety-standalone':
        case '/tests/anxiety':
            return 'anxiety'; // Anxiety test ID
        case '/tests/depression-standalone':
        case '/tests/depression':
            return 'depression'; // Depression test ID
        case '/tests/adhd-standalone':
        case '/tests/adhd':
            return 'adhd'; // ADHD test ID
        case '/tests/ptsd-standalone':
        case '/tests/ptsd':
            return 'ptsd'; // PTSD test ID
        case '/tests/eating-disorder-standalone':
        case '/tests/eating-disorder':
            return 'ed'; // Eating Disorder test ID
        case '/tests/ocd-standalone':
        case '/tests/ocd':
            return 'ocd'; // OCD test ID
        case '/tests/burnout-standalone':
        case '/tests/burnout':
            return 'burnout'; // Burnout test ID
        case '/tests/bipolar-disorder-standalone':
        case '/tests/bipolar-disorder':
            return 'bipolar_disorder'; // Bipolar Disorder test ID
        default:
            return 'assessment';
    }
} 

/**
 * Get custom header CTA button data
 * 
 * @param int $post_id Post ID (optional, defaults to current post)
 * @return array|false Array with 'text' and 'link' keys, or false if not set
 */
function pe_mp_get_header_cta_data($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $cta_text = get_field('header_cta_text', $post_id);
    $cta_link = get_field('header_cta_link', $post_id);
    
    // Only return data if both text and link are provided
    if (!empty($cta_text) && !empty($cta_link)) {
        return array(
            'text' => $cta_text,
            'link' => $cta_link
        );
    }
    
    return false;
}

/**
 * Get custom sidebar CTA banner data
 * 
 * @param int $post_id Post ID (optional, defaults to current post)
 * @return array|false Array with 'image' and 'link' keys, or false if not set
 */
function pe_mp_get_sidebar_cta_data($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $cta_image = get_field('sidebar_cta_image', $post_id);
    $cta_link = get_field('sidebar_cta_link', $post_id);
    
    // Only return data if both image and link are provided
    if (!empty($cta_image) && !empty($cta_link)) {
        return array(
            'image' => $cta_image,
            'link' => $cta_link
        );
    }
    
    return false;
}

/**
 * Get embedded video HTML from URL
 * 
 * @param string $video_url The video URL to embed
 * @param array $args Additional arguments for the embed
 * @return string HTML output for the embedded video
 */
function pe_mp_get_video_embed($video_url, $args = array())
{
    if (empty($video_url)) {
        return '';
    }

    // Default arguments
    $defaults = array(
        'width' => 640,
        'height' => 360,
        'class' => 'video-embed',
        'title' => '',
        'autoplay' => false,
        'muted' => false,
        'controls' => true,
        'loop' => false,
        'rel' => false,
        'preload_metadata' => false,
        'lazy_load' => false,
    );

    $args = wp_parse_args($args, $defaults);

    // Use WordPress oEmbed to get the embed HTML
    $embed_html = wp_oembed_get($video_url, $args);

    if (!$embed_html) {
        // Fallback: create a basic iframe if oEmbed fails
        $iframe_attributes = array(
            'src' => esc_url($video_url),
            'width' => $args['width'],
            'height' => $args['height'],
            'class' => esc_attr($args['class']),
            'frameborder' => '0',
            'allowfullscreen' => '',
        );

        // Add preload metadata if enabled
        if ($args['preload_metadata']) {
            $iframe_attributes['preload'] = 'metadata';
        } else {
            $iframe_attributes['preload'] = 'none';
        }

        // Add lazy loading if enabled
        if ($args['lazy_load']) {
            $iframe_attributes['loading'] = 'lazy';
        }

        $iframe_attr_string = '';
        foreach ($iframe_attributes as $key => $value) {
            if ($value === '') {
                $iframe_attr_string .= ' ' . $key;
            } else {
                $iframe_attr_string .= ' ' . $key . '="' . $value . '"';
            }
        }

        $embed_html = sprintf(
            '<iframe%s></iframe>',
            $iframe_attr_string
        );
    } else {
        // For oEmbed content, we need to modify the HTML to add preload attributes
        if ($args['preload_metadata'] || $args['lazy_load']) {
            // Add preload and loading attributes to iframe elements
            if (strpos($embed_html, '<iframe') !== false) {
                $embed_html = preg_replace(
                    '/<iframe([^>]*)>/i',
                    '<iframe$1' . 
                    ($args['preload_metadata'] ? ' preload="metadata"' : ' preload="none"') .
                    ($args['lazy_load'] ? ' loading="lazy"' : '') . 
                    '>',
                    $embed_html
                );
            }
        }
    }

    // Wrap in a responsive container
    $output = sprintf(
        '<div class="video-embed-wrapper">
            <div>
                %s
            </div>
        </div>',
        $embed_html
    );

    return $output;
}

/**
 * Echo embedded video HTML
 * 
 * @param string $video_url The video URL to embed
 * @param array $args Additional arguments for the embed
 */
function pe_mp_video_embed($video_url, $args = array())
{
    echo pe_mp_get_video_embed($video_url, $args);
}

/**
 * Get the deepest/most specific category for a post
 * 
 * @param int $post_id Post ID (optional, defaults to current post)
 * @return WP_Term|null The deepest category object or null if no categories found
 */
function pe_mp_get_deepest_category($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    
    if (empty($categories)) {
        return null;
    }
    
    $deepest_category = null;
    $max_depth = 0;
    
    foreach ($categories as $category) {
        $depth = 0;
        $current_cat = $category;
        
        // Count how many levels deep this category is
        while ($current_cat->parent != 0) {
            $depth++;
            $current_cat = get_term($current_cat->parent, 'category');
            if (!$current_cat || is_wp_error($current_cat)) {
                break;
            }
        }
        
        // If this category is deeper than our current deepest, update it
        if ($depth > $max_depth) {
            $max_depth = $depth;
            $deepest_category = $category;
        }
    }
    
    // Return the deepest category, or the first one if no hierarchy found
    return $deepest_category ? $deepest_category : $categories[0];
} 

/**
 * Get medical review data for a post
 * 
 * @param int $post_id Post ID (optional, defaults to current post)
 * @return array|false Array with medical review data or false if not reviewed
 */
function pe_mp_get_medical_review_data($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $review_section = get_field('medical_review_section', $post_id);
    
    if (!$review_section || empty($review_section['medical_reviewer'])) {
        return false;
    }
    
    $reviewer_id = $review_section['medical_reviewer'];
    $reviewer = get_post($reviewer_id);
    
    if (!$reviewer || $reviewer->post_type !== 'provider') {
        return false;
    }
    
    // Get reviewer details
    $reviewer_data = array(
        'id' => $reviewer_id,
        'name' => $reviewer->post_title,
        'url' => get_permalink($reviewer_id),
        'image' => get_field('image_url', $reviewer_id),
        'credentials' => pe_mp_get_provider_credentials($reviewer_id),
        'review_date' => $review_section['review_date'],
        'review_quote' => $review_section['review_quote'],
        'show_review_block' => $review_section['show_review_block'] ?? true,
    );
    
    return $reviewer_data;
}

/**
 * Check if a post has been medically reviewed
 * 
 * @param int $post_id Post ID (optional, defaults to current post)
 * @return bool True if post has been medically reviewed
 */
function pe_mp_has_medical_review($post_id = null) {
    $review_data = pe_mp_get_medical_review_data($post_id);
    return $review_data !== false;
}

/**
 * Get provider credentials for display
 * 
 * @param int $provider_id Provider post ID
 * @return string Formatted credentials string
 */
function pe_mp_get_provider_credentials($provider_id) {
    $credentials = array();
    
    // Get provider type
    $provider_types = get_the_terms($provider_id, 'provider-type');
    if ($provider_types && !is_wp_error($provider_types)) {
        foreach ($provider_types as $type) {
            if ($type->slug === 'practitioner') {
                $credentials[] = 'MD';
                break;
            }
        }
    }
    
    // Get verifications for additional credentials
    $verifications = get_field('verifications_relation', $provider_id);
    if ($verifications) {
        foreach ($verifications as $verification_id) {
            $verification = get_post($verification_id);
            if ($verification && $verification->post_type === 'verification') {
                $verification_title = $verification->post_title;
                if (strpos($verification_title, 'PhD') !== false) {
                    $credentials[] = 'PhD';
                } elseif (strpos($verification_title, 'Licensed') !== false) {
                    $credentials[] = 'Licensed';
                }
            }
        }
    }
    
    // Remove duplicates and format
    $credentials = array_unique($credentials);
    
    if (empty($credentials)) {
        return 'Healthcare Professional';
    }
    
    return implode(', ', $credentials);
}

/**
 * Format review date for display
 * 
 * @param string $date_string Date string in Y-m-d format
 * @return string Formatted date string
 */
function pe_mp_format_review_date($date_string) {
    if (!$date_string) {
        return '';
    }
    
    $date = DateTime::createFromFormat('Y-m-d', $date_string);
    if (!$date) {
        return '';
    }
    
    return $date->format('j M Y');
} 