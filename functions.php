<?php

/**
 * PE Media Portal Theme functions and definitions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define basic theme constants first
define('PE_MP_THEME_VERSION', '1.0.0');
define('PE_MP_THEME_DIR', get_template_directory());
define('PE_MP_THEME_URI', get_template_directory_uri());

// Load derived constants
require_once PE_MP_THEME_DIR . '/inc/constants.php';

// Load theme setup
require_once PE_MP_THEME_DIR . '/inc/theme-setup.php';

// Load enqueue scripts and styles
require_once PE_MP_THEME_DIR . '/inc/enqueue.php';

// Load helper functions
require_once PE_MP_THEME_DIR . '/inc/helpers.php';

// Load SVG support
require_once PE_MP_THEME_DIR . '/inc/svg-support.php';

// Load custom post types
require_once PE_MP_THEME_DIR . '/inc/post-types/init.php';

// Load taxonomies
require_once PE_MP_THEME_DIR . '/inc/taxonomies/init.php';

// Load ACF fields
require_once PE_MP_THEME_DIR . '/inc/acf-fields/init.php';

// Include AJAX handlers
require_once PE_MP_THEME_DIR . '/inc/ajax-handlers.php';

// Load shortcodes
require_once PE_MP_THEME_DIR . '/inc/shortcodes.php';

// Load REST API
require_once PE_MP_THEME_DIR . '/inc/api/init.php';

// Load Performance Module (includes WebP functionality)
require_once PE_MP_THEME_DIR . '/inc/performance/init.php';
