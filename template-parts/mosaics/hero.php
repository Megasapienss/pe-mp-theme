<?php

/**
 * Template part for displaying the hero mosaic section
 *
 * @package PE_MP_Theme
 */

// Query featured posts for the hero section
$hero_posts = new WP_Query(array(
    'posts_per_page' => 3,
    'post__not_in' => array(get_the_ID()),
    'orderby' => 'date',
    'order' => 'DESC'
));

// Don't output anything if there are no posts
if (!$hero_posts->have_posts()) {
    return;
}

$posts_array = $hero_posts->posts;
?>

<section class="section-v2 container">
    <div class="mosaic mosaic--2-1 gap-4">
        <div class="mosaic__column">
            <?php if (!empty($posts_array)) : ?>
                <?php 
                $main_post = $posts_array[0];
                get_template_part('template-parts/cards/post', '', [
                    'post' => $main_post
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