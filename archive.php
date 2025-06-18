<?php

/**
 * The template for displaying post archives
 * 
 * @package PE_MP_Theme
 */

get_header();
$term = get_queried_object();
?>

<section class="archive-title container">
    <div class="breadcrumbs breadcrumbs--dark archive-title__breadcrumbs">
        <a href="<?= home_url(); ?>" class="breadcrumbs__link">Home</a>
        <span class="breadcrumbs__separator">/</span>
        <a href="<?= home_url('/blog'); ?>" class="breadcrumbs__link">Articles</a>
    </div>
    <h1 class="archive-title__name title-lg"><?= $term->name; ?></h1>
    <p class="archive-title__description heading-h2"><?= $term->description; ?></p>
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
?>