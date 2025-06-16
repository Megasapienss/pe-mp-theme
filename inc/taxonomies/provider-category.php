<?php
/**
 * Provider Categories Taxonomy
 * 
 * This file registers the provider categories taxonomy.
 * Categories are hierarchical and used to organize providers into main categories.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Provider Categories Taxonomy
 */
function pe_mp_register_provider_categories_taxonomy() {
    $labels = array(
        'name'                       => _x('Provider Categories', 'taxonomy general name', 'pe-mp-theme'),
        'singular_name'              => _x('Provider Category', 'taxonomy singular name', 'pe-mp-theme'),
        'search_items'               => __('Search Categories', 'pe-mp-theme'),
        'popular_items'              => __('Popular Categories', 'pe-mp-theme'),
        'all_items'                  => __('All Categories', 'pe-mp-theme'),
        'parent_item'                => __('Parent Category', 'pe-mp-theme'),
        'parent_item_colon'          => __('Parent Category:', 'pe-mp-theme'),
        'edit_item'                  => __('Edit Category', 'pe-mp-theme'),
        'update_item'                => __('Update Category', 'pe-mp-theme'),
        'add_new_item'               => __('Add New Category', 'pe-mp-theme'),
        'new_item_name'              => __('New Category Name', 'pe-mp-theme'),
        'menu_name'                  => __('Categories', 'pe-mp-theme'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'           => true,
        'show_ui'          => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'    => false,
        'show_in_rest'     => true,
        'rewrite'          => array(
            'slug'         => 'provider-category',
            'with_front'   => false,
            'hierarchical' => true
        ),
    );

    register_taxonomy('provider-category', 'provider', $args);
}
add_action('init', 'pe_mp_register_provider_categories_taxonomy'); 