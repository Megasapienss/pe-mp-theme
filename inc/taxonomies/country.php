<?php
/**
 * Country Taxonomy
 * 
 * Countries served by providers
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function register_country_taxonomy() {
    $labels = array(
        'name' => 'Countries',
        'singular_name' => 'Country',
        'menu_name' => 'Countries',
        'all_items' => 'All Countries',
        'edit_item' => 'Edit Country',
        'view_item' => 'View Country',
        'update_item' => 'Update Country',
        'add_new_item' => 'Add New Country',
        'new_item_name' => 'New Country Name',
        'parent_item' => 'Parent Country',
        'parent_item_colon' => 'Parent Country:',
        'search_items' => 'Search Countries',
        'popular_items' => 'Popular Countries',
        'separate_items_with_commas' => 'Separate countries with commas',
        'add_or_remove_items' => 'Add or remove countries',
        'choose_from_most_used' => 'Choose from the most used countries',
        'not_found' => 'No countries found',
        'no_terms' => 'No countries',
        'filter_by_item' => 'Filter by country',
        'items_list_navigation' => 'Countries list navigation',
        'items_list' => 'Countries list',
        'back_to_items' => '← Back to Countries',
        'item_link' => 'Country Link',
        'item_link_description' => 'A link to a country',
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
        'rest_base' => 'country',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
        'query_var' => 'country',
        'rewrite' => array('slug' => 'country'),
        'capabilities' => array(
            'manage_terms' => 'manage_categories',
            'edit_terms' => 'manage_categories',
            'delete_terms' => 'manage_categories',
            'assign_terms' => 'edit_posts',
        ),
    );

    register_taxonomy('country', array('provider', 'service', 'product'), $args);

    // Add default terms
    $default_countries = array(
        'united_states' => 'United States',
        'canada' => 'Canada',
        'united_kingdom' => 'United Kingdom',
        'australia' => 'Australia',
        'germany' => 'Germany',
        'france' => 'France',
        'spain' => 'Spain',
        'italy' => 'Italy',
        'netherlands' => 'Netherlands',
        'sweden' => 'Sweden',
        'norway' => 'Norway',
        'denmark' => 'Denmark',
        'finland' => 'Finland',
        'switzerland' => 'Switzerland',
        'austria' => 'Austria',
        'belgium' => 'Belgium',
        'ireland' => 'Ireland',
        'new_zealand' => 'New Zealand',
        'japan' => 'Japan',
        'south_korea' => 'South Korea',
        'singapore' => 'Singapore',
        'hong_kong' => 'Hong Kong',
        'taiwan' => 'Taiwan',
        'israel' => 'Israel',
        'united_arab_emirates' => 'United Arab Emirates',
        'saudi_arabia' => 'Saudi Arabia',
        'brazil' => 'Brazil',
        'mexico' => 'Mexico',
        'argentina' => 'Argentina',
        'chile' => 'Chile',
        'colombia' => 'Colombia',
        'peru' => 'Peru',
        'india' => 'India',
        'china' => 'China',
        'russia' => 'Russia',
        'south_africa' => 'South Africa',
        'egypt' => 'Egypt',
        'nigeria' => 'Nigeria',
        'kenya' => 'Kenya',
        'morocco' => 'Morocco',
        'turkey' => 'Turkey',
        'poland' => 'Poland',
        'czech_republic' => 'Czech Republic',
        'hungary' => 'Hungary',
        'romania' => 'Romania',
        'bulgaria' => 'Bulgaria',
        'croatia' => 'Croatia',
        'serbia' => 'Serbia',
        'slovenia' => 'Slovenia',
        'slovakia' => 'Slovakia',
        'lithuania' => 'Lithuania',
        'latvia' => 'Latvia',
        'estonia' => 'Estonia',
        'iceland' => 'Iceland',
        'luxembourg' => 'Luxembourg',
        'malta' => 'Malta',
        'cyprus' => 'Cyprus',
        'greece' => 'Greece',
        'portugal' => 'Portugal',
        'global' => 'Global',
    );

    foreach ($default_countries as $slug => $name) {
        if (!term_exists($slug, 'country')) {
            wp_insert_term($name, 'country', array('slug' => $slug));
        }
    }
}

add_action('init', 'register_country_taxonomy');
