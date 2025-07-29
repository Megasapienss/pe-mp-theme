<?php
/**
 * Initialize ACF Fields
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load ACF field groups
require_once get_template_directory() . '/inc/acf-fields/provider-fields.php';
require_once get_template_directory() . '/inc/acf-fields/taxonomy-fields.php';
require_once get_template_directory() . '/inc/acf-fields/post-fields.php';
// require_once get_template_directory() . '/inc/acf-fields/theme-options.php';