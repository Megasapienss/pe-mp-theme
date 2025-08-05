<?php

/**
 * Template part for displaying complex mosaic sections
 *
 * @param string $args['title'] Section title
 * @param string $args['category'] Category slug or name
 *
 * @package PE_MP_Theme
 */

$title = isset($args['title']) ? $args['title'] : 'Editor\'s Picks';
$category = isset($args['category']) ? $args['category'] : '';

// Query posts by category
$query_args = array(
    'posts_per_page' => 8,
    'post__not_in' => array(get_the_ID()),
    'orderby' => 'date',
    'order' => 'DESC'
);

if ($category) {
    $query_args['category_name'] = $category;
}

$category_posts = new WP_Query($query_args);

// Don't output anything if there are no posts
if (!$category_posts->have_posts()) {
    return;
}

$posts_array = $category_posts->posts;
?>

<section class="section-v2 container">
    <div class="section-v2__title">
        <h2><?= esc_html($title); ?></h2>
    </div>
    <div class="section-v2__content">
        <div class="mosaic mosaic--1-2-1">
            <?php if (isset($posts_array[0])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[0]
                ]); 
                ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($posts_array[1])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[1],
                    'size' => 'large'
                ]); 
                ?>
            </div>
            <?php endif; ?>
            
            <div class="mosaic__column">
                <?php if (isset($posts_array[2])) : ?>
                <div class="mosaic__item">
                    <?php 
                    get_template_part('template-parts/cards/post-v2', '', [
                        'post' => $posts_array[2],
                        'show_image' => false
                    ]); 
                    ?>
                </div>
                <?php endif; ?>
                
                <?php if (isset($posts_array[3])) : ?>
                <div class="mosaic__item">
                    <?php 
                    get_template_part('template-parts/cards/post-v2', '', [
                        'post' => $posts_array[3],
                        'show_image' => false
                    ]); 
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mosaic mosaic--2-1-1">
            <?php if (isset($posts_array[4])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[4],
                    'size' => 'large'
                ]); 
                ?>
            </div>
            <?php endif; ?>
            
            <div class="mosaic__column">
                <?php if (isset($posts_array[5])) : ?>
                <div class="mosaic__item">
                    <?php 
                    get_template_part('template-parts/cards/post-v2', '', [
                        'post' => $posts_array[5],
                        'show_image' => false
                    ]); 
                    ?>
                </div>
                <?php endif; ?>
                
                <?php if (isset($posts_array[6])) : ?>
                <div class="mosaic__item">
                    <?php 
                    get_template_part('template-parts/cards/post-v2', '', [
                        'post' => $posts_array[6],
                        'show_image' => false
                    ]); 
                    ?>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if (isset($posts_array[7])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[7]
                ]); 
                ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section> 