<?php

/**
 * Template Name: Canvas
 *
 * The template for displaying pages without a hero section
 *
 * @package PE_MP_Theme
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

?>

<?php while (have_posts()) : the_post(); ?>

    <div class="page-v2 container">
        <div class="page-v2__right-sidebar"></div>
        <div class="page-v2__inner">
            <div class="page-v2__content">
                <?php the_content(); ?>
            </div>
        </div>
        <div class="page-v2__left-sidebar"></div>
    </div>

<?php endwhile; ?>

<?php

get_footer();
