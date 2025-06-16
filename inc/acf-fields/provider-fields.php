<?php
/**
 * ACF Fields for Provider Post Type
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
        'key' => 'group_provider_details',
        'title' => 'Provider Details',
        'fields' => array(
            array(
                'key' => 'field_address',
                'label' => 'Address',
                'name' => 'address',
                'type' => 'textarea',
                'rows' => 3,
                'required' => 0,
            ),
            array(
                'key' => 'field_latitude',
                'label' => 'Latitude',
                'name' => 'latitude',
                'type' => 'number',
                'required' => 0,
            ),
            array(
                'key' => 'field_longitude',
                'label' => 'Longitude',
                'name' => 'longitude',
                'type' => 'number',
                'required' => 0,
            ),
            array(
                'key' => 'field_short_description',
                'label' => 'Short Description',
                'name' => 'short_description',
                'type' => 'textarea',
                'rows' => 3,
                'required' => 0,
            ),
            array(
                'key' => 'field_full_description',
                'label' => 'Full Description',
                'name' => 'full_description',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'required' => 0,
            ),
            array(
                'key' => 'field_rating',
                'label' => 'Rating',
                'name' => 'rating',
                'type' => 'number',
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'required' => 0,
            ),
            array(
                'key' => 'field_reviews_count',
                'label' => 'Reviews Count',
                'name' => 'reviews_count',
                'type' => 'number',
                'min' => 0,
                'required' => 0,
            ),
            array(
                'key' => 'field_booking_url',
                'label' => 'Booking URL',
                'name' => 'booking_url',
                'type' => 'url',
                'required' => 0,
            ),
            array(
                'key' => 'field_image_url',
                'label' => 'Image URL',
                'name' => 'image_url',
                'type' => 'url',
                'required' => 0,
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
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 1,
    ));
} 