<?php
/**
 * Review Custom Post Type
 * Used for provider reviews and testimonials
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register Custom Post Type: Reviews
function pe_mp_register_reviews_post_type() {
    $labels = array(
        'name'                  => _x('Reviews', 'Post type general name', 'pe-mp-theme'),
        'singular_name'         => _x('Review', 'Post type singular name', 'pe-mp-theme'),
        'menu_name'            => _x('Reviews', 'Admin Menu text', 'pe-mp-theme'),
        'name_admin_bar'       => _x('Review', 'Add New on Toolbar', 'pe-mp-theme'),
        'add_new'              => __('Add New', 'pe-mp-theme'),
        'add_new_item'         => __('Add New Review', 'pe-mp-theme'),
        'new_item'             => __('New Review', 'pe-mp-theme'),
        'edit_item'            => __('Edit Review', 'pe-mp-theme'),
        'view_item'            => __('View Review', 'pe-mp-theme'),
        'all_items'            => __('All Reviews', 'pe-mp-theme'),
        'search_items'         => __('Search Reviews', 'pe-mp-theme'),
        'not_found'            => __('No reviews found.', 'pe-mp-theme'),
        'not_found_in_trash'   => __('No reviews found in Trash.', 'pe-mp-theme'),
        'featured_image'       => __('Reviewer Image', 'pe-mp-theme'),
        'set_featured_image'   => __('Set reviewer image', 'pe-mp-theme'),
        'remove_featured_image'=> __('Remove reviewer image', 'pe-mp-theme'),
        'use_featured_image'   => __('Use as reviewer image', 'pe-mp-theme'),
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
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-star-filled',
        'supports'           => array(
            'title',           // Reviewer name
            'custom-fields',   // For ACF
        ),
        'show_in_rest'       => false, // Disable Gutenberg editor
        'taxonomies'         => array('review-category', 'review-tag'),
    );

    register_post_type('review', $args);
}
add_action('init', 'pe_mp_register_reviews_post_type');
