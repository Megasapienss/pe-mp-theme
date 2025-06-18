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

$categories = get_the_category();
$main_category = !empty($categories) ? $categories[0] : null;
$category_link = $main_category ? get_category_link($main_category->term_id) : '';
$category_name = $main_category ? $main_category->name : '';

?>

<?php while (have_posts()) : the_post(); ?>

    <section class="hero hero--banner" style="background-image: url(<?= get_the_post_thumbnail_url(); ?>);">
        <div class="breadcrumbs breadcrumbs--light hero__breadcrumbs">
            <a href="<?= home_url(); ?>" class="breadcrumbs__link">Home</a>
            <span class="breadcrumbs__separator">/</span>
            <a href="<?= home_url('/blog/'); ?>" class="breadcrumbs__link">Blog</a>
            <span class="breadcrumbs__separator">/</span>
            <a href="<?= $category_link; ?>" class="breadcrumbs__link"><?= $category_name; ?></a>
        </div>
        <div class="hero__inner">
            <div class="hero__date label label--arrow label--muted">
                <?= get_the_date('d M Y'); ?>
            </div>
            <h1 class="hero__title heading-h2"><?= get_the_title(); ?></h1>
        </div>
    </section>
    <article class="article container">
        <section class="article__content body-lg">
            <?= get_the_content(); ?>
        </section>
        <aside class="article__sidebar">
            <div class="sidebar-card sidebar-card--author">
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
            </div>
            <div class="sidebar-card">
                <h3 class="sidebar-card__title">Feeling mentally drained?</h3>
                <p class="sidebar-card__excerpt">Take our 2-minute burnout quiz
                    and get instant insights on your emotional state.</p>
                <a href="#" class="sidebar-card__link arrow-btn arrow-btn--primary">Start the Quiz</a>
            </div>
        </aside>
    </article>
    <section class="section">
        <h2 class="section__title">What else is worth exploring?</h2>
        <div class="cards">
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
                // 'post__not_in' => array(get_the_ID()),
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

<?php endwhile; ?>

<?php

get_footer();
