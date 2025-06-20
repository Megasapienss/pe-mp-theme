<?php
/**
 * Theme Options ACF Fields
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add ACF Options Page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Theme Options',
        'menu_title' => 'Theme Options',
        'menu_slug' => 'theme-options',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

// Register ACF Fields for Theme Options
if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
        'key' => 'group_theme_options',
        'title' => 'Theme Options',
        'fields' => array(
            array(
                'key' => 'field_quiz_link',
                'label' => 'Quiz Link',
                'name' => 'quiz_link',
                'type' => 'url',
                'instructions' => 'Enter the default URL for the quiz banner. This will be used if no specific link is provided.',
                'required' => 0,
                'default_value' => '#',
                'placeholder' => 'https://example.com/quiz',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'theme-options',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
} 