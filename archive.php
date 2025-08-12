<?php

/**
 * The template for displaying post archives
 * 
 * @package PE_MP_Theme
 */

get_header();
$term = get_queried_object();
$post_count = $wp_query->found_posts;
?>

<section class="archive-title-v2 container">
    <?php
    get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
        'class' => 'breadcrumbs breadcrumbs--dark archive-title__breadcrumbs'
    ));
    ?>
    <h1 class="archive-title-v2__name"><?= get_field('h1', $term) ?: $term->name; ?></h1>
    <p class="archive-title-v2__description"><?= $term->description; ?></p>
</section>

<?php
// Quiz Cards Section - Only show for diagnostics category
if (false) :
    $quiz_categories = array(
        array(
            'name' => 'Depression',
            'description' => 'Explore how depression may be affecting your daily life.',
            'link' => '/tests/depression-standalone/',
            'image' => get_template_directory_uri() . '/dist/images/states/depression.webp'
        ),
        array(
            'name' => 'Anxiety',
            'description' => 'Gain insight into your experience with anxiety.',
            'link' => '/tests/anxiety-standalone/',
            'image' => get_template_directory_uri() . '/dist/images/states/anxiety.webp'
        ),
        array(
            'name' => 'PTSD',
            'description' => 'Discover how trauma may be impacting your wellbeing.',
            'link' => '/tests/ptsd-standalone/',
            'image' => get_template_directory_uri() . '/dist/images/states/ptsd.webp'
        ),
        array(
            'name' => 'ADHD',
            'description' => 'Find out if ADHD traits are present in your life.',
            'link' => '/tests/adhd-standalone/',
            'image' => get_template_directory_uri() . '/dist/images/states/adhd.webp'
        )
    );
?>
    <section class="section tests-section" id="tests">
        <div class="cards grid grid--2 container container--wide">
            <?php foreach ($quiz_categories as $quiz) : ?>
                <a href="<?php echo esc_url($quiz['link']); ?>" class="card card--topic">
                    <img src="<?php echo esc_url($quiz['image']); ?>" alt="<?php echo esc_attr($quiz['name']); ?>" class="card__image">
                    <div class="d-flex flex-column">
                        <h3 class="card__title">
                            <?php echo esc_html($quiz['name']); ?>
                        </h3>
                        <div class="card__excerpt">
                            <?php echo esc_html($quiz['description']); ?>
                        </div>
                        <div class="card__tags tags">
                            <span class="label label--squared label--primary-inversed">Takes only 3 minutes</span>
                            <span class="label label--squared label--primary-inversed">Free</span>
                        </div>
                    </div>
                    <div class="card__corner corner corner--right-top">
                        <img src="<?= get_template_directory_uri(); ?>/dist/icons/badge-arrow-45.svg" alt="">
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<?php if ($term && !is_wp_error($term) && $term->slug === 'diagnostics') : ?>
    <section class="section-v2 container grid grid--3">
    <?php
        $test_ids = [
            'anxiety',
            'depression',
            'adhd',
            'ptsd',
            // 'ed',
            // 'ocd',
            'burnout'
        ];
        foreach ($test_ids as $test_id){
            get_template_part('template-parts/banners/test', '', [
                'test_id' => $test_id
            ]); 
        }
    ?>
    </section>
<?php endif; ?>


<?php
// Get child categories of the current term
$child_categories = get_terms(array(
    'taxonomy' => $term->taxonomy,
    'parent' => $term->term_id,
    'hide_empty' => true
));

// If there are subcategories, display a mosaic for each subcategory
if (!empty($child_categories) && !is_wp_error($child_categories)) :
    foreach ($child_categories as $child_category) :
        get_template_part('template-parts/mosaics/category', null, array(
            'title' => $child_category->name,
            'taxonomy' => $child_category->taxonomy,
            'term' => $child_category->slug
        ));
    endforeach;
else :
    // If no subcategories, display the current archive-grid
?>
    <section class="archive-grid mosaic mosaic--1-1 container container--wide">
        <?php if (have_posts()) {
            while (have_posts()) {
                the_post();
                ?>
                <div class="mosaic__item">
                    <?php get_template_part('template-parts/cards/post', 'v2', ['post' => get_post(), 'size' => 'large']); ?>
                </div>
                <?php
            }
            // If this is the middle post (when total is odd), insert newsletter banner
            // if ($post_count % 2 !== 0) {
            //     get_template_part('template-parts/banners/newsletter');
            // }
        } else { ?>
            <!-- <p class="text-center"><?php _e('No posts found.', 'pe-mp-theme'); ?></p>
            <p class="text-center"><?php _e('Come back later!', 'pe-mp-theme'); ?></p> -->
        <?php } ?>
    </section>
<?php endif; ?>

<?php
// if ($post_count % 2 == 0) {
//     get_template_part('template-parts/sections/newsletter');
// }
?>

<?php
//get_template_part('template-parts/sections/apps'); 
?>

<?php
//get_template_part('template-parts/sections/experts'); 
?>

<?php
//get_template_part('template-parts/sections/clinics'); 
?>

<?php
// get_template_part('template-parts/sections/articles', 'top');
?>

<?php
get_footer();
?>