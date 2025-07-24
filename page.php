<?php

/**
 * The template for displaying all single pages
 *
 * @package PE_MP_Theme
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

?>

<?php while (have_posts()) : the_post(); ?>

    <section class="hero hero--banner" style="background-image: url(<?= get_the_post_thumbnail_url() ?: get_template_directory_uri() . '/dist/images/cover.webp'; ?>);">
        <?php
        get_template_part('template-parts/components/breadcrumbs', 'rankmath');
        ?>
        <div class="hero__inner">
            <h1 class="hero__title heading-h2"><?= get_the_title(); ?></h1>
        </div>
    </section>

    <section class="page__content container body-lg">
        <?php the_content(); ?>
    </section>

    <?php get_template_part('template-parts/sections/articles', 'latest'); ?>

<?php endwhile; ?>

<?php

get_footer();
