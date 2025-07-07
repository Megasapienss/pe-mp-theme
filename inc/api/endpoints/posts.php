<?php

/**
 * PE Media Portal Theme - Posts REST API Endpoint
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register posts API endpoint
 */
function pe_mp_register_posts_api_endpoint()
{
    register_rest_route('pe-mp/v1', '/posts', array(
        'methods' => 'GET',
        'callback' => 'pe_mp_get_all_posts',
        'permission_callback' => '__return_true',
        'args' => array(
            'post_type' => array(
                'default' => 'post',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'category' => array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            )
        )
    ));
}

/**
 * Callback function for the posts API endpoint
 */
function pe_mp_get_all_posts($request)
{
    // Get parameters
    $post_type = $request->get_param('post_type');
    $category = $request->get_param('category');

    // Build query arguments - get ALL posts
    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => -1, // -1 means get all posts
        'orderby' => 'date',
        'order' => 'DESC'
    );

    // Add category filter if specified
    if (!empty($category)) {
        $args['category_name'] = $category;
    }

    // Get posts
    $query = new WP_Query($args);
    $posts = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get the main category (first category)
            $categories = get_the_category();
            $main_category = !empty($categories) ? $categories[0]->name : '';

            $posts[] = array(
                'title' => get_the_title(),
                'category' => $main_category,
                'link' => get_permalink(),
            );
        }
        wp_reset_postdata();
    }

    // Prepare response
    $response = array(
        'success' => true,
        'data' => $posts,
        'total' => count($posts)
    );

    return new WP_REST_Response($response, 200);
}
