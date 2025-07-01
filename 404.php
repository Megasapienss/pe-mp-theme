<?php

/**
 * The template for displaying 404 pages (not found)
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
    <h1 class="archive-title__name title-lg"><?php esc_html_e('Page Not Found', 'pe-mp-theme'); ?></h1>
    <p class="archive-title__description heading-h2"><?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'pe-mp-theme'); ?></p>
</section>

<?php
get_footer();
