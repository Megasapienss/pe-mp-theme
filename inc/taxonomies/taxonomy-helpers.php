<?php
/**
 * Taxonomy Helpers
 * Helper functions for managing and accessing reusable taxonomies
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get taxonomy terms as choices array for ACF fields
 */
function get_taxonomy_choices($taxonomy_name, $include_empty = false) {
    $terms = get_terms(array(
        'taxonomy' => $taxonomy_name,
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
    ));

    $choices = array();
    
    if ($include_empty) {
        $choices[''] = 'Select...';
    }

    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) {
            $choices[$term->slug] = $term->name;
        }
    }

    return $choices;
}

/**
 * Get taxonomy terms as flat array
 */
function get_taxonomy_terms($taxonomy_name) {
    $terms = get_terms(array(
        'taxonomy' => $taxonomy_name,
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
    ));

    if (is_wp_error($terms)) {
        return array();
    }

    return $terms;
}

/**
 * Get taxonomy term by slug
 */
function get_taxonomy_term_by_slug($taxonomy_name, $slug) {
    return get_term_by('slug', $slug, $taxonomy_name);
}

/**
 * Get taxonomy term name by slug
 */
function get_taxonomy_term_name($taxonomy_name, $slug) {
    $term = get_term_by('slug', $slug, $taxonomy_name);
    return $term ? $term->name : $slug;
}

/**
 * Get all provider type choices
 */
function get_provider_type_choices($include_empty = false) {
    return get_taxonomy_choices('provider-type', $include_empty);
}

/**
 * Get all card tier choices
 */
function get_card_tier_choices($include_empty = false) {
    return get_taxonomy_choices('card-tier', $include_empty);
}

/**
 * Get all facility type choices
 */
function get_facility_type_choices($include_empty = false) {
    return get_taxonomy_choices('facility-type', $include_empty);
}

/**
 * Get all service delivery method choices
 */
function get_service_delivery_method_choices($include_empty = false) {
    return get_taxonomy_choices('service-delivery-method', $include_empty);
}





/**
 * Add a new term to a taxonomy
 */
function add_taxonomy_term($taxonomy_name, $name, $slug = '', $description = '') {
    $args = array();
    
    if (!empty($slug)) {
        $args['slug'] = $slug;
    }
    
    if (!empty($description)) {
        $args['description'] = $description;
    }

    $result = wp_insert_term($name, $taxonomy_name, $args);
    
    if (is_wp_error($result)) {
        return false;
    }
    
    return $result['term_id'];
}

/**
 * Update taxonomy choices for ACF fields
 */
function update_acf_field_choices($field_key, $taxonomy_name) {
    if (function_exists('acf_update_field')) {
        $field = get_field_object($field_key);
        if ($field) {
            $field['choices'] = get_taxonomy_choices($taxonomy_name);
            acf_update_field($field);
        }
    }
}

/**
 * Get posts by taxonomy term
 */
function get_posts_by_taxonomy_term($post_type, $taxonomy_name, $term_slug, $args = array()) {
    $default_args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy_name,
                'field' => 'slug',
                'terms' => $term_slug,
            ),
        ),
    );

    $query_args = wp_parse_args($args, $default_args);
    
    return get_posts($query_args);
}

/**
 * Get taxonomy terms for a specific post
 */
function get_post_taxonomy_terms($post_id, $taxonomy_name) {
    return wp_get_post_terms($post_id, $taxonomy_name, array('fields' => 'all'));
}

/**
 * Set taxonomy terms for a specific post
 */
function set_post_taxonomy_terms($post_id, $taxonomy_name, $terms) {
    return wp_set_post_terms($post_id, $terms, $taxonomy_name);
}

/**
 * Check if a post has a specific taxonomy term
 */
function post_has_taxonomy_term($post_id, $taxonomy_name, $term_slug) {
    $terms = wp_get_post_terms($post_id, $taxonomy_name, array('fields' => 'slugs'));
    return in_array($term_slug, $terms);
}

/**
 * Get taxonomy term count
 */
function get_taxonomy_term_count($taxonomy_name, $term_slug = '') {
    if (empty($term_slug)) {
        $terms = get_terms(array(
            'taxonomy' => $taxonomy_name,
            'hide_empty' => false,
        ));
        return count($terms);
    } else {
        $term = get_term_by('slug', $term_slug, $taxonomy_name);
        return $term ? $term->count : 0;
    }
}

/**
 * Get taxonomy hierarchy as nested array
 */
function get_taxonomy_hierarchy($taxonomy_name) {
    $terms = get_terms(array(
        'taxonomy' => $taxonomy_name,
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
    ));

    if (is_wp_error($terms)) {
        return array();
    }

    $hierarchy = array();
    $children = array();

    // First pass: collect all terms and their children
    foreach ($terms as $term) {
        if ($term->parent == 0) {
            $hierarchy[$term->term_id] = $term;
        } else {
            if (!isset($children[$term->parent])) {
                $children[$term->parent] = array();
            }
            $children[$term->parent][] = $term;
        }
    }

    // Second pass: add children to their parents
    foreach ($hierarchy as $term_id => $term) {
        if (isset($children[$term_id])) {
            $term->children = $children[$term_id];
        }
    }

    return $hierarchy;
}

