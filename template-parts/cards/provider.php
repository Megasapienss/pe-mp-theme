<?php

/**
 * Template part for displaying posts in main card format
 * 
 * @param WP_Post $args['post'] The post object to display
 * @param string $args['title_class'] Optional title class
 * @param string $args['size'] Optional size modifier (e.g., 'small', 'large')
 * @param string $args['orientation'] Optional orientation ('horizontal' or 'vertical', default: 'vertical')
 */

// Exit if accessed directly or post is not passed
if (!isset($args['post'])) {
    return;
}

$post = $args['post'];
$id = $post->ID;
$permalink = get_permalink($id);
$thumbnail = get_field('image_url', $id) ?: get_template_directory_uri() . '/dist/images/cover.webp';
$type = get_field('provider_type', $id);
$type_name = get_term($type)->name;

// Card variations
$size = isset($args['size']) ? $args['size'] : '';
$orientation = isset($args['orientation']) ? $args['orientation'] : '';

$card_classes = 'card-v2 card-v2--provider';
if ($size) {
    $card_classes .= ' card-v2--' . $size;
}
if ($orientation) {
    $card_classes .= ' card-v2--' . $orientation;
}

?>

<a href="<?= esc_url($permalink); ?>" class="<?= esc_attr($card_classes); ?>">
    <div class="card-v2__image">
        <img src="<?= esc_url($thumbnail); ?>">
    </div>
    <div class="card-v2__content">
        <?php if ($orientation == 'vertical') : ?>
            <span class="card-v2__tag label">
                <?= esc_html($type_name); ?>
            </span>
        <?php endif; ?>
        <?php if (get_field('address')['country']) : ?>
        <div class="provider-single__country">
            <img src="<?= get_template_directory_uri(); ?>/dist/icons/countries/Country=<?= get_field('address')['country']; ?>, Style=Flag, Radius=Off.svg">
            <?= esc_html(get_field('address')['country']); ?>
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
        <p class="card-v2__subtitle">
            <?= get_field('subtitle', $id); ?>
        </p>
        <p class="card-v2__excerpt">
            <?php if (!in_array($orientation, ['vertical', 'horizontal']) && $size != 'compact') : ?>
                <?= get_field('short_description_text', $id); ?>
            <?php endif; ?>
        </p>

        <?php if (!in_array($orientation, ['vertical', 'horizontal']) && $size != 'compact') : ?>
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
        <?php endif; ?>
    </div>
</a> 