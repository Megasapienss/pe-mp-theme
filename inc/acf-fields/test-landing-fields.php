<?php
/**
 * ACF Fields for Test Landing Page Template
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('acf/include_fields', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_test_landing_fields',
        'title' => 'Test Landing Page Fields',
        'name' => 'test_landing_fields',
        'fields' => array(
            array(
                'key' => 'field_test_landing_disclaimer',
                'label' => 'Disclaimer',
                'name' => 'disclaimer',
                'type' => 'textarea',
                'instructions' => 'Enter any disclaimers or important information for this test.',
                'required' => 0,
                'default_value' => '',
                'placeholder' => 'Enter disclaimer text...',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => 'br',
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_test_landing_faq',
                'label' => 'FAQ',
                'name' => 'faq',
                'type' => 'repeater',
                'instructions' => 'Add frequently asked questions and answers.',
                'required' => 0,
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => 'Add FAQ Item',
                'collapsed' => 'field_faq_question',
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_faq_question',
                        'label' => 'Question',
                        'name' => 'question',
                        'type' => 'text',
                        'instructions' => 'Enter the FAQ question.',
                        'required' => 1,
                        'default_value' => '',
                        'placeholder' => 'Enter question...',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                    array(
                        'key' => 'field_faq_answer',
                        'label' => 'Answer',
                        'name' => 'answer',
                        'type' => 'textarea',
                        'instructions' => 'Enter the answer to the FAQ question.',
                        'required' => 1,
                        'default_value' => '',
                        'placeholder' => 'Enter answer...',
                        'maxlength' => '',
                        'rows' => 3,
                        'new_lines' => 'br',
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_test_landing_related_test',
                'label' => 'Related Test',
                'name' => 'related_test',
                'type' => 'page_link',
                'instructions' => 'Select a related test page. Only pages with the parent "Tests" page will be shown.',
                'required' => 0,
                'allow_null' => 1,
                'multiple' => 0,
                'post_type' => array(
                    0 => 'page',
                ),
                'taxonomy' => '',
                'allow_archives' => 0,
                'return_format' => 'id',
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-templates/test-landing.php',
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
        'show_in_rest' => 0,
    ));
});

/**
 * Filter the page_link field to only show pages with parent "Tests"
 * 
 * @param array $args Query arguments for the page_link field
 * @param array $field The ACF field array
 * @return array Modified query arguments
 */
function pe_mp_filter_test_pages_for_test_landing($args, $field) {
    // Only apply this filter to our specific field
    if ($field['name'] === 'related_test') {
        // Find the "Tests" page by slug
        $tests_page = get_page_by_path('tests');
        
        if ($tests_page) {
            // Get all child pages of the "Tests" page
            $test_pages = get_pages(array(
                'parent' => $tests_page->ID,
                'sort_column' => 'menu_order,post_title',
                'hierarchical' => 0,
            ));
            
            // Create an array of page IDs to include
            $include_pages = array();
            foreach ($test_pages as $page) {
                $include_pages[] = $page->ID;
            }
            
            // If we found test pages, modify the query to only include them
            if (!empty($include_pages)) {
                $args['post__in'] = $include_pages;
            } else {
                // If no test pages found, return empty result
                $args['post__in'] = array(0);
            }
        } else {
            // If "Tests" page doesn't exist, return empty result
            $args['post__in'] = array(0);
        }
    }
    
    return $args;
}
add_filter('acf/fields/page_link/query', 'pe_mp_filter_test_pages_for_test_landing', 10, 2);
