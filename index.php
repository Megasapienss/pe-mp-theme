<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * 
 * @package PE_MP_Theme
 */

get_header();
?>

<section class="archive-title container">
    <div class="breadcrumbs breadcrumbs--dark archive-title__breadcrumbs">
        <a href="<?= home_url(); ?>" class="breadcrumbs__link">Home</a>
        <span class="breadcrumbs__separator">/</span>
        <span class="breadcrumbs__current">Blog</span>
    </div>
    <h1 class="archive-title__name title-lg">Blog</h1>
    <p class="archive-title__description heading-h2">Latest articles and updates</p>
</section>

<section class="archive-grid grid grid--2 container">
    <?php if (have_posts()): ?>
        <?php
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/cards/post', 'curved', ['post' => get_post()]);
        }
        ?>

    <?php else: ?>
        <div class="no-results">
            <p><?php _e('No posts found.', 'pe-mp-theme'); ?></p>
        </div>
    <?php endif; ?>
</section>

<?php
get_footer();
