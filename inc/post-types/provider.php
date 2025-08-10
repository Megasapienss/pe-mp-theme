<?php
/**
 * Provider Custom Post Type
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register Custom Post Type: Providers
function pe_mp_register_providers_post_type() {
    $labels = array(
        'name'                  => _x('Providers', 'Post type general name', 'pe-mp-theme'),
        'singular_name'         => _x('Provider', 'Post type singular name', 'pe-mp-theme'),
        'menu_name'            => _x('Providers', 'Admin Menu text', 'pe-mp-theme'),
        'name_admin_bar'       => _x('Provider', 'Add New on Toolbar', 'pe-mp-theme'),
        'add_new'              => __('Add New', 'pe-mp-theme'),
        'add_new_item'         => __('Add New Provider', 'pe-mp-theme'),
        'new_item'             => __('New Provider', 'pe-mp-theme'),
        'edit_item'            => __('Edit Provider', 'pe-mp-theme'),
        'view_item'            => __('View Provider', 'pe-mp-theme'),
        'all_items'            => __('All Providers', 'pe-mp-theme'),
        'search_items'         => __('Search Providers', 'pe-mp-theme'),
        'not_found'            => __('No providers found.', 'pe-mp-theme'),
        'not_found_in_trash'   => __('No providers found in Trash.', 'pe-mp-theme'),
        'featured_image'       => __('Provider Image', 'pe-mp-theme'),
        'set_featured_image'   => __('Set provider image', 'pe-mp-theme'),
        'remove_featured_image'=> __('Remove provider image', 'pe-mp-theme'),
        'use_featured_image'   => __('Use as provider image', 'pe-mp-theme'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array(
            'slug' => 'providers',
            'with_front' => false
        ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-businessman',
        'supports'           => array(
            'title',           // Provider name
            'custom-fields',   // For ACF
        ),
        'show_in_rest'       => false, // Disable Gutenberg editor
        'taxonomies'         => array('provider-category', 'provider-tag'),
    );

    register_post_type('provider', $args);
}
add_action('init', 'pe_mp_register_providers_post_type'); 