<?php

/**
 * Template part for displaying test banners
 * 
 * @param string $args['test_id'] The ID of the test for matching
 * @param string $args['test_title'] Optional display title (uses config title if not provided)
 * @param string $args['custom_description'] Optional custom description (overrides default)
 * @param string $args['custom_url'] Optional custom URL (overrides default)
 * @param string $args['custom_bg_color'] Optional custom background color (overrides default)
 * @param string $args['custom_text_color'] Optional custom text color (overrides default)
 */

$test_id = isset($args['test_id']) ? $args['test_id'] : 'assessment';

// Test configurations with default values
$test_configs = [
    'assessment' => [
        'title' => 'Assessment',
        'description' => 'Your mental health self-scan',
        'url' => '/tests/assessment/',
        'bg_color' => '#B877FF',
        'text_color' => '#FFFFFF',
        'image' => get_template_directory_uri() . '/dist/images/states/anxiety.svg'
    ],
    'anxiety' => [
        'title' => 'Anxiety',
        'description' => 'How stressed or overwhelmed are you right now?',
        'url' => '/tests/anxiety-standalone/',
        'bg_color' => '#5D5FEF',
        'text_color' => '#FFFFFF',
        'image' => get_template_directory_uri() . '/dist/images/states/anxiety.svg'
    ],
    'depression' => [
        'title' => 'Depression Screener',
        'description' => 'Explore symptoms of low mood, loss of motivation, and mental fatigue.',
        'url' => '/tests/depression-standalone/',
        'bg_color' => '#AEE6D8',
        'text_color' => '#202527',
        'image' => get_template_directory_uri() . '/dist/images/states/depression.svg'
    ],
    'adhd' => [
        'title' => 'Adult ADHD',
        'description' => 'A quick screener to detect signs of trauma and stress response.',
        'url' => '/tests/adhd-standalone/',
        'bg_color' => '#FFC86B',
        'text_color' => '#202527',
        'image' => get_template_directory_uri() . '/dist/images/states/adhd.svg'
    ],
    'ptsd' => [
        'title' => 'PTSD Checklist for DSM-5',
        'description' => 'A quick screener to detect signs of trauma and stress response.',
        'url' => '/tests/ptsd-standalone/',
        'bg_color' => '#FF6D8B',
        'text_color' => '#FFFFFF',
        'image' => get_template_directory_uri() . '/dist/images/states/ptsd.svg'
    ],
    'ed' => [
        'title' => 'Eat Disorder',
        'description' => 'Feeling out of control around food or your body?',
        'url' => '/tests/eating-disorder-standalone/',
        'bg_color' => '#CEF0E8',
        'text_color' => '#202527',
        'image' => get_template_directory_uri() . '/dist/images/states/eating-disorder.svg'
    ],
    'ocd' => [
        'title' => 'OCD',
        'description' => 'Stuck in repeating thoughts or rituals?',
        'url' => '/tests/ocd-standalone/',
        'bg_color' => '#9E9FF5',
        'text_color' => '#202527',
        'image' => get_template_directory_uri() . '/dist/images/states/ocd.svg'
    ],
    'burnout' => [
        'title' => 'Burnout',
        'description' => 'Exhausted from constant pressure or overwork?',
        'url' => '/tests/burnout-standalone/',
        'bg_color' => '#FFDEA6',
        'text_color' => '#202527',
        'image' => get_template_directory_uri() . '/dist/images/states/burnout.svg'
    ]
];

// Normalize test ID for lookup
$test_key = strtolower(str_replace([' ', '-', '_'], '', $test_id));

// Don't output anything if test ID doesn't exist
if (!isset($test_configs[$test_key])) {
    return;
}

$test_config = $test_configs[$test_key];

// Allow custom overrides
$title = isset($args['custom_title']) ? $args['custom_title'] : 
         (isset($args['test_title']) ? $args['test_title'] : $test_config['title']);
$description = isset($args['custom_description']) ? $args['custom_description'] : $test_config['description'];
$url = isset($args['custom_url']) ? $args['custom_url'] : $test_config['url'];
$bg_color = isset($args['custom_bg_color']) ? $args['custom_bg_color'] : $test_config['bg_color'];
$text_color = isset($args['custom_text_color']) ? $args['custom_text_color'] : $test_config['text_color'];
$image = isset($args['custom_image']) ? $args['custom_image'] : $test_config['image'];
?>

<div class="banner banner--test" style="background-color: <?= esc_attr($bg_color); ?>; color: <?= esc_attr($text_color); ?>;">
    <div class="banner__content">
        <h5 class="banner__title" style="color: <?= esc_attr($text_color); ?>;">
            <?= esc_html($title); ?>
        </h5>
        <p class="banner__description" style="color: <?= esc_attr($text_color); ?>;">
            <?= esc_html($description); ?>
        </p>
        <img class="banner__image" src="<?= esc_url($image); ?>" alt="<?= esc_attr($title); ?>">
        <a href="<?= esc_url($url); ?>" class="banner__button text-btn" style="color: <?= esc_attr($text_color); ?>;">
            Start 3 min test →
        </a>
    </div>
</div> 