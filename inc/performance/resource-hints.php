<?php

/**
 * Resource Hints Module
 * 
 * Handles resource hints and preconnect optimization for better performance
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add resource hints for better performance
 */
function pe_mp_resource_hints($hints, $relation_type)
{
    if ($relation_type === 'preconnect') {
        // Add preconnect for external domains
        $hints[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin' => '',
        );
        $hints[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => '',
        );
    }
    
    return $hints;
}
add_filter('wp_resource_hints', 'pe_mp_resource_hints', 10, 2); 