<?php

/**
 * The template for displaying post archives
 * 
 * @package PE_MP_Theme
 */

get_header();
$term = get_queried_object();
$post_count = $wp_query->found_posts;

// Get all condition terms for the filter
$conditions = get_terms(array(
    'taxonomy' => 'condition',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC'
));

// Get current condition filter from URL
$current_condition = isset($_GET['condition']) ? sanitize_text_field($_GET['condition']) : '';
?>

<section class="archive-title-v2 archive-title-v2--providers container">
    <?php
    get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
        'class' => 'breadcrumbs breadcrumbs--dark archive-title__breadcrumbs'
    ));
    ?>
    <h1 class="archive-title-v2__name">Treatment centres in the UK and EU</h1>
    <!-- <p class="archive-title-v2__description"></p> -->
    <img src="<?= get_template_directory_uri(); ?>/dist/images/cover-4x1.webp" alt="Providers" class="archive-title-v2__cover">

</section>

<?php if (!empty($conditions) && !is_wp_error($conditions)) : ?>
<section class="provider-filters container">
    <select id="condition-filter" class="select-v2 condition-filter">
        <option value="">All Conditions</option>
        <?php foreach ($conditions as $condition) : ?>
            <option value="<?= esc_attr($condition->slug) ?>" <?= selected($current_condition, $condition->slug, false) ?>>
                <?= esc_html($condition->name) ?>
            </option>
        <?php endforeach; ?>
    </select>
</section>
<?php endif; ?>

<section class="provider-archive container">
    <div class="provider-archive__inner" id="provider-results">
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