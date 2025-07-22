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

<section class="archive-title container">
    <?php
    get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
        'class' => 'breadcrumbs breadcrumbs--dark archive-title__breadcrumbs'
    ));
    ?>
    <h1 class="archive-title__name title-lg"><?= get_field('h1', $term) ?: $term->name; ?></h1>
    <p class="archive-title__description heading-h2"><?= $term->description; ?></p>
</section>

<?php
// Quiz Cards Section - Only show for diagnostics category
if ($term && !is_wp_error($term) && $term->slug === 'diagnostics') :
    $quiz_categories = array(
        array(
            'name' => 'Depression',
            'description' => 'This assessment will help us understand your depression symptoms.',
            'link' => '/tests/depression/',
            'image' => get_template_directory_uri() . '/dist/images/states/depression.png'
        ),
        array(
            'name' => 'Anxiety',
            'description' => 'This assessment will help us understand your anxiety symptoms.',
            'link' => '/tests/anxiety/',
            'image' => get_template_directory_uri() . '/dist/images/states/anxiety.png'
        ),
        array(
            'name' => 'PTSD',
            'description' => 'This assessment will help us understand your PTSD symptoms.',
            'link' => '/tests/ptsd/',
            'image' => get_template_directory_uri() . '/dist/images/states/ptsd.png'
        ),
        array(
            'name' => 'ADHD',
            'description' => 'This assessment will help us understand your ADHD symptoms.',
            'link' => '/tests/adhd/',
            'image' => get_template_directory_uri() . '/dist/images/states/adhd.png'
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

<?php
// Get child categories of the current term
$child_categories = get_terms(array(
    'taxonomy' => $term->taxonomy,
    'parent' => $term->term_id,
    'hide_empty' => true
));

// Display child categories if they exist
if (!empty($child_categories) && !is_wp_error($child_categories)) :
    get_template_part('template-parts/sections/topics', null, array(
        'custom_categories' => $child_categories,
        'section_title' => ''
    ));
endif;
?>

<section class="archive-grid grid grid--2 container container--wide">
    <?php if (have_posts()) {
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/cards/post', 'curved', ['post' => get_post()]);
        }
        // If this is the middle post (when total is odd), insert newsletter banner
        if ($post_count % 2 !== 0) {
            get_template_part('template-parts/banners/newsletter');
        }
    } else { ?>
        <p class="text-center"><?php _e('No posts found.', 'pe-mp-theme'); ?></p>
        <p class="text-center"><?php _e('Come back later!', 'pe-mp-theme'); ?></p>
    <?php } ?>
</section>

<?php
if ($post_count % 2 == 0) {
    get_template_part('template-parts/sections/newsletter');
}
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
get_template_part('template-parts/sections/articles', 'top');
?>

<?php
get_footer();
?>