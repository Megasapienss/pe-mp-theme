<?php
/**
 * Card Tier Taxonomy
 * Reusable taxonomy for card tiers across different post types
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register Card Tier Taxonomy
function pe_mp_register_card_tier_taxonomy() {
    $labels = array(
        'name'              => _x('Card Tiers', 'taxonomy general name', 'pe-mp-theme'),
        'singular_name'     => _x('Card Tier', 'taxonomy singular name', 'pe-mp-theme'),
        'search_items'      => __('Search Card Tiers', 'pe-mp-theme'),
        'all_items'         => __('All Card Tiers', 'pe-mp-theme'),
        'parent_item'       => __('Parent Card Tier', 'pe-mp-theme'),
        'parent_item_colon' => __('Parent Card Tier:', 'pe-mp-theme'),
        'edit_item'         => __('Edit Card Tier', 'pe-mp-theme'),
        'update_item'       => __('Update Card Tier', 'pe-mp-theme'),
        'add_new_item'      => __('Add New Card Tier', 'pe-mp-theme'),
        'new_item_name'     => __('New Card Tier Name', 'pe-mp-theme'),
        'menu_name'         => __('Card Tiers', 'pe-mp-theme'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'card-tier'),
        'show_in_rest'      => true,
    );

    register_taxonomy('card-tier', array('provider'), $args);
}
add_action('init', 'pe_mp_register_card_tier_taxonomy');

// Add default terms
function pe_mp_add_default_card_tiers() {
    $default_tiers = array(
        'basic' => 'Basic',
        'verified' => 'Verified',
        'pro' => 'Pro',
    );

    foreach ($default_tiers as $slug => $name) {
        if (!term_exists($slug, 'card-tier')) {
            wp_insert_term($name, 'card-tier', array('slug' => $slug));
        }
    }
}
add_action('init', 'pe_mp_add_default_card_tiers');
