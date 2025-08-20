<?php
/**
 * ACF Fields for Header CTA
 * Custom header CTA button fields for posts, pages, and providers
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('acf/include_fields', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_header_cta_fields',
        'title' => 'Header CTA Button',
        'name' => 'header_cta_fields',
        'fields' => array(
            array(
                'key' => 'field_header_cta_text',
                'label' => 'Button Text',
                'name' => 'header_cta_text',
                'type' => 'text',
                'instructions' => 'Enter the text for the header CTA button.',
                'required' => 0,
                'default_value' => '',
                'placeholder' => 'Start Test',
                'wrapper' => array(
                    'width' => '100',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_header_cta_link',
                'label' => 'Button Link',
                'name' => 'header_cta_link',
                'type' => 'url',
                'instructions' => 'Enter the URL for the header CTA button.',
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
                    'value' => 'post',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'provider',
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
        'description' => 'Custom header CTA button that overrides the default header button when both text and link are provided.',
        'show_in_rest' => 0,
    ));
});
