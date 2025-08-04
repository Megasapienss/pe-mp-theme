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
$categories = get_the_category($post->ID);
$tag = !empty($categories) ? $categories[0]->name : 'Science & Innovation';
$date = get_the_date('F j, Y', $post->ID);
$excerpt = wp_trim_words(get_the_excerpt($post), 20);
$author = get_the_author_meta('display_name', $post->post_author);
$author_link = get_author_posts_url($post->post_author);

// Card variations
$size = isset($args['size']) ? $args['size'] : '';

$card_classes = 'card card--shifted';
if ($size) {
    $card_classes .= ' card--shifted--' . $size;
}
?>

<div class="<?= esc_attr($card_classes); ?>">
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
            <a href="<?= esc_url($permalink); ?>"><?= esc_html($post->post_title); ?></a>
        </h3>
        <p class="card__excerpt">
            <?= esc_html($excerpt); ?>
        </p>
        <div class="card__author">
            Written By: <a href="<?= esc_url($author_link); ?>"><?= esc_html($author); ?></a>
        </div>
    </div>
</div> 