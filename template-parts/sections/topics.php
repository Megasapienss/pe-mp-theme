<?php

/**
 * Template part for displaying the topics section
 * 
 * @package PE_MP_Theme
 */

// Check if custom categories are passed via $args
$custom_categories = isset($args['custom_categories']) ? $args['custom_categories'] : null;
$custom_title = isset($args['section_title']) ? $args['section_title'] : null;


if ($custom_categories && !is_wp_error($custom_categories)) {
    // Use the custom categories passed from archive
    $categories = $custom_categories;
} else {
    // Get the 6 most popular categories and tags combined
    $categories = get_terms(array(
        'taxonomy' => array('category', 'post_tag'),
        'number' => 6,
        'orderby' => 'count',
        'order' => 'DESC',
        'hide_empty' => true
    ));
}

$section_title = $custom_title !== null ? $custom_title : 'Explore Topics';

// Don't output anything if there are no categories
if (empty($categories) || is_wp_error($categories)) {
    return;
}
?>

<section class="section topics-section" id="topics">
    <?php if (!empty($section_title)) : ?>
        <div class="section__title">
            <h2 class="section__title-text"><?= $section_title; ?></h2>
            <a href="<?= get_permalink(get_option('page_for_posts')); ?>#topics" class="section__title-link arrow-btn arrow-btn--muted">View all</a>
        </div>
    <?php endif; ?>
    <div class="cards grid grid--2 container container--wide">
        <?php
        // Display category cards
        foreach ($categories as $category) :
            get_template_part('template-parts/cards/term', 'topic', ['term' => $category]);
        endforeach;
        ?>
    </div>
</section>