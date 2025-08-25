<?php

/**
 * Template part for displaying posts in shifted card format
 * 
 * @param WP_Post $args['post'] The post object to display
 * @param string $args['size'] Optional size modifier (e.g., 'small')
 * @param string $args['title_class'] Optional title class
 */

// Exit if accessed directly or post is not passed
if (!isset($args['post'])) {
    return;
}

$post = $args['post'];
$permalink = get_permalink($post->ID);
$thumbnail = get_the_post_thumbnail_url($post->ID, 'large') ?: get_template_directory_uri() . '/dist/images/banner-default.webp';

// Derive data from post
$deepest_category = pe_mp_get_deepest_category($post->ID);
$tag = $deepest_category ? $deepest_category->name : 'Science & Innovation';
$date = get_the_date('F j, Y', $post->ID);
$excerpt = wp_trim_words(get_the_excerpt($post), 20);
$author = get_the_author_meta('display_name', $post->post_author);
$author_link = get_author_posts_url($post->post_author);
$review_data = pe_mp_get_medical_review_data($post->ID);

// Card variations
$size = isset($args['size']) ? $args['size'] : '';
$show_author = isset($args['show_author']) ? $args['show_author'] : true;
$show_review = isset($args['show_review']) ? $args['show_review'] : true;

$card_classes = 'card card--shifted';
if ($size) {
    $card_classes .= ' card--shifted--' . $size;
}
?>

<a class="<?= esc_attr($card_classes); ?>" href="<?= esc_url($permalink); ?>">
    <div class="card__image">
        <img src="<?= esc_url($thumbnail); ?>">
    </div>
    <div class="card__content">
        <div class="card__meta">
            <span class="card__tag label">
                <?= esc_html($tag); ?>
            </span>
            <div class="card__date">
                <time datetime="<?= get_the_date('Y-m-d', $post->ID); ?>"><?= esc_html($date); ?></time>
            </div>
        </div>
        
        <h3 class="card__title<?= isset($args['title_class']) ? ' ' . esc_attr($args['title_class']) : ''; ?>">
            <span><?= esc_html($post->post_title); ?></span>
        </h3>
        <p class="card__excerpt">
            <?= esc_html($excerpt); ?>
        </p>
        <?php if ($show_author && $show_review && $review_data) : ?>
        <div class="medical-review-badge card__review">
            <div class="medical-review-badge__reviewer">
                <img src="<?= get_template_directory_uri(); ?>/dist/icons/checkmark.svg">
                <span class="medical-review-badge__reviewer-name">
                    Medically reviewed by: <span><?php echo esc_html($review_data['name']); ?></span>
                </span>
            </div>
        </div>
        <?php elseif ($show_author && $author) : ?>
        <div class="card__author">
            Written By: <span><?= esc_html($author); ?></span>
        </div>
        <?php endif; ?>
    </div>
</a> 