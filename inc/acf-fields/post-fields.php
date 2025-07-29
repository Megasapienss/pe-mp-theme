<?php
/**
 * ACF Fields for Posts
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('acf/include_fields', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // Debug: Check if this function is being called
    error_log('ACF Post Fields: Registering field group');

    acf_add_local_field_group(array(
        'key' => 'group_post_fields',
        'title' => 'Post Fields',
        'name' => 'post_fields',
        'fields' => array(
            array(
                'key' => 'field_post_test_page',
                'label' => 'Related Test Page',
                'name' => 'related_test_page',
                'type' => 'page_link',
                'instructions' => 'Select a test page that is related to this post. Only pages with the parent "Tests" page will be shown.',
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
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
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
function pe_mp_filter_test_pages_for_acf($args, $field) {
    // Only apply this filter to our specific field
    if ($field['name'] === 'related_test_page') {
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
add_filter('acf/fields/page_link/query', 'pe_mp_filter_test_pages_for_acf', 10, 2); 