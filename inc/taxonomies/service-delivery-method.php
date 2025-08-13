<?php
/**
 * Service Delivery Method Taxonomy
 * Reusable taxonomy for service delivery methods across different post types
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register Service Delivery Method Taxonomy
function pe_mp_register_service_delivery_method_taxonomy() {
    $labels = array(
        'name'              => _x('Service Delivery Methods', 'taxonomy general name', 'pe-mp-theme'),
        'singular_name'     => _x('Service Delivery Method', 'taxonomy singular name', 'pe-mp-theme'),
        'search_items'      => __('Search Service Delivery Methods', 'pe-mp-theme'),
        'all_items'         => __('All Service Delivery Methods', 'pe-mp-theme'),
        'parent_item'       => __('Parent Service Delivery Method', 'pe-mp-theme'),
        'parent_item_colon' => __('Parent Service Delivery Method:', 'pe-mp-theme'),
        'edit_item'         => __('Edit Service Delivery Method', 'pe-mp-theme'),
        'update_item'       => __('Update Service Delivery Method', 'pe-mp-theme'),
        'add_new_item'      => __('Add New Service Delivery Method', 'pe-mp-theme'),
        'new_item_name'     => __('New Service Delivery Method Name', 'pe-mp-theme'),
        'menu_name'         => __('Service Delivery Methods', 'pe-mp-theme'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'service-delivery-method'),
        'show_in_rest'      => true,
    );

    register_taxonomy('service-delivery-method', array('provider', 'service'), $args);
}
add_action('init', 'pe_mp_register_service_delivery_method_taxonomy');

// Add default terms
function pe_mp_add_default_service_delivery_methods() {
    $default_methods = array(
        'hybrid' => 'Hybrid',
        'online' => 'Online',
        'in-person' => 'In-person',
    );

    foreach ($default_methods as $slug => $name) {
        if (!term_exists($slug, 'service-delivery-method')) {
            wp_insert_term($name, 'service-delivery-method', array('slug' => $slug));
        }
    }
}
add_action('init', 'pe_mp_add_default_service_delivery_methods');
