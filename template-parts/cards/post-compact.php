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

<article class="card card--post card--compact" style="background-image: url(<?= get_the_post_thumbnail_url($post->ID, 'large'); ?>);">
    <div class="card__content">
        <div class="label label--muted">
            <?php echo esc_html($date); ?>
        </div>
        <div class="card__excerpt">
            <h3 class="heading-h6">
                <?php echo esc_html($post->post_title); ?>
            </h3>

            <a href="<?php echo esc_url($permalink); ?>" class="text-btn">
                <?php _e('Read', 'pe-mp-theme'); ?>
            </a>
        </div>

    </div>
</article>