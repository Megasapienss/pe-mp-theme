<?php

/**
 * The template for displaying all single pages
 *
 * @package PE_MP_Theme
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

?>

<?php while (have_posts()) : the_post(); ?>

    <section class="single-title-v2 container">
        <?php
        get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
            'class' => 'breadcrumbs breadcrumbs--dark single-title-v2__breadcrumbs'
        ));
        ?>
        <!-- <h1 class="single-title-v2__title heading-h1"><?= get_the_title(); ?></h1> -->
    </section>

    <div class="page-v2 container">
        <div class="page-v2__right-sidebar"></div>
        <div class="page-v2__inner">
            <h1 class="page-v2__title"><?= get_the_title(); ?></h1>
            <div class="page-v2__content">
                <?php the_content(); ?>
            </div>
        </div>
        <div class="page-v2__left-sidebar"></div>
    </div>

<?php endwhile; ?>

<?php

get_footer();
