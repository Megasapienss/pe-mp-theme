<?php
/**
 * Verification Custom Post Type
 * Used for third-party verifications and certifications
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register Custom Post Type: Verifications
function pe_mp_register_verifications_post_type() {
    $labels = array(
        'name'                  => _x('Verifications', 'Post type general name', 'pe-mp-theme'),
        'singular_name'         => _x('Verification', 'Post type singular name', 'pe-mp-theme'),
        'menu_name'            => _x('Verifications', 'Admin Menu text', 'pe-mp-theme'),
        'name_admin_bar'       => _x('Verification', 'Add New on Toolbar', 'pe-mp-theme'),
        'add_new'              => __('Add New', 'pe-mp-theme'),
        'add_new_item'         => __('Add New Verification', 'pe-mp-theme'),
        'new_item'             => __('New Verification', 'pe-mp-theme'),
        'edit_item'            => __('Edit Verification', 'pe-mp-theme'),
        'view_item'            => __('View Verification', 'pe-mp-theme'),
        'all_items'            => __('All Verifications', 'pe-mp-theme'),
        'search_items'         => __('Search Verifications', 'pe-mp-theme'),
        'not_found'            => __('No verifications found.', 'pe-mp-theme'),
        'not_found_in_trash'   => __('No verifications found in Trash.', 'pe-mp-theme'),
        'featured_image'       => __('Verification Badge', 'pe-mp-theme'),
        'set_featured_image'   => __('Set verification badge', 'pe-mp-theme'),
        'remove_featured_image'=> __('Remove verification badge', 'pe-mp-theme'),
        'use_featured_image'   => __('Use as verification badge', 'pe-mp-theme'),
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
        'menu_position'      => 26,
        'menu_icon'          => 'dashicons-shield',
        'supports'           => array(
            'title',           // Verification name
            'custom-fields',   // For ACF
        ),
        'show_in_rest'       => false, // Disable Gutenberg editor
        'taxonomies'         => array('verification-category', 'verification-tag'),
    );

    register_post_type('verification', $args);
}
add_action('init', 'pe_mp_register_verifications_post_type');
