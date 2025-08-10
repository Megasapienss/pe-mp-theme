<?php
/**
 * Provider Tag Taxonomy
 * 
 * Additional tags for categorization
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function register_provider_tag_taxonomy() {
    $labels = array(
        'name' => 'Tags',
        'singular_name' => 'Tag',
        'menu_name' => 'Tags',
        'all_items' => 'All Tags',
        'edit_item' => 'Edit Tag',
        'view_item' => 'View Tag',
        'update_item' => 'Update Tag',
        'add_new_item' => 'Add New Tag',
        'new_item_name' => 'New Tag Name',
        'parent_item' => 'Parent Tag',
        'parent_item_colon' => 'Parent Tag:',
        'search_items' => 'Search Tags',
        'popular_items' => 'Popular Tags',
        'separate_items_with_commas' => 'Separate tags with commas',
        'add_or_remove_items' => 'Add or remove tags',
        'choose_from_most_used' => 'Choose from the most used tags',
        'not_found' => 'No tags found',
        'no_terms' => 'No tags',
        'filter_by_item' => 'Filter by tag',
        'items_list_navigation' => 'Tags list navigation',
        'items_list' => 'Tags list',
        'back_to_items' => '← Back to Tags',
        'item_link' => 'Tag Link',
        'item_link_description' => 'A link to a tag',
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
        'rest_base' => 'provider-tag',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'query_var' => 'provider-tag',
        'rewrite' => array('slug' => 'provider-tag'),
        'capabilities' => array(
            'manage_terms' => 'manage_categories',
            'edit_terms' => 'manage_categories',
            'delete_terms' => 'manage_categories',
            'assign_terms' => 'edit_posts',
        ),
    );

    register_taxonomy('provider-tag', array('provider', 'service', 'product'), $args);

    // Add default terms
    $default_tags = array(
        '24_7' => '24/7 Availability',
    );

    foreach ($default_tags as $slug => $name) {
        if (!term_exists($slug, 'provider-tag')) {
            wp_insert_term($name, 'provider-tag', array('slug' => $slug));
        }
    }
}

add_action('init', 'register_provider_tag_taxonomy');
