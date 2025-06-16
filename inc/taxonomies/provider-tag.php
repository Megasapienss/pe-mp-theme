<?php
/**
 * Provider Tags Taxonomy
 * 
 * This file registers the provider tags taxonomy.
 * Tags are non-hierarchical and used for flexible tagging of providers.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Provider Tags Taxonomy
 */
function pe_mp_register_provider_tags_taxonomy() {
    $labels = array(
        'name'                       => _x('Provider Tags', 'taxonomy general name', 'pe-mp-theme'),
        'singular_name'              => _x('Provider Tag', 'taxonomy singular name', 'pe-mp-theme'),
        'search_items'               => __('Search Tags', 'pe-mp-theme'),
        'popular_items'              => __('Popular Tags', 'pe-mp-theme'),
        'all_items'                  => __('All Tags', 'pe-mp-theme'),
        'edit_item'                  => __('Edit Tag', 'pe-mp-theme'),
        'update_item'                => __('Update Tag', 'pe-mp-theme'),
        'add_new_item'               => __('Add New Tag', 'pe-mp-theme'),
        'new_item_name'              => __('New Tag Name', 'pe-mp-theme'),
        'separate_items_with_commas' => __('Separate tags with commas', 'pe-mp-theme'),
        'add_or_remove_items'        => __('Add or remove tags', 'pe-mp-theme'),
        'choose_from_most_used'      => __('Choose from the most used tags', 'pe-mp-theme'),
        'menu_name'                  => __('Tags', 'pe-mp-theme'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'           => true,
        'show_ui'          => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'    => true,
        'show_in_rest'     => true,
        'rewrite'          => array(
            'slug'         => 'provider-tag',
            'with_front'   => false
        ),
    );

    register_taxonomy('provider-tag', 'provider', $args);
}
add_action('init', 'pe_mp_register_provider_tags_taxonomy'); 