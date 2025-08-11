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
    <h1 class="archive-title-v2__name">Providers</h1>
    <p class="archive-title-v2__description">Find a provider</p>
    <img src="<?= get_template_directory_uri(); ?>/dist/images/cover-4x1.webp" alt="Providers" class="archive-title-v2__cover">

</section>

<section class="provider-archive container">
    <div class="provider-archive__inner">
        <?php if (have_posts()) {
            while (have_posts()) {
                the_post();
                get_template_part('template-parts/cards/provider', '', ['post' => get_post()]); 
            }
        } else { ?>
            <p class="text-center"><?php _e('No providers found.', 'pe-mp-theme'); ?></p>
        <?php } ?>
    </div>
    <div class="provider-archive__sidebar">
        <div class="sidebar-card-v2">
            <?php

            get_template_part('template-parts/banners/test', '', [
                'test_id' => pe_mp_get_related_test_id()
            ]); 
                
            ?>
        </div>
    </div>
</section>

<?php
get_template_part('template-parts/mosaics/recommendations');
?>

<?php
get_footer();
?>