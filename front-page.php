<?php

/**
 * The template for displaying post archives
 * 
 * @package PE_MP_Theme
 */

get_header();
?>

<section class="hero hero--banner hero--xl" style="background-image: url('<?= get_template_directory_uri(); ?>/dist/images/cover.jpg');">
    <h1 class="hero__title title-lg">
        Heal your mind & health will follow
    </h1>
    <h2 class="hero__description">
        Discover the latest mental health breakthroughs, advanced diagnostics, and the therapies leading tomorrow's care.
    </h2>
    <a href="/tests/assessment/" class="arrow-btn">
        <?php esc_html_e('Start 3 min test', 'pe-mp-theme'); ?>
    </a>
</section>


<section class="container about-section">
    <div class="label label--arrow">
        About Us
    </div>
    <h2>
        We are shaping a new scientific approach to treating mental disorders and supporting psychological well-being with psychedelics.
        Explore our guides, tools, specialists, and curated knowledge base.
    </h2>
</section>

<?php
get_template_part('template-parts/sections/articles', 'latest');
?>

<?php
// Get the "Science & Innovation" category and its subcategories
$science_innovation_category = get_term_by('name', 'Science & Innovation', 'category');

if ($science_innovation_category && !is_wp_error($science_innovation_category)) {
    // Get child categories (subcategories) of Science & Innovation
    $science_innovation_subcategories = get_terms(array(
        'taxonomy' => 'category',
        'parent' => $science_innovation_category->term_id,
        'hide_empty' => true
    ));

    // Only display if there are subcategories
    if (!empty($science_innovation_subcategories) && !is_wp_error($science_innovation_subcategories)) {
        get_template_part('template-parts/sections/topics', null, array(
            'custom_categories' => $science_innovation_subcategories,
            'section_title' => 'Science & Innovation',
            'view_all_link' => get_term_link($science_innovation_category)
        ));
    }
} else {
    // Fallback to default topics if Science & Innovation category doesn't exist
    get_template_part('template-parts/sections/topics', null, array(
        'section_title' => 'Science & Innovation'
    ));
}
?>

<?php
// Get the "Mental Wellness" category and its subcategories
$mental_wellness_category = get_term_by('name', 'Mental Wellness', 'category');

if ($mental_wellness_category && !is_wp_error($mental_wellness_category)) {
    // Get child categories (subcategories) of Mental Wellness
    $mental_wellness_subcategories = get_terms(array(
        'taxonomy' => 'category',
        'parent' => $mental_wellness_category->term_id,
        'hide_empty' => true
    ));

    // Only display if there are subcategories
    if (!empty($mental_wellness_subcategories) && !is_wp_error($mental_wellness_subcategories)) {
        get_template_part('template-parts/sections/topics', null, array(
            'custom_categories' => $mental_wellness_subcategories,
            'section_title' => 'Mental Wellness',
            'view_all_link' => get_term_link($mental_wellness_category)
        ));
    }
} else {
    // Fallback to default topics if Mental Wellness category doesn't exist
    get_template_part('template-parts/sections/topics', null, array(
        'section_title' => 'Mental Wellness'
    ));
}
?>

<?php
get_template_part('template-parts/sections/newsletter');
?>

<?php
get_template_part('template-parts/sections/articles', 'top');
?>

<?php
//get_template_part('template-parts/sections/experts'); 
?>

<?php
//get_template_part('template-parts/sections/clinics'); 
?>

<?php
//get_template_part('template-parts/sections/apps'); 
?>



<?php
get_footer();
?>