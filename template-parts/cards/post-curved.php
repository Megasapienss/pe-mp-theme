<?php

/**
 * Template part for displaying posts in card format
 * 
 * @param WP_Post $args['post'] The post object to display
 */

// Exit if accessed directly or post is not passed
if (!isset($args['post'])) {
    return;
}

$post = $args['post'];
$permalink = get_permalink($post->ID);
$thumbnail = get_the_post_thumbnail($post->ID, 'medium', array('class' => 'card__thumbnail'));
$categories = get_the_category($post->ID);
$date = get_the_date('', $post->ID);
$excerpt = wp_trim_words(get_the_excerpt($post), 20);
?>

<a href="<?php echo esc_url($permalink); ?>" class="card card--post card--curved">
    <div class="card__cover" style="background-image: url(<?= get_the_post_thumbnail_url($post->ID, 'large'); ?>);">
        <div class="card__corner corner corner--left-top">
            <div class="label label--muted">
                <?php echo esc_html($date); ?>
            </div>
        </div>
        <div class="card__corner corner corner--right-bottom">
            <img src="<?= get_template_directory_uri(); ?>/dist/icons/button-arrow-right.svg">
        </div>
    </div>
    <div class="card__content">

        <h3 class="card__title">
            <?php echo esc_html($post->post_title); ?>
        </h3>
        <div class="card__excerpt">
            <?= esc_html($excerpt); ?>
        </div>

    </div>
</a>