<?php

/**
 * Template part for displaying the top articles section
 * 
 * @package PE_MP_Theme
 */

// Get the current post's categories
$categories = get_the_category();
$category_ids = array();
foreach ($categories as $category) {
    $category_ids[] = $category->term_id;
}

// Query related posts
$related_posts = new WP_Query(array(
    'category__in' => $category_ids,
    'post__not_in' => array(get_the_ID()),
    'posts_per_page' => 3,
    'orderby' => 'rand'
));

// Don't output anything if there are no posts
if (!$related_posts->have_posts()) {
    return;
}
?>

<section class="section">
    <div class="section__title">
        <h2 class="section__title-text">Top Articles</h2>
        <a href="<?= get_permalink(get_option('page_for_posts')); ?>#posts" class="section__title-link arrow-btn arrow-btn--muted">See all</a>
    </div>
    <div class="cards grid grid--3">
        <?php
        // Display related posts
        foreach ($related_posts->posts as $post) :
            get_template_part('template-parts/cards/post', 'simple', ['post' => $post]);
        endforeach;
        ?>
    </div>
</section> 