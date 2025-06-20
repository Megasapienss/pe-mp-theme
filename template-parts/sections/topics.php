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
    $section_title = $custom_title !== null ? $custom_title : 'Explore Topics';
} else {
    // Get the 6 most popular categories (original behavior)
    $categories = get_terms(array(
        'taxonomy' => 'category',
        'number' => 6,
        'orderby' => 'count',
        'order' => 'DESC',
        'hide_empty' => true
    ));
    $section_title = 'Explore by Topic';
}

// Don't output anything if there are no categories
if (empty($categories) || is_wp_error($categories)) {
    return;
}
?>

<section class="section" id="topics">
    <?php if (!empty($section_title)) : ?>
    <div class="section__title">
        <h2 class="section__title-text"><?= $section_title; ?></h2>
        <a href="#" class="section__title-link arrow-btn arrow-btn--muted">See all</a>
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