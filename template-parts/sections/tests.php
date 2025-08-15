<?php

/**
 * Template part for displaying the tests section
 *
 * @param array $args['test_ids'] Array of test IDs
 *
 * @package PE_MP_Theme
 */

$default_test_ids = [
    'anxiety',
    'depression',
    'adhd',
    'ptsd',
    'burnout'
];

$test_ids = isset($args['test_ids']) ? $args['test_ids'] : $default_test_ids;
?>

<section class="section-v2 tests-section">
    <div class="container">
    <div class="section-v2__title">
        <h2>Check Your Mental State</h2>
        <a href="<?= esc_url(get_term_link('diagnostics', 'category')); ?>" class="btn btn--muted btn--arrow">
            See all
        </a>
    </div>
    </div>
    <div class="tests-section__content">
        <div class="swiper tests-swiper">
            <div class="swiper-wrapper">
                <?php foreach ($test_ids as $test_id) : ?>
                    <div class="swiper-slide">
                        <?php 
                        get_template_part('template-parts/banners/test', '', [
                            'test_id' => $test_id
                        ]); 
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Navigation arrows -->
            <!-- <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div> -->
        </div>
    </div>
</section> 