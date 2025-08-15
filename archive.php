<?php

/**
 * The template for displaying post archives
 * 
 * @package PE_MP_Theme
 */

get_header();
$term = get_queried_object();
$post_count = $wp_query->found_posts;
?>

<section class="archive-title-v2 container">
    <?php
    get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
        'class' => 'breadcrumbs breadcrumbs--dark archive-title__breadcrumbs'
    ));
    ?>
    <h1 class="archive-title-v2__name"><?= get_field('h1', $term) ?: $term->name; ?></h1>
    <p class="archive-title-v2__description"><?= $term->description; ?></p>
</section>

<?php if ($term && !is_wp_error($term) && $term->slug === 'diagnostics') : ?>
    <section class="section-v2 container grid grid--2">
    <?php
        $test_ids = [
            'anxiety',
            'depression',
            'adhd',
            'ptsd',
            // 'ed',
            // 'ocd',
            // 'burnout'
        ];
        foreach ($test_ids as $test_id){
            get_template_part('template-parts/banners/test', '', [
                'test_id' => $test_id
            ]); 
        }
    ?>
    </section>
<?php endif; ?>


<?php
// Get child categories of the current term
$child_categories = get_terms(array(
    'taxonomy' => $term->taxonomy,
    'parent' => $term->term_id,
    'hide_empty' => true
));

// If there are subcategories, display a mosaic for each subcategory
if (!empty($child_categories) && !is_wp_error($child_categories)) :
    foreach ($child_categories as $child_category) :
        get_template_part('template-parts/mosaics/category', null, array(
            'title' => $child_category->name,
            'taxonomy' => $child_category->taxonomy,
            'term' => $child_category->slug
        ));
    endforeach;
else :
    // If no subcategories, display the current archive-grid
?>
    <section class="archive-grid mosaic mosaic--1-1 container container--wide">
        <?php if (have_posts()) {
            while (have_posts()) {
                the_post();
                ?>
                <div class="mosaic__item">
                    <?php get_template_part('template-parts/cards/post', 'v2', ['post' => get_post(), 'size' => 'large']); ?>
                </div>
                <?php
            }
            // If this is the middle post (when total is odd), insert newsletter banner
            // if ($post_count % 2 !== 0) {
            //     get_template_part('template-parts/banners/newsletter');
            // }
        } else { ?>
            <!-- <p class="text-center"><?php _e('No posts found.', 'pe-mp-theme'); ?></p>
            <p class="text-center"><?php _e('Come back later!', 'pe-mp-theme'); ?></p> -->
        <?php } ?>
    </section>
<?php endif; ?>

<?php
// if ($post_count % 2 == 0) {
//     get_template_part('template-parts/sections/newsletter');
// }
?>

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