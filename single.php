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

    <section class="hero hero--banner" style="background-image: url(<?= get_the_post_thumbnail_url() ?: get_template_directory_uri() . '/dist/images/cover.jpg'; ?>);">
        <?php
        get_template_part('template-parts/components/breadcrumbs', 'rankmath');
        ?>
        <div class="hero__inner">
            <div class="hero__date label label--arrow label--muted">
                <?= get_the_date('d M Y'); ?>
            </div>
            <h1 class="hero__title heading-h2"><?= get_the_title(); ?></h1>
        </div>
    </section>

    <article class="article container">
        <section class="article__content body-lg">
            <?php the_content(); ?>
            <div class="sidebar-card sidebar-card--quiz hidden-more-than-xl">
                <h3 class="sidebar-card__title">Feeling mentally drained?</h3>
                <p class="sidebar-card__excerpt">Take our 2-minute burnout quiz
                    and get instant insights on your emotional state.</p>
                <!-- <a href="<?= pe_mp_get_quiz_link(); ?>" class="sidebar-card__link arrow-btn arrow-btn--primary">Start the Quiz</a> -->
                <button class="sidebar-card__link arrow-btn arrow-btn--primary" onclick="window.dispatchEvent(new CustomEvent('heyflow-modal-element:open', { detail: { modalId: '2yeWxj1NPN' }}))">
                    <?php esc_html_e('Start the Quiz', 'pe-mp-theme'); ?>
                </button>
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
                <div class="sidebar-card__time">
                    <span class="heading-h6">Estimated reading time</span>
                    <span class="sidebar-card__time-value">6 min</span>
                </div>

                <div class="sidebar-card__share">
                    <span class="heading-h6">Share</span>
                    <div class="sidebar-card__share-icons d-flex flex-row">
                        <img src="<?= get_template_directory_uri(); ?>/dist/icons/badge-instagram.svg">
                        <img src="<?= get_template_directory_uri(); ?>/dist/icons/badge-x.svg">
                        <img src="<?= get_template_directory_uri(); ?>/dist/icons/badge-f.svg">

                    </div>
                </div>
            </div>
            <div class="sidebar-card sidebar-card--quiz hidden-less-than-xl">
                <h3 class="sidebar-card__title">Feeling mentally drained?</h3>
                <p class="sidebar-card__excerpt">Take our 2-minute burnout quiz
                    and get instant insights on your emotional state.
                </p>
                <button class="sidebar-card__link arrow-btn arrow-btn--primary" onclick="window.dispatchEvent(new CustomEvent('heyflow-modal-element:open', { detail: { modalId: '2yeWxj1NPN' }}))">
                    <?php esc_html_e('Start the Quiz', 'pe-mp-theme'); ?>
                </button>
            </div>
        </aside>
    </article>

    <section class="section">
        <div class="section__title">
            <h2 class="section__title-text">What else is worth exploring?</h2>
            <a href="<?= get_permalink(get_option('page_for_posts')); ?>#posts" class="section__title-link arrow-btn arrow-btn--muted">See all</a>
        </div>
        <div class="cards grid grid--3">
            <?php
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

            // Display related posts
            if ($related_posts->have_posts()) :
                foreach ($related_posts->posts as $post) :
                    get_template_part('template-parts/cards/post', 'simple', ['post' => $post]);
                endforeach;
            else :
                echo '<p>' . __('No related posts found.', 'pe-mp-theme') . '</p>';
            endif;
            ?>
        </div>
    </section>

    <?php //get_template_part('template-parts/sections/experts'); 
    ?>

    <?php get_template_part('template-parts/sections/articles', 'latest'); ?>


<?php endwhile; ?>

<?php

get_footer();
