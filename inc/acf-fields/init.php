<?php
/**
 * Initialize ACF Fields
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load ACF field groups
require_once get_template_directory() . '/inc/acf-fields/provider-fields.php'; 