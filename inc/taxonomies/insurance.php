<?php
/**
 * Insurance Taxonomy
 * 
 * Insurance plans accepted by providers
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function register_insurance_taxonomy() {
    $labels = array(
        'name' => 'Insurance Plans',
        'singular_name' => 'Insurance Plan',
        'menu_name' => 'Insurance Plans',
        'all_items' => 'All Insurance Plans',
        'edit_item' => 'Edit Insurance Plan',
        'view_item' => 'View Insurance Plan',
        'update_item' => 'Update Insurance Plan',
        'add_new_item' => 'Add New Insurance Plan',
        'new_item_name' => 'New Insurance Plan Name',
        'parent_item' => 'Parent Insurance Plan',
        'parent_item_colon' => 'Parent Insurance Plan:',
        'search_items' => 'Search Insurance Plans',
        'popular_items' => 'Popular Insurance Plans',
        'separate_items_with_commas' => 'Separate insurance plans with commas',
        'add_or_remove_items' => 'Add or remove insurance plans',
        'choose_from_most_used' => 'Choose from the most used insurance plans',
        'not_found' => 'No insurance plans found',
        'no_terms' => 'No insurance plans',
        'filter_by_item' => 'Filter by insurance plan',
        'items_list_navigation' => 'Insurance plans list navigation',
        'items_list' => 'Insurance plans list',
        'back_to_items' => '← Back to Insurance Plans',
        'item_link' => 'Insurance Plan Link',
        'item_link_description' => 'A link to an insurance plan',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'show_tagcloud' => false,
        'show_in_rest' => true,
        'rest_base' => 'insurance',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'query_var' => 'insurance',
        'rewrite' => array('slug' => 'insurance'),
        'capabilities' => array(
            'manage_terms' => 'manage_categories',
            'edit_terms' => 'manage_categories',
            'delete_terms' => 'manage_categories',
            'assign_terms' => 'edit_posts',
        ),
    );

    register_taxonomy('insurance', array('provider', 'service'), $args);

    // Add default terms
    $default_insurance = array(
        'medicaid' => 'Medicaid',
        'medicare' => 'Medicare',
        'no_insurance' => 'No Insurance Accepted',
    );

    foreach ($default_insurance as $slug => $name) {
        if (!term_exists($slug, 'insurance')) {
            wp_insert_term($name, 'insurance', array('slug' => $slug));
        }
    }
}

add_action('init', 'register_insurance_taxonomy');
