<?php
/**
 * Cost Range Taxonomy
 * 
 * Standardized price tier indicators for providers
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function register_cost_range_taxonomy() {
    $labels = array(
        'name' => 'Cost Ranges',
        'singular_name' => 'Cost Range',
        'menu_name' => 'Cost Ranges',
        'all_items' => 'All Cost Ranges',
        'edit_item' => 'Edit Cost Range',
        'view_item' => 'View Cost Range',
        'update_item' => 'Update Cost Range',
        'add_new_item' => 'Add New Cost Range',
        'new_item_name' => 'New Cost Range Name',
        'parent_item' => 'Parent Cost Range',
        'parent_item_colon' => 'Parent Cost Range:',
        'search_items' => 'Search Cost Ranges',
        'popular_items' => 'Popular Cost Ranges',
        'separate_items_with_commas' => 'Separate cost ranges with commas',
        'add_or_remove_items' => 'Add or remove cost ranges',
        'choose_from_most_used' => 'Choose from the most used cost ranges',
        'not_found' => 'No cost ranges found',
        'no_terms' => 'No cost ranges',
        'filter_by_item' => 'Filter by cost range',
        'items_list_navigation' => 'Cost ranges list navigation',
        'items_list' => 'Cost ranges list',
        'back_to_items' => '← Back to Cost Ranges',
        'item_link' => 'Cost Range Link',
        'item_link_description' => 'A link to a cost range',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'show_tagcloud' => false,
        'show_in_rest' => true,
        'rest_base' => 'cost-range',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'query_var' => 'cost-range',
        'rewrite' => array('slug' => 'cost-range'),
        'capabilities' => array(
            'manage_terms' => 'manage_categories',
            'edit_terms' => 'manage_categories',
            'delete_terms' => 'manage_categories',
            'assign_terms' => 'edit_posts',
        ),
    );

    register_taxonomy('cost-range', array('provider', 'service', 'product'), $args);

    // Add default terms
    $default_ranges = array(
        'budget' => '$',
        'mid_range' => '$$',
        'premium' => '$$$',
        'luxury' => '$$$$',
    );

    foreach ($default_ranges as $slug => $name) {
        if (!term_exists($slug, 'cost-range')) {
            wp_insert_term($name, 'cost-range', array('slug' => $slug));
        }
    }
}

add_action('init', 'register_cost_range_taxonomy');
