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
    // 'ed',
    // 'ocd',
    'burnout'
];

$test_ids = isset($args['test_ids']) ? $args['test_ids'] : $default_test_ids;
?>

<section class="section-v2 tests-section container">
    <div class="section-v2__title">
        <h2>Check Your Mental State</h2>
    </div>
    <div class="tests-section__content overflow-breakout">
        <?php foreach ($test_ids as $test_id) : ?>
            <?php 
            get_template_part('template-parts/banners/test', '', [
                'test_id' => $test_id
            ]); 
            ?>
        <?php endforeach; ?>
    </div>
</section> 