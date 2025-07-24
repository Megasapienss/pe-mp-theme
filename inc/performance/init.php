<?php

/**
 * Performance Module Initialization
 * 
 * Loads all performance-related functionality for the PE Media Portal Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load WebP functionality first (required by other modules)
require_once PE_MP_THEME_DIR . '/inc/performance/webp-helpers.php';
require_once PE_MP_THEME_DIR . '/inc/performance/webp-converter.php';

// Load performance modules
require_once PE_MP_THEME_DIR . '/inc/performance/preload.php';
require_once PE_MP_THEME_DIR . '/inc/performance/resource-hints.php';
// require_once PE_MP_THEME_DIR . '/inc/performance/hover-preload.php'; // Disabled for now 