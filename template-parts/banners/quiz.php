<?php

/**
 * Template part for displaying quiz banner
 *
 * @package PE_MP_Theme
 */

$args = wp_parse_args($args, array(
    'class' => '',
    'link' => PE_MP_QUIZ_DEFAULT_LINK,
    'title' => '',
    'description' => ''
));
?>

<div class="banner banner--quiz <?= esc_attr($args['class']); ?>">
    <span class="banner__label label label--icon label--clock label--primary-inversed">3 min</span>
    <h3 class="banner__title heading-h2"><?= !empty($args['title']) ? esc_html($args['title']) : 'Feeling low?'; ?></h3>
    <p class="banner__description">
        <?= !empty($args['description']) ? esc_html($args['description']) : 'Check your mental state level and get a personalized action plan in 3 minutes.'; ?>
    </p>
    <a href="<?= esc_url($args['link']); ?>" class="banner__link arrow-btn arrow-btn--primary">
        <?php esc_html_e('Start test', 'pe-mp-theme'); ?>
    </a>
</div>