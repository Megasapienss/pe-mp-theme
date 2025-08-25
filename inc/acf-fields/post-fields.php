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
            array(
                'key' => 'field_post_enable_providers_block',
                'label' => 'Enable Providers Block',
                'name' => 'enable_providers_block',
                'type' => 'true_false',
                'instructions' => 'Enable the providers block at the bottom of this post.',
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Enabled',
                'ui_off_text' => 'Disabled',
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
            ),
            // Medical Review Section
            array(
                'key' => 'field_post_medical_review_section',
                'label' => 'Medical Review',
                'name' => 'medical_review_section',
                'type' => 'group',
                'instructions' => 'Configure medical review information for this post.',
                'required' => 0,
                'layout' => 'block',
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_post_medical_reviewer',
                        'label' => 'Medical Reviewer',
                        'name' => 'medical_reviewer',
                        'type' => 'post_object',
                        'instructions' => 'Select a practitioner to review this article. Only providers with "Practitioner" type will be shown.',
                        'required' => 0,
                        'post_type' => array('provider'),
                        'multiple' => 0,
                        'return_format' => 'id',
                        'ui' => 1,
                        'allow_null' => 1,
                        'wrapper' => array(
                            'width' => '100',
                        ),
                    ),
                    array(
                        'key' => 'field_post_review_date',
                        'label' => 'Review Date',
                        'name' => 'review_date',
                        'type' => 'date_picker',
                        'instructions' => 'Date when this article was medically reviewed.',
                        'required' => 0,
                        'display_format' => 'd/m/Y',
                        'return_format' => 'Y-m-d',
                        'first_day' => 1,
                        'wrapper' => array(
                            'width' => '100',
                        ),
                    ),
                    array(
                        'key' => 'field_post_review_quote',
                        'label' => 'Review Quote',
                        'name' => 'review_quote',
                        'type' => 'textarea',
                        'instructions' => 'Short quote from the reviewer about this article (1-2 sentences).',
                        'required' => 0,
                        'rows' => 3,
                        'maxlength' => 200,
                        'wrapper' => array(
                            'width' => '',
                        ),
                    ),
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
        'position' => 'side',
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

/**
 * Filter the post_object field to only show practitioners for medical review
 * 
 * @param array $args Query arguments for the post_object field
 * @param array $field The ACF field array
 * @return array Modified query arguments
 */
function pe_mp_filter_practitioners_for_medical_review($args, $field) {
    // Only apply this filter to our medical reviewer field
    if ($field['name'] === 'medical_reviewer') {
        // Get the "Practitioner" term from provider-type taxonomy
        $practitioner_term = get_term_by('slug', 'practitioner', 'provider-type');
        
        if ($practitioner_term) {
            // Get all providers with "Practitioner" type AND reviewer functionality enabled
            $practitioners = get_posts(array(
                'post_type' => 'provider',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'enable_reviewer_functionality',
                        'value' => '1',
                        'compare' => '=',
                    ),
                ),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'provider-type',
                        'field' => 'term_id',
                        'terms' => $practitioner_term->term_id,
                    ),
                ),
                'post_status' => 'publish',
            ));
            
            // Create an array of provider IDs to include
            $include_providers = array();
            foreach ($practitioners as $provider) {
                $include_providers[] = $provider->ID;
            }
            
            // If we found practitioners, modify the query to only include them
            if (!empty($include_providers)) {
                $args['post__in'] = $include_providers;
            } else {
                // If no practitioners found, return empty result
                $args['post__in'] = array(0);
            }
        } else {
            // If "Practitioner" term doesn't exist, return empty result
            $args['post__in'] = array(0);
        }
    }
    
    return $args;
}
add_filter('acf/fields/post_object/query', 'pe_mp_filter_practitioners_for_medical_review', 10, 2); 