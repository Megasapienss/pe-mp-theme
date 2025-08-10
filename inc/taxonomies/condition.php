<?php
/**
 * Condition Taxonomy
 * 
 * Medical/wellness conditions treated by providers
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function register_condition_taxonomy() {
    $labels = array(
        'name' => 'Conditions',
        'singular_name' => 'Condition',
        'menu_name' => 'Conditions',
        'all_items' => 'All Conditions',
        'edit_item' => 'Edit Condition',
        'view_item' => 'View Condition',
        'update_item' => 'Update Condition',
        'add_new_item' => 'Add New Condition',
        'new_item_name' => 'New Condition Name',
        'parent_item' => 'Parent Condition',
        'parent_item_colon' => 'Parent Condition:',
        'search_items' => 'Search Conditions',
        'popular_items' => 'Popular Conditions',
        'separate_items_with_commas' => 'Separate conditions with commas',
        'add_or_remove_items' => 'Add or remove conditions',
        'choose_from_most_used' => 'Choose from the most used conditions',
        'not_found' => 'No conditions found',
        'no_terms' => 'No conditions',
        'filter_by_item' => 'Filter by condition',
        'items_list_navigation' => 'Conditions list navigation',
        'items_list' => 'Conditions list',
        'back_to_items' => '← Back to Conditions',
        'item_link' => 'Condition Link',
        'item_link_description' => 'A link to a condition',
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
        'rest_base' => 'condition',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'query_var' => 'condition',
        'rewrite' => array('slug' => 'condition'),
        'capabilities' => array(
            'manage_terms' => 'manage_categories',
            'edit_terms' => 'manage_categories',
            'delete_terms' => 'manage_categories',
            'assign_terms' => 'edit_posts',
        ),
    );

    register_taxonomy('condition', array('provider', 'service'), $args);

    // Add default terms
    $default_conditions = array(
        'depression' => 'Depression',
        'anxiety' => 'Anxiety',
        'ptsd' => 'PTSD',
        'adhd' => 'ADHD',
    );

    foreach ($default_conditions as $slug => $name) {
        if (!term_exists($slug, 'condition')) {
            wp_insert_term($name, 'condition', array('slug' => $slug));
        }
    }
}

add_action('init', 'register_condition_taxonomy');
