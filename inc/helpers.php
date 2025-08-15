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
            return 'eating-disorder'; // Eating Disorder test ID
        case '/tests/ocd-standalone':
        case '/tests/ocd':
            return 'ocd'; // OCD test ID
        case '/tests/burnout-standalone':
        case '/tests/burnout':
            return 'burnout'; // Burnout test ID
        default:
            return 'assessment';
    }
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