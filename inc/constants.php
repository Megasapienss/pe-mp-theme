<?php
/**
 * Theme Constants and Configuration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Quiz Configuration
define('PE_MP_QUIZ_DEFAULT_LINK', '/tests/assessment/');

// File Paths
define('PE_MP_DIST_DIR', PE_MP_THEME_DIR . '/dist');
define('PE_MP_DIST_URI', PE_MP_THEME_URI . '/dist');

// CSS and JS Paths
define('PE_MP_CSS_FILE', PE_MP_DIST_DIR . '/css/main.css');
define('PE_MP_CSS_URI', PE_MP_DIST_URI . '/css/main.css');
define('PE_MP_JS_FILE', PE_MP_DIST_DIR . '/js/scripts.js');
define('PE_MP_JS_URI', PE_MP_DIST_URI . '/js/scripts.js');

// Provider JS Paths
define('PE_MP_PROVIDER_JS_FILE', PE_MP_DIST_DIR . '/js/components/provider-single.js');
define('PE_MP_PROVIDER_JS_URI', PE_MP_DIST_URI . '/js/components/provider-single.js');

// Image Paths
define('PE_MP_IMAGES_DIR', PE_MP_DIST_DIR . '/images');
define('PE_MP_IMAGES_URI', PE_MP_DIST_URI . '/images');

// Icon Paths
define('PE_MP_ICONS_DIR', PE_MP_DIST_DIR . '/icons');
define('PE_MP_ICONS_URI', PE_MP_DIST_URI . '/icons'); 