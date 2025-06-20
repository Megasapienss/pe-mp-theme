<?php
/**
 * Template part for displaying the latest articles section
 *
 * @package PE_MP_Theme
 */

// Get the current post's categories
$categories = get_the_category();
$category_ids = array();
foreach ($categories as $category) {
    $category_ids[] = $category->term_id;
}

// Query related posts
$related_posts = new WP_Query(array(
    'category__in' => $category_ids,
    // 'post__not_in' => array(get_the_ID()),
    'posts_per_page' => 3,
    'orderby' => 'rand'
));

// Don't output anything if there are no posts
if (!$related_posts->have_posts()) {
    return;
}
?>

<section class="section">
    <div class="section__title">
        <h2 class="section__title-text">Latest Articles</h2>
        <a href="#" class="section__title-link arrow-btn arrow-btn--muted">View all</a>
    </div>
    <div class="container container--wide cards grid grid--3">
        <?php
        // Display related posts
        foreach ($related_posts->posts as $post) :
            get_template_part('template-parts/cards/post', 'compact', ['post' => $post]);
        endforeach;
        ?>
    </div>
</section> 