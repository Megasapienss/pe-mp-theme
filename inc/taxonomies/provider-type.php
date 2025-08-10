<?php
/**
 * Provider Type Taxonomy
 * Reusable taxonomy for provider types across different post types
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register Provider Type Taxonomy
function pe_mp_register_provider_type_taxonomy() {
    $labels = array(
        'name'              => _x('Provider Types', 'taxonomy general name', 'pe-mp-theme'),
        'singular_name'     => _x('Provider Type', 'taxonomy singular name', 'pe-mp-theme'),
        'search_items'      => __('Search Provider Types', 'pe-mp-theme'),
        'all_items'         => __('All Provider Types', 'pe-mp-theme'),
        'parent_item'       => __('Parent Provider Type', 'pe-mp-theme'),
        'parent_item_colon' => __('Parent Provider Type:', 'pe-mp-theme'),
        'edit_item'         => __('Edit Provider Type', 'pe-mp-theme'),
        'update_item'       => __('Update Provider Type', 'pe-mp-theme'),
        'add_new_item'      => __('Add New Provider Type', 'pe-mp-theme'),
        'new_item_name'     => __('New Provider Type Name', 'pe-mp-theme'),
        'menu_name'         => __('Provider Types', 'pe-mp-theme'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'provider-type'),
        'show_in_rest'      => true,
    );

    register_taxonomy('provider-type', array('provider', 'service', 'product'), $args);
}
add_action('init', 'pe_mp_register_provider_type_taxonomy');

// Add default terms
function pe_mp_add_default_provider_types() {
    $default_types = array(
        'treatment_facility' => 'Treatment Facility',
        'practitioner' => 'Practitioner',
        'ecom_provider' => 'E-com Provider',
        'digital_platform' => 'Digital Platform',
    );

    foreach ($default_types as $slug => $name) {
        if (!term_exists($slug, 'provider-type')) {
            wp_insert_term($name, 'provider-type', array('slug' => $slug));
        }
    }
}
add_action('init', 'pe_mp_add_default_provider_types'); 