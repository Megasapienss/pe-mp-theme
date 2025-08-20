<?php

/**
 * Template part for displaying provider-based mosaic sections
 *
 * @param string $args['title'] Section title
 * @param string $args['taxonomy'] Taxonomy name (e.g., 'provider-type', 'service-delivery-method')
 * @param array $args['terms'] Array of term slugs (can be single item or multiple)
 * @param int $args['count'] Number of providers to display (default: 6)
 * @param string $args['category'] Category slug or name (deprecated, use taxonomy + term instead)
 * @param array $args['exclude_posts'] Array of post IDs to exclude from query
 *
 * @package PE_MP_Theme
 */

$title = isset($args['title']) ? $args['title'] : 'Providers';
$taxonomy = isset($args['taxonomy']) ? $args['taxonomy'] : '';
$terms = isset($args['terms']) ? $args['terms'] : array();
$count = isset($args['count']) ? intval($args['count']) : 3;
$exclude_posts = isset($args['exclude_posts']) ? $args['exclude_posts'] : array();

// Backward compatibility for existing usage
if (!$taxonomy && empty($terms) && isset($args['category']) && $args['category']) {
    $taxonomy = 'category';
    $terms = array($args['category']);
}

// Query providers by taxonomy and term
$query_args = array(
    'post_type' => 'provider',
    'posts_per_page' => $count,
    'post__not_in' => array_merge(array(get_the_ID()), $exclude_posts),
    'orderby' => 'date',
    'order' => 'DESC'
);

// Add taxonomy query if taxonomy and terms are provided
if ($taxonomy && !empty($terms)) {
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => $taxonomy,
            'field' => 'slug',
            'terms' => $terms,
            'operator' => 'IN'
        )
    );
}

$providers_query = new WP_Query($query_args);

// Don't output anything if there are no providers
if (!$providers_query->have_posts()) {
    return;
}

$providers_array = $providers_query->posts;

// Update global displayed post IDs
global $displayed_post_ids;
if (!isset($displayed_post_ids) || !is_array($displayed_post_ids)) {
    $displayed_post_ids = array();
}
$displayed_post_ids = array_merge($displayed_post_ids, wp_list_pluck($providers_array, 'ID'));
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