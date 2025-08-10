<?php
/**
 * Facility Type Taxonomy
 * Reusable taxonomy for facility types across different post types
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register Facility Type Taxonomy
function pe_mp_register_facility_type_taxonomy() {
    $labels = array(
        'name'              => _x('Facility Types', 'taxonomy general name', 'pe-mp-theme'),
        'singular_name'     => _x('Facility Type', 'taxonomy singular name', 'pe-mp-theme'),
        'search_items'      => __('Search Facility Types', 'pe-mp-theme'),
        'all_items'         => __('All Facility Types', 'pe-mp-theme'),
        'parent_item'       => __('Parent Facility Type', 'pe-mp-theme'),
        'parent_item_colon' => __('Parent Facility Type:', 'pe-mp-theme'),
        'edit_item'         => __('Edit Facility Type', 'pe-mp-theme'),
        'update_item'       => __('Update Facility Type', 'pe-mp-theme'),
        'add_new_item'      => __('Add New Facility Type', 'pe-mp-theme'),
        'new_item_name'     => __('New Facility Type Name', 'pe-mp-theme'),
        'menu_name'         => __('Facility Types', 'pe-mp-theme'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'facility-type'),
        'show_in_rest'      => true,
    );

    register_taxonomy('facility-type', array('provider'), $args);
}
add_action('init', 'pe_mp_register_facility_type_taxonomy');

// Add default terms
function pe_mp_add_default_facility_types() {
    $default_types = array(
        'medical_facility' => 'Medical Facility',
        'treatment_center' => 'Treatment Center',
        'wellness_center' => 'Wellness Center',
        'telemedicine_provider' => 'Telemedicine Provider',
        'community_center' => 'Community Center',
    );

    foreach ($default_types as $slug => $name) {
        if (!term_exists($slug, 'facility-type')) {
            wp_insert_term($name, 'facility-type', array('slug' => $slug));
        }
    }
}
add_action('init', 'pe_mp_add_default_facility_types');
