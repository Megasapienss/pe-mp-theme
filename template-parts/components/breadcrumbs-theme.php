<?php
/**
 * Template part for displaying breadcrumbs
 *
 * @package PE_MP_Theme
 */

// Get arguments passed from get_template_part
$args = wp_parse_args($args, array(
    'class' => 'breadcrumbs breadcrumbs--light'
));

// Set default values
$home_url = home_url();
$explore_text = 'Explore';

// Get the current post's categories if we're on a single post
$category_link = '';
$category_name = '';
if (is_single()) {
    $categories = get_the_category();
    $main_category = !empty($categories) ? $categories[0] : null;
    $category_link = $main_category ? get_category_link($main_category->term_id) : '';
    $category_name = $main_category ? $main_category->name : '';
}

// Handle archive pages
$current_text = '';
if (is_archive()) {
    $term = get_queried_object();
    if ($term && !is_wp_error($term)) {
        $current_text = $term->name;
    }
}
?>

<div class="breadcrumbs <?= $args['class']; ?>">
    <a href="<?= $home_url; ?>" class="breadcrumbs__link">Home</a>
    <span class="breadcrumbs__separator">/</span>
    
    <?php if ($category_link && $category_name): ?>
        <a href="<?= $home_url; ?>" class="breadcrumbs__link"><?= $explore_text; ?></a>
        <span class="breadcrumbs__separator">/</span>
        <a href="<?= $category_link; ?>" class="breadcrumbs__link"><?= $category_name; ?></a>
    <?php elseif ($current_text): ?>
        <a href="<?= $home_url; ?>" class="breadcrumbs__link"><?= $explore_text; ?></a>
        <span class="breadcrumbs__separator">/</span>
        <span class="breadcrumbs__current"><?= $current_text; ?></span>
    <?php else: ?>
        <span class="breadcrumbs__current"><?= $explore_text; ?></span>
    <?php endif; ?>
</div> 