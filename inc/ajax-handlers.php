<?php
/**
 * AJAX handlers for the provider archive
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load providers via AJAX
 */
function pe_mp_load_providers() {
    check_ajax_referer('pe_mp_nonce', 'nonce');

    $args = array(
        'post_type' => 'provider',
        'posts_per_page' => 12,
        'paged' => isset($_POST['paged']) ? intval($_POST['paged']) : 1,
    );

    // Handle search query
    if (isset($_POST['s']) && !empty($_POST['s'])) {
        $args['s'] = sanitize_text_field($_POST['s']);
    }

    // Handle provider type filter
    if (isset($_POST['provider-type']) && !empty($_POST['provider-type'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'provider-type',
            'field' => 'slug',
            'terms' => sanitize_text_field($_POST['provider-type'])
        );
    }

    // Handle sorting
    if (isset($_POST['orderby'])) {
        switch ($_POST['orderby']) {
            case 'rating':
                $args['meta_key'] = 'rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'name':
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                break;
        }
    }

    $query = new WP_Query($args);
    $html = '';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ob_start();
            get_template_part('template-parts/provider-card');
            $html .= ob_get_clean();
        }
    }

    wp_reset_postdata();

    // Get the total number of posts found
    $total_posts = $query->found_posts;

    wp_send_json_success(array(
        'html' => $html,
        'count' => $total_posts,
        'debug' => array(
            'args' => $args,
            'found_posts' => $query->found_posts,
            'post_count' => $query->post_count,
            'max_num_pages' => $query->max_num_pages
        )
    ));
}
add_action('wp_ajax_load_providers', 'pe_mp_load_providers');
add_action('wp_ajax_nopriv_load_providers', 'pe_mp_load_providers');

/**
 * Enqueue scripts and localize data
 */
function pe_mp_enqueue_archive_scripts() {
    if (is_post_type_archive('provider')) {
        wp_enqueue_script(
            'pe-mp-provider-archive',
            get_template_directory_uri() . '/dist/js/components/provider-archive.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script('pe-mp-provider-archive', 'pe_mp_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pe_mp_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'pe_mp_enqueue_archive_scripts'); 