/**
 * Flatten taxonomy hierarchy to simple array
 */
function flatten_taxonomy_hierarchy($hierarchy, $level = 0) {
    $flat = array();
    
    foreach ($hierarchy as $term) {
        $prefix = str_repeat('— ', $level);
        $flat[$term->slug] = $prefix . $term->name;
        
        if (isset($term->children)) {
            $children = flatten_taxonomy_hierarchy($term->children, $level + 1);
            $flat = array_merge($flat, $children);
        }
    }
    
    return $flat;
}

/**
 * Get taxonomy choices with hierarchy
 */
function get_taxonomy_choices_hierarchical($taxonomy_name, $include_empty = false) {
    $hierarchy = get_taxonomy_hierarchy($taxonomy_name);
    $choices = flatten_taxonomy_hierarchy($hierarchy);
    
    if ($include_empty) {
        $choices = array('' => 'Select...') + $choices;
    }
    
    return $choices;
}

// ========================================
// COST RANGE TAXONOMY HELPERS
// ========================================

/**
 * Get cost range choices for ACF fields
 */
function get_cost_range_choices($include_empty = false) {
    return get_taxonomy_choices('cost-range', $include_empty);
}

/**
 * Get cost range terms
 */
function get_cost_range_terms() {
    return get_taxonomy_terms('cost-range');
}

/**
 * Get cost range term by slug
 */
function get_cost_range_term_by_slug($slug) {
    return get_taxonomy_term_by_slug('cost-range', $slug);
}

/**
 * Get cost range term name by slug
 */
function get_cost_range_term_name($slug) {
    return get_taxonomy_term_name('cost-range', $slug);
}

// ========================================
// LANGUAGE TAXONOMY HELPERS
// ========================================

/**
 * Get language choices for ACF fields
 */
function get_language_choices($include_empty = false) {
    return get_taxonomy_choices('language', $include_empty);
}

/**
 * Get language terms
 */
function get_language_terms() {
    return get_taxonomy_terms('language');
}

/**
 * Get language term by slug
 */
function get_language_term_by_slug($slug) {
    return get_taxonomy_term_by_slug('language', $slug);
}

/**
 * Get language term name by slug
 */
function get_language_term_name($slug) {
    return get_taxonomy_term_name('language', $slug);
}



// ========================================
// CONDITION TAXONOMY HELPERS
// ========================================

/**
 * Get condition choices for ACF fields
 */
function get_condition_choices($include_empty = false) {
    return get_taxonomy_choices('condition', $include_empty);
}

/**
 * Get condition terms
 */
function get_condition_terms() {
    return get_taxonomy_terms('condition');
}

/**
 * Get condition term by slug
 */
function get_condition_term_by_slug($slug) {
    return get_taxonomy_term_by_slug('condition', $slug);
}

/**
 * Get condition term name by slug
 */
function get_condition_term_name($slug) {
    return get_taxonomy_term_name('condition', $slug);
}

// ========================================
// TAG TAXONOMY HELPERS
// ========================================

/**
 * Get tag choices for ACF fields
 */
function get_tag_choices($include_empty = false) {
    return get_taxonomy_choices('tag', $include_empty);
}

/**
 * Get tag terms
 */
function get_tag_terms() {
    return get_taxonomy_terms('tag');
}

/**
 * Get tag term by slug
 */
function get_tag_term_by_slug($slug) {
    return get_taxonomy_term_by_slug('tag', $slug);
}

/**
 * Get tag term name by slug
 */
function get_tag_term_name($slug) {
    return get_taxonomy_term_name('tag', $slug);
}

// ========================================
// INSURANCE TAXONOMY HELPERS
// ========================================

/**
 * Get insurance choices for ACF fields
 */
function get_insurance_choices($include_empty = false) {
    return get_taxonomy_choices('insurance', $include_empty);
}

/**
 * Get insurance terms
 */
function get_insurance_terms() {
    return get_taxonomy_terms('insurance');
}

/**
 * Get insurance term by slug
 */
function get_insurance_term_by_slug($slug) {
    return get_taxonomy_term_by_slug('insurance', $slug);
}

/**
 * Get insurance term name by slug
 */
function get_insurance_term_name($slug) {
    return get_taxonomy_term_name('insurance', $slug);
}

// ========================================
// COUNTRY TAXONOMY HELPERS
// ========================================

/**
 * Get country choices for ACF fields
 */
function get_country_choices($include_empty = false) {
    return get_taxonomy_choices('country', $include_empty);
}

/**
 * Get country terms
 */
function get_country_terms() {
    return get_taxonomy_terms('country');
}

/**
 * Get country term by slug
 */
function get_country_term_by_slug($slug) {
    return get_taxonomy_term_by_slug('country', $slug);
}

/**
 * Get country term name by slug
 */
function get_country_term_name($slug) {
    return get_taxonomy_term_name('country', $slug);
}
