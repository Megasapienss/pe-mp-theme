<?php
/**
 * Product Custom Post Type
 * Used for physical goods and products
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register Custom Post Type: Products
function pe_mp_register_products_post_type() {
    $labels = array(
        'name'                  => _x('Products', 'Post type general name', 'pe-mp-theme'),
        'singular_name'         => _x('Product', 'Post type singular name', 'pe-mp-theme'),
        'menu_name'            => _x('Products', 'Admin Menu text', 'pe-mp-theme'),
        'name_admin_bar'       => _x('Product', 'Add New on Toolbar', 'pe-mp-theme'),
        'add_new'              => __('Add New', 'pe-mp-theme'),
        'add_new_item'         => __('Add New Product', 'pe-mp-theme'),
        'new_item'             => __('New Product', 'pe-mp-theme'),
        'edit_item'            => __('Edit Product', 'pe-mp-theme'),
        'view_item'            => __('View Product', 'pe-mp-theme'),
        'all_items'            => __('All Products', 'pe-mp-theme'),
        'search_items'         => __('Search Products', 'pe-mp-theme'),
        'not_found'            => __('No products found.', 'pe-mp-theme'),
        'not_found_in_trash'   => __('No products found in Trash.', 'pe-mp-theme'),
        'featured_image'       => __('Product Image', 'pe-mp-theme'),
        'set_featured_image'   => __('Set product image', 'pe-mp-theme'),
        'remove_featured_image'=> __('Remove product image', 'pe-mp-theme'),
        'use_featured_image'   => __('Use as product image', 'pe-mp-theme'),
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
        'menu_position'      => 28,
        'menu_icon'          => 'dashicons-cart',
        'supports'           => array(
            'title',           // Product name
            'custom-fields',   // For ACF
        ),
        'show_in_rest'       => false, // Disable Gutenberg editor
        'taxonomies'         => array('product-category', 'product-tag'),
    );

    register_post_type('product', $args);
}
add_action('init', 'pe_mp_register_products_post_type');
