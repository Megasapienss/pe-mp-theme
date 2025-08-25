<?php

/**
 * Template part for displaying posts in post-v2 format
 * 
 * @param WP_Post $args['post'] The post object to display
 * @param string $args['size'] Optional size modifier (e.g., 'small')
 * @param string $args['image_align'] Optional image alignment ('left' or 'right', default: 'left')
 * @param bool $args['show_image'] Whether to show the image (default: true)
 * @param bool $args['show_excerpt'] Whether to show the excerpt (default: true)
 * @param bool $args['show_author'] Whether to show the author (default: true)
 */

// Exit if accessed directly or post is not passed
if (!isset($args['post'])) {
    return;
}

$post = $args['post'];
$permalink = get_permalink($post->ID);
$thumbnail = get_the_post_thumbnail_url($post->ID, 'medium') ?: get_template_directory_uri() . '/dist/images/banner-default.webp';

// Derive data from post
$deepest_category = pe_mp_get_deepest_category($post->ID);
$tag = $deepest_category ? $deepest_category->name : '';
$review_data = pe_mp_get_medical_review_data($post->ID);    
$excerpt = wp_trim_words(get_the_excerpt($post), 15);
$author = get_the_author_meta('display_name', $post->post_author);

$author_link = get_author_posts_url($post->post_author);

// Card variations
$size = isset($args['size']) ? $args['size'] : '';
$image_align = isset($args['image_align']) ? $args['image_align'] : 'left';
$show_image = isset($args['show_image']) ? $args['show_image'] : true;
$show_excerpt = isset($args['show_excerpt']) ? $args['show_excerpt'] : true;
$show_author = isset($args['show_author']) ? $args['show_author'] : true;
$show_review = isset($args['show_review']) ? $args['show_review'] : true;

$card_classes = 'card-v2';
if ($size) {
    $card_classes .= ' card-v2--' . $size;
}
if ($image_align === 'right') {
    $card_classes .= ' card-v2--image-right';
}
?>

<a class="<?= esc_attr($card_classes); ?>" href="<?= esc_url($permalink); ?>">
    <?php if ($show_image && $image_align === 'left') : ?>
    <div class="card-v2__image">
        <img src="<?= esc_url($thumbnail); ?>">
    </div>
    <?php endif; ?>
    
    <div class="card-v2__content">
        <span class="card-v2__tag label">
            <?= esc_html($tag); ?>
        </span>
        <h5 class="card-v2__title<?= isset($args['title_class']) ? ' ' . esc_attr($args['title_class']) : ''; ?>">
            <span><?= esc_html($post->post_title); ?></span>
        </h5>
        
        <?php if ($show_excerpt && $excerpt) : ?>
        <p class="card-v2__excerpt">
            <?= esc_html($excerpt); ?>
        </p>
        <?php endif; ?>
        
        <?php if ($show_author && $show_review && $review_data) : ?>
        <div class="medical-review-badge card-v2__review">
            <div class="medical-review-badge__reviewer">
                <img src="<?= get_template_directory_uri(); ?>/dist/icons/checkmark.svg">
                <span class="medical-review-badge__reviewer-name">
                    Medically reviewed by: <span><?php echo esc_html($review_data['name']); ?></span>
                </span>
            </div>
        </div>
        <?php elseif ($show_author && $author) : ?>
        <div class="card-v2__author">
            Written by: <span><?= esc_html($author); ?></span>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if ($show_image && $image_align === 'right') : ?>
    <div class="card-v2__image">
        <img src="<?= esc_url($thumbnail); ?>">
    </div>
    <?php endif; ?>
</a> 