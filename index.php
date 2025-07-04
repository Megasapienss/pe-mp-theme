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
    <h1 class="archive-title__name title-lg">Stories and Guides</h1>
    <p class="archive-title__description heading-h2">Discover insights for a healthier mind and life</p>
</section>

<?php
// Get only subcategories (child categories)
$all_categories = get_terms(array(
    'taxonomy' => 'category',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC'
));

// Filter to only include subcategories (categories with a parent)
$subcategories = array();
foreach ($all_categories as $category) {
    if ($category->parent > 0 && $category->parent != 1) {
        $subcategories[] = $category;
    }
}

// Display subcategories if they exist
if (!empty($subcategories) && !is_wp_error($subcategories)) :
    get_template_part('template-parts/sections/topics', null, array(
        'custom_categories' => $subcategories,
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
