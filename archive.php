<?php

/**
 * The template for displaying post archives
 * 
 * @package PE_MP_Theme
 */

get_header();
$term = get_queried_object();
?>

<section class="archive-title container">
    <?php
    get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
        'class' => 'breadcrumbs breadcrumbs--dark archive-title__breadcrumbs'
    ));
    ?>
    <h1 class="archive-title__name title-lg"><?= get_field('h1', $term) ?: $term->name; ?></h1>
    <p class="archive-title__description heading-h2"><?= $term->description; ?></p>
</section>

<?php
// Get child categories of the current term
$child_categories = get_terms(array(
    'taxonomy' => $term->taxonomy,
    'parent' => $term->term_id,
    'hide_empty' => true
));

// Display child categories if they exist
if (!empty($child_categories) && !is_wp_error($child_categories)) :
    get_template_part('template-parts/sections/topics', null, array(
        'custom_categories' => $child_categories,
        'section_title' => ''
    ));
endif;
?>

<section class="archive-grid grid grid--2 container container--wide">
    <?php if (have_posts()): ?>
        <?php
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/cards/post', 'curved', ['post' => get_post()]);
        }
        ?>

    <?php else: ?>
        <p class="text-center"><?php _e('No posts found.', 'pe-mp-theme'); ?></p>
        <p class="text-center"><?php _e('Come back later!', 'pe-mp-theme'); ?></p>
    <?php endif; ?>
</section>

<?php
get_template_part('template-parts/sections/newsletter');
?>

<?php //get_template_part('template-parts/sections/apps'); 
?>

<?php //get_template_part('template-parts/sections/experts'); 
?>

<?php
//get_template_part('template-parts/sections/clinics'); 
?>

<?php
get_template_part('template-parts/sections/articles', 'related');
?>

<?php
get_footer();
?>