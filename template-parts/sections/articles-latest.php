<?php

/**
 * Template part for displaying the latest articles section
 *
 * @package PE_MP_Theme
 */

// Query latest posts
$latest_posts = new WP_Query(array(
    'posts_per_page' => 5,
    'post__not_in' => array(get_the_ID()),
    'orderby' => 'date',
    'order' => 'DESC'
));

// Don't output anything if there are no posts
if (!$latest_posts->have_posts()) {
    return;
}
?>

<section class="section articles-section">
    <div class="section__title">
        <h2 class="section__title-text">New articles</h2>
        <a href="<?= get_permalink(get_option('page_for_posts')); ?>#posts" class="section__title-link arrow-btn arrow-btn--muted">View all</a>
    </div>
    <div class="cards grid grid--3">
        <?php
        // Display latest posts
        foreach ($latest_posts->posts as $post) :
            get_template_part('template-parts/cards/post', 'simple', ['post' => $post]);
        endforeach;
        ?>

        <?php
        get_template_part('template-parts/banners/quiz');
        ?>
    </div>
</section>