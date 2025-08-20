<?php
/**
 * Initialize ACF Fields
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load taxonomy helpers first
require_once get_template_directory() . '/inc/taxonomies/taxonomy-helpers.php';

// Load ACF field groups
require_once get_template_directory() . '/inc/acf-fields/provider-fields.php';
// require_once get_template_directory() . '/inc/acf-fields/review-fields.php';
// require_once get_template_directory() . '/inc/acf-fields/verification-fields.php';
require_once get_template_directory() . '/inc/acf-fields/service-fields.php';
// require_once get_template_directory() . '/inc/acf-fields/product-fields.php';
require_once get_template_directory() . '/inc/acf-fields/taxonomy-fields.php';
require_once get_template_directory() . '/inc/acf-fields/post-fields.php';
require_once get_template_directory() . '/inc/acf-fields/header-cta-fields.php';
require_once get_template_directory() . '/inc/acf-fields/sidebar-cta-fields.php';
// require_once get_template_directory() . '/inc/acf-fields/theme-options.php';