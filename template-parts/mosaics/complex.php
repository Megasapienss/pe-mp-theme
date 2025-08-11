<?php

/**
 * Template part for displaying complex mosaic sections
 *
 * @param string $args['title'] Section title
 * @param string $args['taxonomy'] Taxonomy name (e.g., 'category', 'post_tag', 'custom-taxonomy')
 * @param string $args['term'] Term slug or name
 * @param int $args['count'] Number of posts to display (default: 8)
 * @param string $args['category'] Category slug or name (deprecated, use taxonomy + term instead)
 *
 * @package PE_MP_Theme
 */

$title = isset($args['title']) ? $args['title'] : 'Editor\'s Picks';
$taxonomy = isset($args['taxonomy']) ? $args['taxonomy'] : '';
$term = isset($args['term']) ? $args['term'] : '';
$count = isset($args['count']) ? intval($args['count']) : 8;

// Backward compatibility for existing usage
if (!$taxonomy && !$term && isset($args['category']) && $args['category']) {
    $taxonomy = 'category';
    $term = $args['category'];
}

// Query posts by taxonomy and term
$query_args = array(
    'posts_per_page' => $count,
    'post__not_in' => array(get_the_ID()),
    'orderby' => 'date',
    'order' => 'DESC'
);

if ($taxonomy && $term) {
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => $term
        )
    );
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
        if ($taxonomy && $term) {
            $term_obj = get_term_by('slug', $term, $taxonomy);
            if ($term_obj) : 
        ?>
        <a href="<?= esc_url(get_term_link($term_obj)); ?>" class="btn btn--muted btn--arrow">
            See all
        </a>
        <?php 
            endif;
        }
        ?>
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
        
        <?php if (count($posts_array) > 4) : ?>
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
        <?php endif; ?>
        
        <?php if (count($posts_array) > 8) : ?>
        <div class="mosaic mosaic--1-1-1">
            <?php for ($i = 8; $i < min($count, count($posts_array)); $i++) : ?>
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