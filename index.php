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
    <?php 
    get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
        'class' => 'breadcrumbs breadcrumbs--dark archive-title__breadcrumbs'
    )); 
    ?>
    <h1 class="archive-title__name title-lg">Explore</h1>
    <p class="archive-title__description heading-h2">Latest articles and updates</p>
</section>

<?php
// Get all post tags
$post_tags = get_terms(array(
    'taxonomy' => 'post_tag',
    'hide_empty' => true
));

// Display post tags if they exist
if (!empty($post_tags) && !is_wp_error($post_tags)) :
    get_template_part('template-parts/sections/topics', null, array(
        'custom_categories' => $post_tags,
        'section_title' => ''
    ));
endif;
?>

<section id="posts" class="archive-grid grid grid--2 container container--wide">
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
