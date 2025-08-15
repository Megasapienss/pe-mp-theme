<?php

/**
 * Template part for displaying the hero mosaic section
 *
 * @package PE_MP_Theme
 */

// Get exclude_posts parameter
$exclude_posts = isset($args['exclude_posts']) ? $args['exclude_posts'] : array();

// Query featured posts for the hero section - prioritize "superfeature" tag
$hero_posts = new WP_Query(array(
    'posts_per_page' => 3,
    'post__not_in' => array_merge(array(get_the_ID()), $exclude_posts),
    'tag' => 'superfeature',
    'orderby' => 'date',
    'order' => 'DESC'
));

// If we don't have enough superfeature posts, fill with regular posts
if ($hero_posts->post_count < 3) {
    $remaining_slots = 3 - $hero_posts->post_count;
    $superfeature_post_ids = wp_list_pluck($hero_posts->posts, 'ID');
    
    $regular_posts = new WP_Query(array(
        'posts_per_page' => $remaining_slots,
        'post__not_in' => array_merge(array(get_the_ID()), $exclude_posts, $superfeature_post_ids),
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    // Merge the posts arrays
    $hero_posts->posts = array_merge($hero_posts->posts, $regular_posts->posts);
    $hero_posts->post_count = count($hero_posts->posts);
}

// Don't output anything if there are no posts
if (!$hero_posts->have_posts()) {
    return;
}

$posts_array = $hero_posts->posts;

// Update global displayed post IDs
global $displayed_post_ids;
if (!isset($displayed_post_ids) || !is_array($displayed_post_ids)) {
    $displayed_post_ids = array();
}
$displayed_post_ids = array_merge($displayed_post_ids, wp_list_pluck($posts_array, 'ID'));
?>

<section class="section-v2 container">
    <div class="mosaic mosaic--2-1 gap-4">
        <div class="mosaic__column">
            <?php if (!empty($posts_array)) : ?>
                <?php 
                $main_post = $posts_array[0];
                get_template_part('template-parts/cards/post', 'shifted', [
                    'post' => $main_post,
                    'size' => 'large'
                ]); 
                ?>
            <?php endif; ?>
        </div>
        <div class="mosaic__column">
            <?php if (isset($posts_array[1])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[1],
                    'size' => 'small',
                    'show_excerpt' => false,
                    'show_author' => false,
                    'image_align' => 'right'
                ]); 
                ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($posts_array[2])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[2],
                    'size' => 'small',
                    'show_excerpt' => false,
                    'show_author' => false,
                    'image_align' => 'right'
                ]); 
                ?>
            </div>
            <?php endif; ?>
            
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/banners/test', '', [
                    'test_id' => 'anxiety'
                ]); 
                ?>
            </div>
        </div>
    </div>
</section> 