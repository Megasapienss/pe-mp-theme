<?php

/**
 * The template for displaying all single posts
 *
 * @package PE_MP_Theme
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

?>

<?php while (have_posts()) : the_post(); ?>

    <section class="hero hero--banner" style="background-image: url('<?= pe_mp_get_webp_url(get_post_thumbnail_id()) ?: get_template_directory_uri() . '/dist/images/cover.webp'; ?>');">
        <?php get_template_part('template-parts/components/breadcrumbs', 'rankmath'); ?>
        <div class="hero__inner">
            <div class="hero__date label label--arrow label--muted">
                <?= get_the_date('d M Y'); ?>
            </div>
            <h1 class="hero__title heading-h2"><?= get_the_title(); ?></h1>

        </div>
        <!-- <div class="hero__toc-wrapper">
            <div class="hero__toc">
                <div class="hero__toc-list">
                </div>
            </div>
        </div> -->
    </section>

    <article class="article container">
        <section class="article__content body-lg">
            <?php the_content(); ?>
            <div class="article__sources accordion">
                <div class="accordion__header">
                    <h2 class="accordion__title">Sources</h2>
                    <img src="<?= get_template_directory_uri(); ?>/dist/icons/icon-arrow-down.svg">
                </div>
                <div class="accordion__body">
                    <nav class="sources-list">
                        <!-- Sources will be populated by JavaScript -->
                    </nav>
                </div>
            </div>
        </section>
        <aside class="article__sidebar">
            <div class="sidebar-card sidebar-card--author">
                <div class="corner corner--right-top">
                    <span>Author</span>
                </div>
                <?php
                $author_id = get_the_author_meta('ID');
                $avatar = get_avatar_url($author_id, array('size' => 120));
                $author_name = get_the_author_meta('display_name');
                $author_description = get_the_author_meta('description');
                ?>
                <img src="<?= $avatar; ?>" class="sidebar-card__avatar">
                <h3 class="sidebar-card__title"><?= $author_name; ?></h3>
                <p class="sidebar-card__excerpt">
                    <?= $author_description; ?>
                </p>

                <!-- <div class="sidebar-card__time">
                    <span class="heading-h6">Estimated reading time</span>
                    <span class="sidebar-card__time-value">6 min</span>
                </div> -->

                <div class="sidebar-card__share">
                    <span class="label label--share label--primary label--icon">Share</span>
                    <!-- <div class="sidebar-card__share-icons d-flex flex-row">
                        <img src="<?= get_template_directory_uri(); ?>/dist/icons/badge-instagram.svg">
                        <img src="<?= get_template_directory_uri(); ?>/dist/icons/badge-x.svg">
                        <img src="<?= get_template_directory_uri(); ?>/dist/icons/badge-f.svg">
                    </div> -->
                </div>
            </div>
            <div class="sidebar-card sidebar-card--quiz">
                <h3 class="sidebar-card__title">Feeling low?</h3>
                <p class="sidebar-card__excerpt">
                    Check your mental state level and get a personalized action plan in 3 minutes.
                </p>
                <?php
                // Priority system for link:
                // 1. Assigned page from ACF field
                // 2. Hardcoded link
                $quiz_link = pe_mp_get_related_test_page_url();
                if (empty($quiz_link)) {
                    $quiz_link = PE_MP_QUIZ_DEFAULT_LINK;
                }
                ?>
                <a href="<?= esc_url($quiz_link); ?>" class="sidebar-card__link arrow-btn arrow-btn--primary">
                    <?php esc_html_e('Start test', 'pe-mp-theme'); ?>
                </a>
            </div>
        </aside>
    </article>

    <?php
    //get_template_part('template-parts/sections/experts'); 
    ?>

    <?php get_template_part('template-parts/sections/articles', 'related'); ?>


<?php endwhile; ?>

<?php

get_footer();
