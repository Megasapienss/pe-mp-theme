<?php

/**
 * Image Preloading Module
 * 
 * Handles preloading of post thumbnails and critical images for better performance
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Preload post thumbnails for better performance
 */
function pe_mp_preload_post_thumbnails()
{
    // Only preload on single posts, pages, and provider pages
    if (!is_single() && !is_page() && !is_singular('provider')) {
        return;
    }

    // Get the current post thumbnail
    $thumbnail_id = get_post_thumbnail_id();

    if (!$thumbnail_id) {
        return;
    }

    // Get the large size thumbnail URL
    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');

    if (!$thumbnail_url) {
        return;
    }

    // Check if WebP version exists and browser supports it
    $webp_url = pe_mp_get_webp_url($thumbnail_id);

    if ($webp_url && function_exists('pe_mp_browser_supports_webp') && pe_mp_browser_supports_webp()) {
        // Preload WebP version
        echo '<link rel="preload" as="image" href="' . esc_url($webp_url) . '" type="image/webp">';
    } else {
        // Preload original format
        echo '<link rel="preload" as="image" href="' . esc_url($thumbnail_url) . '">';
    }
}
add_action('wp_head', 'pe_mp_preload_post_thumbnails', 1);

/**
 * Preload thumbnails for archive pages (home, category, etc.)
 */
function pe_mp_preload_archive_thumbnails()
{
    // Only preload on archive pages
    if (!is_home() && !is_archive() && !is_search()) {
        return;
    }

    // Get the first few posts to preload their thumbnails
    $posts = get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => 3, // Preload first 3 post thumbnails
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        )
    ));

    foreach ($posts as $post) {
        $thumbnail_id = get_post_thumbnail_id($post->ID);

        if (!$thumbnail_id) {
            continue;
        }

        // Get the medium size thumbnail URL (appropriate for cards)
        $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'medium');

        if (!$thumbnail_url) {
            continue;
        }

        // Check if WebP version exists and browser supports it
        $webp_url = pe_mp_get_webp_url($thumbnail_id);

        if ($webp_url && function_exists('pe_mp_browser_supports_webp') && pe_mp_browser_supports_webp()) {
            // Preload WebP version
            echo '<link rel="preload" as="image" href="' . esc_url($webp_url) . '" type="image/webp">';
        } else {
            // Preload original format
            echo '<link rel="preload" as="image" href="' . esc_url($thumbnail_url) . '">';
        }
    }
}
add_action('wp_head', 'pe_mp_preload_archive_thumbnails', 1);

/**
 * Preload provider thumbnails on provider archive pages
 */
function pe_mp_preload_provider_thumbnails()
{
    // Only preload on provider archive pages
    if (!is_post_type_archive('provider')) {
        return;
    }

    // Get the first few providers to preload their thumbnails
    $providers = get_posts(array(
        'post_type' => 'provider',
        'posts_per_page' => 3, // Preload first 3 provider thumbnails
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        )
    ));

    foreach ($providers as $provider) {
        $thumbnail_id = get_post_thumbnail_id($provider->ID);

        if (!$thumbnail_id) {
            continue;
        }

        // Get the medium size thumbnail URL (appropriate for provider cards)
        $thumbnail_url = get_the_post_thumbnail_url($provider->ID, 'medium');

        if (!$thumbnail_url) {
            continue;
        }

        // Check if WebP version exists and browser supports it
        $webp_url = pe_mp_get_webp_url($thumbnail_id);

        if ($webp_url && function_exists('pe_mp_browser_supports_webp') && pe_mp_browser_supports_webp()) {
            // Preload WebP version
            echo '<link rel="preload" as="image" href="' . esc_url($webp_url) . '" type="image/webp">';
        } else {
            // Preload original format
            echo '<link rel="preload" as="image" href="' . esc_url($thumbnail_url) . '">';
        }
    }
}
add_action('wp_head', 'pe_mp_preload_provider_thumbnails', 1);

/**
 * Preload critical images for better performance
 * This function preloads images that are likely to be visible above the fold
 */
function pe_mp_preload_critical_images()
{
    // Only run on frontend
    if (is_admin()) {
        return;
    }

    $preload_images = array();

    // Preload hero/banner images for single posts and pages
    if (is_single() || is_page() || is_singular('provider')) {
        $thumbnail_id = get_post_thumbnail_id();
        if ($thumbnail_id) {
            $large_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
            if ($large_url) {
                $webp_url = pe_mp_get_webp_url($thumbnail_id);
                if ($webp_url && function_exists('pe_mp_browser_supports_webp') && pe_mp_browser_supports_webp()) {
                    $preload_images[] = array(
                        'url' => $webp_url,
                        'type' => 'image/webp'
                    );
                } else {
                    $preload_images[] = array(
                        'url' => $large_url,
                        'type' => ''
                    );
                }
            }
        }
    }

    // Preload logo and other critical assets
    $logo_url = get_template_directory_uri() . '/dist/images/brand/logo-v1.svg';
    $preload_images[] = array(
        'url' => $logo_url,
        'type' => 'image/svg+xml'
    );

    // Output preload links
    foreach ($preload_images as $image) {
        if ($image['type']) {
            echo '<link rel="preload" as="image" href="' . esc_url($image['url']) . '" type="' . esc_attr($image['type']) . '">';
        } else {
            echo '<link rel="preload" as="image" href="' . esc_url($image['url']) . '">';
        }
    }
}
add_action('wp_head', 'pe_mp_preload_critical_images', 2);
