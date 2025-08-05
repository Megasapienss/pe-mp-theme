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