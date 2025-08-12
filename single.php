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

    <?php
    $thumbnail_id = get_post_thumbnail_id();
    $background_url = '';
    
    if ($thumbnail_id) {
        // First try to get WebP version
        $webp_url = pe_mp_get_webp_url($thumbnail_id);
        if ($webp_url) {
            $background_url = $webp_url;
        } else {
            // If no WebP, use original thumbnail
            $background_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
        }
    }
    
    // If no thumbnail or WebP, use default cover
    if (empty($background_url)) {
        $background_url = get_template_directory_uri() . '/dist/images/cover.webp';
    }
    ?>
    
    <!-- <section class="hero hero--banner" style="background-image: url('<?= esc_url($background_url); ?>');">
        <?php get_template_part('template-parts/components/breadcrumbs', 'rankmath'); ?>
        <div class="hero__inner">
            <div class="hero__date label label--arrow label--muted">
                <?= get_the_date('d M Y'); ?>
            </div>
            <h1 class="hero__title heading-h2"><?= get_the_title(); ?></h1>

        </div>
        <div class="hero__toc-wrapper">
            <div class="hero__toc">
                <div class="hero__toc-list">
                </div>
            </div>
        </div>
    </section> -->

    <section class="single-title-v2 container">
        <?php
        get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
            'class' => 'breadcrumbs breadcrumbs--dark single-title-v2__breadcrumbs'
        ));
        ?>
    </section>

    <article class="article-v2 container">

        <div class="article-v2__meta">
            <div class="article-v2__meta-item">
                <span class="article-v2__meta-label">Date</span>
                <span class="article-v2__meta-value"><?= get_the_date('d M Y'); ?></span>
            </div>
            <div class="article-v2__meta-item">
                <span class="article-v2__meta-label">Reading time</span>
                <span class="article-v2__meta-value">
                    <?php
                        $content = get_post_field('post_content', get_the_ID());
                        $word_count = str_word_count(strip_tags($content));
                        $reading_time = ceil($word_count / 400);
                        echo $reading_time . ' min';
                    ?>
                </span>
            </div>
            <div class="article-v2__meta-item">
                <?php
                $categories = get_the_category();
                if (!empty($categories)) {
                    $main_category = $categories[0];
                    ?>
                    <span class="label"><?= esc_html($main_category->name); ?></span>
                    <?php
                }
                ?>
            </div>
        </div>

        <div class="article-v2__inner">

            <h1 class="article-v2__title"><?= get_the_title(); ?></h1>
            <p class="article-v2__excerpt"><?= get_the_excerpt(); ?></p>
            <div class="article-v2__author">
                <div class="article-v2__author-avatar">
                    <?php echo get_avatar(get_the_author_meta('ID'), 60); ?>
                </div>
                <div class="article-v2__author-name">Written by: <a href="<?= get_author_posts_url(get_the_author_meta('ID')); ?>"><?= get_the_author(); ?></a></div>
                <div class="article-v2__author-description"><?= get_the_author_meta('description'); ?></div>
            </div>

            <div class="article-v2__cover">
                <img src="<?= $background_url; ?>" alt="<?= get_the_title(); ?>">
            </div>

            <div class="article-v2__content-wrapper">

                <div class="article-v2__content body-lg">
                    <?php the_content(); ?>
                    <div class="article-v2__share">
                        <span class="icon-tag icon-tag--rounded icon-tag--globe share-trigger">Share this article</span>
                    </div>
                </div>

                <div class="article-v2__sidebar">
                    <div class="sidebar-card-v2">
                        <div class="tabs article-v2__sidebar-tabs">
                            <div class="tabs__header">
                                <span class="tabs__button tabs__button--active" data-tab="sections">Sections</span>
                                <span class="tabs__button" data-tab="sources">Sources</span>
                            </div>
                            <div class="tabs__body">
                                <div class="tabs__content article-v2__toc" data-tab="sections">
                                    <nav class="article-v2__toc-list">
                                    </nav>
                                </div>
                                <div class="tabs__content article-v2__sources" data-tab="sources">
                                    <nav class="article-v2__sources-list sources-list">
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-card-v2">
                        <?php

                        get_template_part('template-parts/banners/test', '', [
                            'test_id' => pe_mp_get_related_test_id()
                        ]); 
                            
                        ?>
                    </div>
                </div>

            </div>

        </div>
        
    </article>

    <?php
    // New articles Section
    get_template_part('template-parts/mosaics/recommendations');
    ?>  

    <?php if (false) : ?>
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
    <?php endif; ?>

<?php endwhile; ?>

<?php

get_footer();
