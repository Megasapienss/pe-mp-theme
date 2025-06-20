<?php

/**
 * The template for displaying post archives
 * 
 * @package PE_MP_Theme
 */

get_header();
$term = get_queried_object();
?>

<section class="archive-title container">
    <div class="breadcrumbs breadcrumbs--dark archive-title__breadcrumbs">
        <a href="<?= home_url(); ?>" class="breadcrumbs__link">Home</a>
        <span class="breadcrumbs__separator">/</span>
        <a href="<?= home_url(); ?>" class="breadcrumbs__link">Explore</a>
        <span class="breadcrumbs__separator">/</span>
        <span><?= $term->name; ?></span>

    </div>
    <h1 class="archive-title__name title-lg"><?= get_field('h1', $term) ?: $term->name; ?></h1>
    <p class="archive-title__description heading-h2"><?= $term->description; ?></p>
</section>

<?php
// Get child categories of the current term
$child_categories = get_terms(array(
    'taxonomy' => 'category',
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

<section class="archive-grid grid grid--2 container">
    <?php if (have_posts()): ?>
        <?php
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/cards/post', 'curved', ['post' => get_post()]);
        }
        ?>

    <?php else: ?>
        <p class="text-center"><?php _e('No posts found.', 'pe-mp-theme'); ?></p>
        <p class="text-center"><?php _e('Come back later!', 'pe-mp-theme'); ?></p>
    <?php endif; ?>
</section>

<?php //get_template_part('template-parts/sections/apps'); 
?>

<?php //get_template_part('template-parts/sections/experts'); 
?>

<section class="section">
    <div class="section__title">
        <h2 class="section__title-text">Guided Retreats & Clinics</h2>
        <a href="/providers/" class="section__title-link arrow-btn arrow-btn--muted">Find More Clinics</a>
    </div>
    <div class="providers-section">
        <div class="banner" style="background-image: url('<?= get_template_directory_uri(); ?>/dist/images/banner-default.jpg');">
            <h2 class="banner__title title-lg">Integration Journey</h2>
            <p class="banner__description body-lg">
                Your path to healing begins with safe, supported experiences. Explore retreat centers that focus on reconnection, grounding and long-term transformation.
            </p>
            <a href="/providers/" class="banner__link arrow-btn arrow-btn--muted">
                View All Retreats
            </a>
        </div>
        <div class="providers-section__list">
            <div class="card card--provider">
                <h3 class="card__title">
                    Healing Forest (Portugal)
                </h3>
                <div class="card__excerpt">
                    This forest retreat combines psilocybin-assisted integration, bodywork and silent reflection in lush natural surroundings.
                </div>
                <div class="card__tags tags">
                    <h6 class="tags__title">Program Milestones</h6>
                    <span class="label label--squared label--muted">
                        Guided microdosing protocol
                    </span>
                    <span class="label label--squared label--muted">
                        Somatic sessions & breathwork
                    </span>
                    <span class="label label--squared label--muted">
                        Shared integration circles
                    </span>
                </div>
                <div class="card__corner corner corner--right-bottom">
                    <a href="#" class="arrow-btn arrow-btn--primary">
                        Book Consultation
                    </a>
                </div>
            </div>
            <div class="card card--provider">
                <h3 class="card__title">
                    Soma Mind Clinic (Netherlands)
                </h3>
                <div class="card__excerpt">
                    An urban outpatient clinic focused on mental health recovery through structured psilocybin sessions and psychotherapy. </div>
                <div class="card__tags tags">
                    <h6 class="tags__title">Program Milestones</h6>
                    <span class="label label--squared label--muted">
                        Legal therapeutic sessions
                    </span>
                    <span class="label label--squared label--muted">
                        Trauma-informed coaching
                    </span>
                    <span class="label label--squared label--muted">
                        Ongoing psychiatric check-ins
                    </span>
                </div>
                <div class="card__corner corner corner--right-bottom">
                    <a href="#" class="arrow-btn arrow-btn--primary">
                        Book Consultation
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="section">
    <div class="section__title">
        <h2 class="section__title-text">Continue Your Exploration</h2>
        <a href="#" class="section__title-link arrow-btn arrow-btn--muted">View all</a>
    </div>
    <div class="container container--wide cards grid grid--3">
        <?php
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

        // Display related posts
        if ($related_posts->have_posts()) :
            foreach ($related_posts->posts as $post) :
                get_template_part('template-parts/cards/post', 'compact', ['post' => $post]);
            endforeach;
        else :
            echo '<p>' . __('No related posts found.', 'pe-mp-theme') . '</p>';
        endif;
        ?>
    </div>
</section>

<?php
get_footer();
?>