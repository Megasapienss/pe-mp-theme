<?php
/**
 * Service Custom Post Type
 * Used for service offerings with pricing
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register Custom Post Type: Services
function pe_mp_register_services_post_type() {
    $labels = array(
        'name'                  => _x('Services', 'Post type general name', 'pe-mp-theme'),
        'singular_name'         => _x('Service', 'Post type singular name', 'pe-mp-theme'),
        'menu_name'            => _x('Services', 'Admin Menu text', 'pe-mp-theme'),
        'name_admin_bar'       => _x('Service', 'Add New on Toolbar', 'pe-mp-theme'),
        'add_new'              => __('Add New', 'pe-mp-theme'),
        'add_new_item'         => __('Add New Service', 'pe-mp-theme'),
        'new_item'             => __('New Service', 'pe-mp-theme'),
        'edit_item'            => __('Edit Service', 'pe-mp-theme'),
        'view_item'            => __('View Service', 'pe-mp-theme'),
        'all_items'            => __('All Services', 'pe-mp-theme'),
        'search_items'         => __('Search Services', 'pe-mp-theme'),
        'not_found'            => __('No services found.', 'pe-mp-theme'),
        'not_found_in_trash'   => __('No services found in Trash.', 'pe-mp-theme'),
        'featured_image'       => __('Service Image', 'pe-mp-theme'),
        'set_featured_image'   => __('Set service image', 'pe-mp-theme'),
        'remove_featured_image'=> __('Remove service image', 'pe-mp-theme'),
        'use_featured_image'   => __('Use as service image', 'pe-mp-theme'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false, // Not publicly queryable
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 27,
        'menu_icon'          => 'dashicons-clipboard',
        'supports'           => array(
            'title',           // Service name
            'custom-fields',   // For ACF
        ),
        'show_in_rest'       => false, // Disable Gutenberg editor
        'taxonomies'         => array('service-category', 'service-tag'),
    );

    register_post_type('service', $args);
}
add_action('init', 'pe_mp_register_services_post_type');
