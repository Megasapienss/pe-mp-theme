<?php
/**
 * Initialize Custom Post Types
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load all custom post types
require_once get_template_directory() . '/inc/post-types/provider.php';
require_once get_template_directory() . '/inc/post-types/review.php';
require_once get_template_directory() . '/inc/post-types/verification.php';
require_once get_template_directory() . '/inc/post-types/service.php';
require_once get_template_directory() . '/inc/post-types/product.php'; 