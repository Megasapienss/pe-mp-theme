<?php
/**
 * ACF Fields for Verification Post Type
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
        'key' => 'group_verification_details',
        'title' => 'Verification Details',
        'fields' => array(
            array(
                'key' => 'field_verification_provider',
                'label' => 'Provider',
                'name' => 'provider',
                'type' => 'relationship',
                'post_type' => array('provider'),
                'multiple' => 0,
                'return_format' => 'id',
                'required' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'verification',
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
