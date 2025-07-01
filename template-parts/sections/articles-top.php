<?php

/**
 * Template part for displaying the top articles section
 * 
 * @package PE_MP_Theme
 */

// Query random posts
$random_posts = new WP_Query(array(
    'post__not_in' => array(get_the_ID()),
    'posts_per_page' => 6,
    'orderby' => 'rand'
));

// Don't output anything if there are no posts
if (!$random_posts->have_posts()) {
    return;
}
?>

<section class="section">
    <div class="section__title">
        <h2 class="section__title-text">What else is worth exploring?</h2>
        <a href="<?= get_permalink(get_option('page_for_posts')); ?>#posts" class="section__title-link arrow-btn arrow-btn--muted">View all</a>
    </div>
    <div class="cards grid grid--3">
        <?php
        // Display random posts
        foreach ($random_posts->posts as $post) :
            get_template_part('template-parts/cards/post', 'simple', ['post' => $post]);
        endforeach;
        ?>
    </div>
</section>