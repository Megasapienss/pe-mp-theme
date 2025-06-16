<?php
/**
 * Initialize Custom Post Types
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load all custom post types
require_once get_template_directory() . '/inc/post-types/provider.php'; 