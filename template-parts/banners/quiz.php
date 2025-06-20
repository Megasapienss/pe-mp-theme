<?php

/**
 * Template part for displaying quiz banner
 *
 * @package PE_MP_Theme
 */

$args = wp_parse_args($args, array(
    'class' => '',
    'link' => pe_mp_get_quiz_link()
));
?>

<div class="banner banner--quiz <?= esc_attr($args['class']); ?>">
    <span class="banner__label label label--icon label--clock label--primary-inversed">2 min</span>
    <h3 class="banner__title heading-h2">Feeling mentally drained?</h3>
    <p class="banner__description">
        Take our 2-minute burnout quiz and get instant insights on your emotional state.
    </p>
    <a href="<?= esc_url($args['link']); ?>" class="banner__link arrow-btn arrow-btn--primary">Start the Quiz</a>
</div>