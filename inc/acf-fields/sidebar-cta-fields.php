<?php
/**
 * ACF Fields for Sidebar CTA
 * Custom sidebar CTA banner fields for providers
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('acf/include_fields', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_sidebar_cta_fields',
        'title' => 'Sidebar CTA Banner',
        'name' => 'sidebar_cta_fields',
        'fields' => array(
            array(
                'key' => 'field_sidebar_cta_image',
                'label' => 'Sidebar Image',
                'name' => 'sidebar_cta_image',
                'type' => 'image',
                'instructions' => 'Upload an image for the sidebar CTA. This image will be clickable.',
                'required' => 0,
                'return_format' => 'url',
                'preview_size' => 'medium',
                'library' => 'all',
                'wrapper' => array(
                    'width' => '100',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_sidebar_cta_link',
                'label' => 'Image Link',
                'name' => 'sidebar_cta_link',
                'type' => 'url',
                'instructions' => 'Enter the URL that the image will link to when clicked.',
                'required' => 0,
                'default_value' => '',
                'placeholder' => 'https://example.com',
                'wrapper' => array(
                    'width' => '100',
                    'class' => '',
                    'id' => '',
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'provider',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => 'Custom sidebar CTA banner that overrides the default sidebar test when both text and link are provided.',
        'show_in_rest' => 0,
    ));
});
