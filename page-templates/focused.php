<?php

/**
 * Template Name: Focused
 *
 * The template for displaying pages without header menu
 *
 * @package PE_MP_Theme
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header('focused');

?>

<?php while (have_posts()) : the_post(); ?>

    <section class="page__content container body-lg">
        <?php the_content(); ?>
    </section>

<?php endwhile; ?>

<?php

get_footer('focused');
