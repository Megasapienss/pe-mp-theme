<?php

/**
 * Template part for displaying category or tag-based mosaic sections
 *
 * @param string $args['title'] Section title
 * @param string $args['category'] Category slug or name (optional)
 * @param string $args['tag'] Tag slug or name (optional)
 *
 * @package PE_MP_Theme
 */

$title = isset($args['title']) ? $args['title'] : 'Science & Innovation';
$category = isset($args['category']) ? $args['category'] : '';
$tag = isset($args['tag']) ? $args['tag'] : '';

// Query posts by category or tag
$query_args = array(
    'posts_per_page' => 6,
    'post__not_in' => array(get_the_ID()),
    'orderby' => 'date',
    'order' => 'DESC'
);

if ($category) {
    $query_args['category_name'] = $category;
} elseif ($tag) {
    $query_args['tag'] = $tag;
}

$posts_query = new WP_Query($query_args);

// Don't output anything if there are no posts
if (!$posts_query->have_posts()) {
    return;
}

$posts_array = $posts_query->posts;
?>

<section class="section-v2 container">
    <div class="section-v2__title">
        <h2><?= esc_html($title); ?></h2>
        <?php 
        if ($category) {
            $term_obj = get_category_by_slug($category);
            if ($term_obj) : 
        ?>
        <a href="<?= esc_url(get_category_link($term_obj)); ?>" class="btn btn--muted btn--arrow">
            See all
        </a>
        <?php 
            endif;
        } elseif ($tag) {
            $term_obj = get_term_by('slug', $tag, 'post_tag');
            if ($term_obj) : 
        ?>
        <a href="<?= esc_url(get_tag_link($term_obj)); ?>" class="btn btn--muted btn--arrow">
            See all
        </a>
        <?php 
            endif;
        }
        ?>
    </div>
    <div class="section-v2__content">
        <div class="mosaic mosaic--2-1-1">
            <?php if (!empty($posts_array)) : ?>
            <div class="mosaic__item">
                <?php 
                $main_post = $posts_array[0];
                get_template_part('template-parts/cards/post', 'shifted', [
                    'post' => $main_post,
                    'size' => 'small'
                ]); 
                ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($posts_array[1])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[1]
                ]); 
                ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($posts_array[2])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[2]
                ]); 
                ?>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if (count($posts_array) > 3) : ?>
        <div class="mosaic mosaic--1-1-1">
            <?php for ($i = 3; $i < min(6, count($posts_array)); $i++) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[$i],
                    'size' => 'small',
                    'show_excerpt' => false,
                    'show_author' => false
                ]); 
                ?>
            </div>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</section> 