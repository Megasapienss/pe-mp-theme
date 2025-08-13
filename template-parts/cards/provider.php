<?php

/**
 * Template part for displaying posts in main card format
 * 
 * @param WP_Post $args['post'] The post object to display
 * @param string $args['title_class'] Optional title class
 */

// Exit if accessed directly or post is not passed
if (!isset($args['post'])) {
    return;
}

$post = $args['post'];
$id = $post->ID;
$permalink = get_permalink($id);
$thumbnail = get_field('image_url', $id) ?: get_template_directory_uri() . '/dist/images/cover.webp';

?>

<a href="<?= esc_url($permalink); ?>" class="card-v2 card-v2--provider">
    <div class="card-v2__image">
        <img src="<?= esc_url($thumbnail); ?>">
    </div>
    <div class="card-v2__content">
        <?php if (get_field('countries_list')) : ?>
        <div class="provider-single__meta-item">
            <?php $countries = get_field('countries_list'); ?>
            <div class="d-flex flex-row">
                <?php foreach ($countries as $country_id) : ?>
                    <?php $country = get_post($country_id); ?>
                    <div class="icon-tag icon-tag--globe text-muted"><?= esc_html($country->name); ?></div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="card-v2__title-wrapper">
            <h3 class="card-v2__title">
                <?= esc_html($post->post_title); ?>
            </h3>
            <?php
            $card_tier = get_field('card_tier_badge', $id);
            if ($card_tier && is_object($card_tier)) {
                $tier_slug = $card_tier->slug;
                $tier_name = $card_tier->name;
                $icon_path = get_template_directory_uri() . '/dist/icons/badges/' . $tier_slug . '--sm.svg';
                if (file_exists(get_template_directory() . '/dist/icons/badges/' . $tier_slug . '--sm.svg')) {
                    ?>
                    <img src="<?= esc_url($icon_path); ?>" class="provider-single__tier--sm">
                    <?php
                }
            }
            ?>
            
        </div>

        <p class="card-v2__excerpt">
            <?= get_field('subtitle', $id); ?><br>
            <?= get_field('short_description_text', $id); ?>
        </p>

        <div class="card-v2__meta">
                <?php
                // List of taxonomies to display
                $taxonomies = array('provider-type', 'service-delivery-method');
                foreach ($taxonomies as $taxonomy) {
                    $terms = get_the_terms(get_the_ID(), $taxonomy);
                    if (!empty($terms) && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            ?>
                            <span class="icon-tag icon-tag--rounded"><?= esc_html($term->name); ?></span>
                            <?php
                        }
                    }
                }
                ?>
            </div>
    </div>
</a> 