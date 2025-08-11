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
$post_count = $wp_query->found_posts;
?>

<section class="archive-title-v2 container">
    <?php
    get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
        'class' => 'breadcrumbs breadcrumbs--dark archive-title__breadcrumbs'
    ));
    ?>
    <h1 class="archive-title-v2__name">Stories and Guides</h1>
    <p class="archive-title-v2__description">Discover insights for a healthier mind and life</p>
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

// If there are subcategories, display a mosaic for each subcategory
if (!empty($subcategories) && !is_wp_error($subcategories)) :
    foreach ($subcategories as $subcategory) :
        get_template_part('template-parts/mosaics/category', null, array(
            'title' => $subcategory->name,
            'taxonomy' => $subcategory->taxonomy,
            'term' => $subcategory->slug
        ));
    endforeach;
else :
    // If no subcategories, display the current archive-grid
?>

    <section class="archive-grid mosaic mosaic--1-1-1 container container--wide">
        <?php if (have_posts()) {
            while (have_posts()) {
                the_post();
                ?>
                <div class="mosaic__item">
                    <?php get_template_part('template-parts/cards/post', 'v2', ['post' => get_post()]); ?>
                </div>
                <?php
            }
        } else { ?>
            <p class="text-center"><?php _e('No posts found.', 'pe-mp-theme'); ?></p>
            <p class="text-center"><?php _e('Come back later!', 'pe-mp-theme'); ?></p>
        <?php } ?>
    </section>
<?php endif; ?>

<?php
//get_template_part('template-parts/sections/apps'); 
?>

<?php
//get_template_part('template-parts/sections/experts'); 
?>

<?php
//get_template_part('template-parts/sections/clinics'); 
?>

<?php
// get_template_part('template-parts/sections/articles', 'top');
?>

<?php
get_footer();
?>
