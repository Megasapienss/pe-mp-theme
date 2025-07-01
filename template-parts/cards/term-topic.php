<?php

/**
 * Template part for displaying category terms in topic card format
 * 
 * @param WP_Term $args['term'] The term object to display
 */

// Exit if accessed directly or term is not passed
if (!isset($args['term'])) {
    return;
}

$term = $args['term'];
$term_link = get_term_link($term);
$term_description = wp_trim_words($term->description, 15);
$term_count = $term->count;

// Get the featured image from ACF field, fallback to placeholder
$term_image = get_field('featured_image', $term) ?: get_template_directory_uri() . '/src/images/topic-card-placeholder.png';

// Get child categories for tags (if any)
$child_categories = get_terms(array(
    'taxonomy' => $term->taxonomy,
    'parent' => $term->term_id,
    'number' => 3,
    'hide_empty' => false
));
?>

<a href="<?php echo esc_url($term_link); ?>" class="card card--topic">
    <img src="<?php echo esc_url($term_image); ?>" alt="<?php echo esc_attr($term->name); ?>" class="card__image">
    <div class="d-flex flex-column">
        <h3 class="card__title">
            <?php echo esc_html($term->name); ?>
        </h3>
        <div class="card__excerpt">
            <?php echo esc_html($term_description); ?>
        </div>
        <?php if (!empty($child_categories) && !is_wp_error($child_categories)) : ?>
            <div class="card__tags tags">
                <?php foreach ($child_categories as $child) : ?>
                    <span class="label label--squared label--primary-inversed">
                        <?php echo esc_html($child->name); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="card__corner corner corner--right-top">
        <img src="<?= get_template_directory_uri(); ?>/dist/icons/badge-arrow-45.svg" alt="">
        <!-- <span class="arrow-btn arrow-btn--primary">
            <?php echo esc_html($term->name); ?>
        </span> -->
    </div>
</a>