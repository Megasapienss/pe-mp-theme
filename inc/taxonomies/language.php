<?php
/**
 * Language Taxonomy
 * 
 * Languages offered by providers
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function register_language_taxonomy() {
    $labels = array(
        'name' => 'Languages',
        'singular_name' => 'Language',
        'menu_name' => 'Languages',
        'all_items' => 'All Languages',
        'edit_item' => 'Edit Language',
        'view_item' => 'View Language',
        'update_item' => 'Update Language',
        'add_new_item' => 'Add New Language',
        'new_item_name' => 'New Language Name',
        'parent_item' => 'Parent Language',
        'parent_item_colon' => 'Parent Language:',
        'search_items' => 'Search Languages',
        'popular_items' => 'Popular Languages',
        'separate_items_with_commas' => 'Separate languages with commas',
        'add_or_remove_items' => 'Add or remove languages',
        'choose_from_most_used' => 'Choose from the most used languages',
        'not_found' => 'No languages found',
        'no_terms' => 'No languages',
        'filter_by_item' => 'Filter by language',
        'items_list_navigation' => 'Languages list navigation',
        'items_list' => 'Languages list',
        'back_to_items' => '← Back to Languages',
        'item_link' => 'Language Link',
        'item_link_description' => 'A link to a language',
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
        'rest_base' => 'language',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'query_var' => 'language',
        'rewrite' => array('slug' => 'language'),
        'capabilities' => array(
            'manage_terms' => 'manage_categories',
            'edit_terms' => 'manage_categories',
            'delete_terms' => 'manage_categories',
            'assign_terms' => 'edit_posts',
        ),
    );

    register_taxonomy('language', array('provider', 'service'), $args);

    // Add default terms
    $default_languages = array(
        'english' => 'English',
        'russian' => 'Russian',
    );

    foreach ($default_languages as $slug => $name) {
        if (!term_exists($slug, 'language')) {
            wp_insert_term($name, 'language', array('slug' => $slug));
        }
    }
}

add_action('init', 'register_language_taxonomy');
