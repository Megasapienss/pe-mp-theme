<?php
/**
 * Initialize Taxonomies
 * 
 * This file initializes all custom taxonomies for the theme.
 * Each taxonomy is defined in its own file in the taxonomies directory.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Load all taxonomy files
 */
function pe_mp_load_taxonomies() {
    // Get all PHP files in the taxonomies directory
    $taxonomy_files = glob(get_template_directory() . '/inc/taxonomies/*.php');
    
    // Load each taxonomy file
    foreach ($taxonomy_files as $file) {
        // Skip this init file
        if (basename($file) === 'init.php') {
            continue;
        }
        
        require_once $file;
    }
}

// Load taxonomies
pe_mp_load_taxonomies(); 