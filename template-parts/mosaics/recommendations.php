<?php

/**
 * Template part for displaying recommendations with fallback logic
 *
 * Priority order:
 * 1. Mentioned articles from provider (if post type is provider, up to count limit)
 * 2. Posts tagged "editorial pick" (fill remaining slots)
 * 3. Related articles (fill remaining slots)
 * 4. Latest articles (fill any remaining slots)
 *
 * Always ensures exactly 'count' number of articles are displayed
 *
 * @param string $args['title'] Section title
 * @param int $args['count'] Number of posts to display (default: 8)
 * @param array $args['exclude_posts'] Array of post IDs to exclude from query
 *
 * @package PE_MP_Theme
 */

$title = isset($args['title']) ? $args['title'] : 'Editorial Picks';
$count = isset($args['count']) ? intval($args['count']) : 8;
$exclude_posts = isset($args['exclude_posts']) ? $args['exclude_posts'] : array();

$posts_array = array();
$sources = array();

// First priority: Mentioned articles from provider (if current post type is provider)
if (get_post_type() === 'provider') {
    $mentioned_articles = get_field('mentioned_articles_relation');
    
    if ($mentioned_articles && is_array($mentioned_articles) && !empty($mentioned_articles)) {
        // Filter out current post and already displayed posts
        $excluded_posts = array_merge(array(get_the_ID()), $exclude_posts);
        $filtered_articles = array_diff($mentioned_articles, $excluded_posts);
        
        if (!empty($filtered_articles)) {
            // Get the actual post objects
            $mentioned_posts = get_posts(array(
                'post__in' => $filtered_articles,
                'post_type' => 'post',
                'posts_per_page' => $count,
                'orderby' => 'post__in', // Maintain the order from the field
                'post_status' => 'publish'
            ));
            
            if (!empty($mentioned_posts)) {
                $posts_array = array_merge($posts_array, $mentioned_posts);
                $sources = array_merge($sources, array_fill(0, count($mentioned_posts), 'mentioned'));
            }
        }
    }
}

// Second priority: Editorial picks (only if we don't have enough mentioned articles)
if (count($posts_array) < $count) {
    $remaining_slots = $count - count($posts_array);
    
    $editorial_posts = new WP_Query(array(
        'posts_per_page' => $remaining_slots,
        'post__not_in' => array_merge(array(get_the_ID()), $exclude_posts, wp_list_pluck($posts_array, 'ID')),
        'tag' => 'editorial-pick',
        'orderby' => 'date',
        'order' => 'DESC'
    ));

    if ($editorial_posts->have_posts()) {
        $editorial_array = $editorial_posts->posts;
        $posts_array = array_merge($posts_array, $editorial_array);
        $sources = array_merge($sources, array_fill(0, count($editorial_array), 'editorial-pick'));
    }
}

// If we don't have enough posts, fill with related articles using scoring system
if (count($posts_array) < $count) {
    $remaining_slots = $count - count($posts_array);
    
    // Get current post's categories and tags
    $current_categories = wp_get_post_categories(get_the_ID());
    $current_tags = wp_get_post_tags(get_the_ID());
    $current_tag_ids = wp_list_pluck($current_tags, 'term_id');
    
    // Get all potential related posts (both tag and category matches)
    $related_query_args = array(
        'posts_per_page' => -1, // Get all posts to score them
        'post__not_in' => array_merge(array(get_the_ID()), $exclude_posts, wp_list_pluck($posts_array, 'ID')),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    // Build tax query to get posts that match either tags OR categories
    $tax_query = array();
    
    if (!empty($current_categories)) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field' => 'term_id',
            'terms' => $current_categories
        );
    }
    
    if (!empty($current_tag_ids)) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field' => 'term_id',
            'terms' => $current_tag_ids
        );
    }
    
    if (!empty($tax_query)) {
        // If we have both categories and tags, use OR logic
        if (count($tax_query) > 1) {
            $related_query_args['tax_query'] = array(
                'relation' => 'OR',
                $tax_query
            );
        } else {
            // If only one taxonomy type, use it directly
            $related_query_args['tax_query'] = $tax_query;
        }
        
        $related_posts = new WP_Query($related_query_args);
        
        if ($related_posts->have_posts()) {
            $scored_posts = array();
            
            // Score each post based on matches
            foreach ($related_posts->posts as $post) {
                $score = 0;
                
                // Check for tag matches (+5 points per tag match)
                if (!empty($current_tag_ids)) {
                    $post_tags = wp_get_post_tags($post->ID);
                    $post_tag_ids = wp_list_pluck($post_tags, 'term_id');
                    $tag_matches = array_intersect($current_tag_ids, $post_tag_ids);
                    $score += count($tag_matches) * 5;
                }
                
                // Check for category matches (+2 points per category match)
                if (!empty($current_categories)) {
                    $post_categories = wp_get_post_categories($post->ID);
                    $category_matches = array_intersect($current_categories, $post_categories);
                    $score += count($category_matches) * 2;
                }
                
                // Only include posts with at least one match
                if ($score > 0) {
                    $scored_posts[] = array(
                        'post' => $post,
                        'score' => $score
                    );
                }
            }
            
            // Sort by score (highest first), then by date (newest first)
            usort($scored_posts, function($a, $b) {
                if ($a['score'] !== $b['score']) {
                    return $b['score'] - $a['score']; // Higher score first
                }
                return strtotime($b['post']->post_date) - strtotime($a['post']->post_date); // Newer first
            });
            
            // Take the top posts up to the remaining slots
            $top_posts = array_slice($scored_posts, 0, $remaining_slots);
            
            if (!empty($top_posts)) {
                $related_array = array_column($top_posts, 'post');
                $posts_array = array_merge($posts_array, $related_array);
                $sources = array_merge($sources, array_fill(0, count($related_array), 'related'));
            }
        }
    }
}

