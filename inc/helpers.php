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
 * @return string The related test page URL or empty string if not found
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
    
    return '';
} 