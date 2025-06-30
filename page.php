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

    <section class="hero hero--banner" style="background-image: url(<?= get_the_post_thumbnail_url() ?: get_template_directory_uri() . '/dist/images/cover.jpg'; ?>);">
        <?php
        get_template_part('template-parts/components/breadcrumbs', 'rankmath');
        ?>
        <div class="hero__inner">
            <h1 class="hero__title heading-h2"><?= get_the_title(); ?></h1>
        </div>
    </section>

    <section class="page__content container body-lg">
        <?php the_content(); ?>
        <div class="sidebar-card sidebar-card--quiz hidden-more-than-xl">
            <h3 class="sidebar-card__title">Feeling mentally drained?</h3>
            <p class="sidebar-card__excerpt">Take our 2-minute burnout quiz
                and get instant insights on your emotional state.</p>
            <button class="sidebar-card__link arrow-btn arrow-btn--primary" onclick="window.dispatchEvent(new CustomEvent('heyflow-modal-element:open', { detail: { modalId: '2yeWxj1NPN' }}))">
                <?php esc_html_e('Start the Quiz', 'pe-mp-theme'); ?>
            </button>
        </div>
    </section>

    <?php get_template_part('template-parts/sections/articles', 'latest'); ?>

<?php endwhile; ?>

<?php

get_footer();
