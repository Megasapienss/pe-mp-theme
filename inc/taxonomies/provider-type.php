<?php
/**
 * Provider Type Taxonomy
 * 
 * This file registers the provider type taxonomy.
 * Provider types are hierarchical and used to categorize providers by their type
 * (e.g., Primary Care Physician, Specialist, etc.).
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Provider Type Taxonomy
 */
function pe_mp_register_provider_type_taxonomy() {
    $labels = array(
        'name'                       => _x('Provider Types', 'taxonomy general name', 'pe-mp-theme'),
        'singular_name'              => _x('Provider Type', 'taxonomy singular name', 'pe-mp-theme'),
        'search_items'               => __('Search Provider Types', 'pe-mp-theme'),
        'popular_items'              => __('Popular Provider Types', 'pe-mp-theme'),
        'all_items'                  => __('All Provider Types', 'pe-mp-theme'),
        'parent_item'                => __('Parent Provider Type', 'pe-mp-theme'),
        'parent_item_colon'          => __('Parent Provider Type:', 'pe-mp-theme'),
        'edit_item'                  => __('Edit Provider Type', 'pe-mp-theme'),
        'update_item'                => __('Update Provider Type', 'pe-mp-theme'),
        'add_new_item'               => __('Add New Provider Type', 'pe-mp-theme'),
        'new_item_name'              => __('New Provider Type Name', 'pe-mp-theme'),
        'separate_items_with_commas' => __('Separate provider types with commas', 'pe-mp-theme'),
        'add_or_remove_items'        => __('Add or remove provider types', 'pe-mp-theme'),
        'choose_from_most_used'      => __('Choose from the most used provider types', 'pe-mp-theme'),
        'menu_name'                  => __('Provider Types', 'pe-mp-theme'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'           => true,
        'show_ui'          => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'    => false,
        'show_in_rest'     => true, // Enable Gutenberg editor support
        'rewrite'          => array(
            'slug'         => 'provider-type',
            'with_front'   => false,
            'hierarchical' => true
        ),
    );

    register_taxonomy('provider-type', 'provider', $args);
}
add_action('init', 'pe_mp_register_provider_type_taxonomy'); 