<?php
/**
 * AJAX handlers
 */

if (!defined('ABSPATH')) {
    exit;
}

// AJAX handler for provider condition filter
function pe_mp_filter_providers_by_condition() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'pe_mp_filter_nonce')) {
        wp_die('Security check failed');
    }
    
    $condition = isset($_POST['condition']) ? sanitize_text_field($_POST['condition']) : '';
    
    // Build query args
    $args = array(
        'post_type' => 'provider',
        'posts_per_page' => get_option('posts_per_page'),
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'provider-type',
                'field' => 'slug',
                'terms' => 'practitioner',
                'operator' => 'NOT IN'
            )
        )
    );
    
    // Add condition filter if specified
    if (!empty($condition)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'condition',
            'field' => 'slug',
            'terms' => $condition
        );
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/cards/provider', '', ['post' => get_post()]);
        }
    } else {
        echo '<p class="text-center">' . __('No providers found.', 'pe-mp-theme') . '</p>';
    }
    
    $html = ob_get_clean();
    wp_reset_postdata();
    
    wp_send_json_success(array(
        'html' => $html,
        'count' => $query->found_posts
    ));
}

add_action('wp_ajax_filter_providers', 'pe_mp_filter_providers_by_condition');
add_action('wp_ajax_nopriv_filter_providers', 'pe_mp_filter_providers_by_condition');