// If we still don't have enough posts, fill with latest articles
if (count($posts_array) < $count) {
    $remaining_slots = $count - count($posts_array);
    
    $latest_posts = new WP_Query(array(
        'posts_per_page' => $remaining_slots,
        'post__not_in' => array_merge(array(get_the_ID()), $exclude_posts, wp_list_pluck($posts_array, 'ID')),
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    if ($latest_posts->have_posts()) {
        $latest_array = $latest_posts->posts;
        $posts_array = array_merge($posts_array, $latest_array);
        $sources = array_merge($sources, array_fill(0, count($latest_array), 'latest'));
    }
}

// Don't output anything if there are no posts
if (empty($posts_array)) {
    return;
}

// Update global displayed post IDs
global $displayed_post_ids;
if (!isset($displayed_post_ids) || !is_array($displayed_post_ids)) {
    $displayed_post_ids = array();
}
$displayed_post_ids = array_merge($displayed_post_ids, wp_list_pluck($posts_array, 'ID'));

// Determine primary source for the button (prioritize mentioned, then editorial picks, then related, then latest)
$primary_source = 'latest';
if (in_array('mentioned', $sources)) {
    $primary_source = 'mentioned';
} elseif (in_array('editorial-pick', $sources)) {
    $primary_source = 'editorial-pick';
} elseif (in_array('related', $sources)) {
    $primary_source = 'related';
}
?>

<section class="section-v2 container">
    <div class="section-v2__title">
        <h2><?= esc_html($title); ?></h2>
        <?php if ($primary_source === 'editorial-pick') : ?>
        <!-- <a href="<?= esc_url(get_tag_link(get_term_by('slug', 'editorial-pick', 'post_tag'))); ?>" class="btn btn--muted btn--arrow">
            See all editorial picks
        </a> -->
        <?php elseif ($primary_source === 'mentioned') : ?>
        <!-- <a href="<?= esc_url(get_permalink()); ?>#mentioned" class="btn btn--muted btn--arrow">
            More from this provider
        </a> -->
        <?php elseif ($primary_source === 'related') : ?>
        <!-- <a href="<?= esc_url(get_permalink()); ?>#related" class="btn btn--muted btn--arrow">
            More like this
        </a> -->
        <?php else : ?>
        <!-- <a href="<?= esc_url(home_url('/')); ?>" class="btn btn--muted btn--arrow">
            Browse all articles
        </a> -->
        <?php endif; ?>
    </div>
    <div class="section-v2__content">
        <div class="mosaic mosaic--1-2-1">
            <?php if (isset($posts_array[0])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[0]
                ]); 
                ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($posts_array[1])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[1],
                    'size' => 'large'
                ]); 
                ?>
            </div>
            <?php endif; ?>
            
            <div class="mosaic__column">
                <?php if (isset($posts_array[2])) : ?>
                <div class="mosaic__item">
                    <?php 
                    get_template_part('template-parts/cards/post-v2', '', [
                        'post' => $posts_array[2],
                        'show_image' => false
                    ]); 
                    ?>
                </div>
                <?php endif; ?>
                
                <?php if (isset($posts_array[3])) : ?>
                <div class="mosaic__item">
                    <?php 
                    get_template_part('template-parts/cards/post-v2', '', [
                        'post' => $posts_array[3],
                        'show_image' => false
                    ]); 
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (count($posts_array) > 4) : ?>
        <div class="mosaic mosaic--2-1-1">
            <?php if (isset($posts_array[4])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[4],
                    'size' => 'large'
                ]); 
                ?>
            </div>
            <?php endif; ?>
            
            <div class="mosaic__column">
                <?php if (isset($posts_array[5])) : ?>
                <div class="mosaic__item">
                    <?php 
                    get_template_part('template-parts/cards/post-v2', '', [
                        'post' => $posts_array[5],
                        'show_image' => false
                    ]); 
                    ?>
                </div>
                <?php endif; ?>
                
                <?php if (isset($posts_array[6])) : ?>
                <div class="mosaic__item">
                    <?php 
                    get_template_part('template-parts/cards/post-v2', '', [
                        'post' => $posts_array[6],
                        'show_image' => false
                    ]); 
                    ?>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if (isset($posts_array[7])) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[7]
                ]); 
                ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if (count($posts_array) > 8) : ?>
        <div class="mosaic mosaic--1-1-1">
            <?php for ($i = 8; $i < min($count, count($posts_array)); $i++) : ?>
            <div class="mosaic__item">
                <?php 
                get_template_part('template-parts/cards/post-v2', '', [
                    'post' => $posts_array[$i],
                    'size' => 'small',
                    'show_excerpt' => false,
                    'show_author' => false
                ]); 
                ?>
            </div>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</section> 