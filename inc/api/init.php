<?php

/**
 * PE Media Portal Theme - REST API Initialization
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load API endpoints
require_once PE_MP_THEME_DIR . '/inc/api/endpoints/posts.php';

/**
 * Initialize all REST API endpoints
 */
function pe_mp_api_init()
{
    // Register all API endpoints
    pe_mp_register_posts_api_endpoint();
}
add_action('rest_api_init', 'pe_mp_api_init');

/**
 * Add CORS headers for the API endpoints
 */
function pe_mp_add_cors_headers()
{
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/wp-json/pe-mp/v1/') !== false) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            status_header(200);
            exit();
        }
    }
}
add_action('init', 'pe_mp_add_cors_headers'); 