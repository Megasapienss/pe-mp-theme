<?php

/**
 * Template part for displaying provider-based mosaic sections
 *
 * @param string $args['title'] Section title
 * @param string $args['taxonomy'] Taxonomy name (e.g., 'provider-type', 'service-delivery-method')
 * @param array $args['terms'] Array of term slugs (can be single item or multiple)
 * @param int $args['count'] Number of providers to display (default: 6)
 *
 * @package PE_MP_Theme
 */

$title = isset($args['title']) ? $args['title'] : 'Providers';
$taxonomy = isset($args['taxonomy']) ? $args['taxonomy'] : '';
$terms = isset($args['terms']) ? $args['terms'] : array();
$count = isset($args['count']) ? intval($args['count']) : 3;

// Query providers by taxonomy and term
$query_args = array(
    'post_type' => 'provider',
    'posts_per_page' => $count,
    'orderby' => 'date',
    'order' => 'DESC'
);

// Build taxonomy query array
$tax_queries = array();

// Add taxonomy query if taxonomy and terms are provided
if ($taxonomy && !empty($terms)) {
    $tax_queries[] = array(
        'taxonomy' => $taxonomy,
        'field' => 'slug',
        'terms' => $terms,
        'operator' => 'IN'
    );
}

// Always exclude practitioner provider-type
$tax_queries[] = array(
    'taxonomy' => 'provider-type',
    'field' => 'slug',
    'terms' => 'practitioner',
    'operator' => 'NOT IN'
);

// Add tax_query if we have any queries
if (!empty($tax_queries)) {
    $query_args['tax_query'] = array(
        'relation' => 'AND',
        $tax_queries
    );
}

$providers_array = get_posts($query_args);

// If we don't have enough providers, fill with random providers
if (count($providers_array) < $count) {
    $remaining_slots = $count - count($providers_array);
    
    // Get random providers to fill remaining slots
    $random_query_args = array(
        'post_type' => 'provider',
        'posts_per_page' => $remaining_slots,
        'post__not_in' => wp_list_pluck($providers_array, 'ID'),
        'orderby' => 'rand',
        'post_status' => 'publish'
    );
    
    // Only exclude practitioner provider-type from random providers
    $random_query_args['tax_query'] = array(
        array(
            'taxonomy' => 'provider-type',
            'field' => 'slug',
            'terms' => 'practitioner',
            'operator' => 'NOT IN'
        )
    );
    
    $random_providers = get_posts($random_query_args);
    
    if (!empty($random_providers)) {
        $providers_array = array_merge($providers_array, $random_providers);
    }
}

// Don't output anything if there are no providers
if (empty($providers_array)) {
    return;
}
?>

<section class="section-v2 container">
    <div class="section-v2__title">
        <h2><?= esc_html($title); ?></h2>
        <?php 
        if ($taxonomy && !empty($terms)) {
            $term_obj = get_term_by('slug', $terms[0], $taxonomy);
            if ($term_obj) : 
        ?>
        <a href="/providers/" class="btn btn--muted btn--arrow">
            See all
        </a>
        <?php 
            endif;
        }
        ?>
    </div>
    <div class="section-v2__content">
        <div class="mosaic mosaic--2-1-1">
            <?php if (!empty($providers_array)) : ?>
            <div class="mosaic__item">
                <?php 
                $main_provider = $providers_array[0];
                get_template_part('template-parts/cards/provider', '', [
                    'post' => $main_provider,
                    'size' => 'large',
                    'orientation' => 'vertical'
                ]); 
                ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($providers_array[1])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/provider', '', [
                    'post' => $providers_array[1],
                    'orientation' => 'vertical'
                ]); 
                ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($providers_array[2])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/provider', '', [
                    'post' => $providers_array[2],
                    'orientation' => 'vertical'
                ]); 
                ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section> 