<?php

/**
 * Template Name: Home V2
 * 
 * The template for displaying the home page version 2
 * 
 * @package PE_MP_Theme
 */

get_header();
?>

<!-- <section class="hero hero--minimalistic">
    <h1 class="hero__title">
        Heal your mind & health will follow
    </h1>
    <p class="hero__description heading-h4">
        Discover the latest mental health breakthroughs, advanced diagnostics, and the therapies leading tomorrow's care.
    </p>
</section> -->

<?php
// Hero Mosaic Section
get_template_part('template-parts/mosaics/hero', '');

// Science & Innovation Section
get_template_part('template-parts/mosaics/category', '', [
    'title' => 'Science & Innovation',
    'taxonomy' => 'category',
    'term' => 'science-innovation'
]);

// Discover safe psychedelic care in Europe and Asia Section
get_template_part('template-parts/mosaics/category', '', [
    'title' => 'Discover safe psychedelic care in Europe and Asia',
    'taxonomy' => 'post_tag',
    'term' => 'providers',
    'count' => 3
]);

get_template_part('template-parts/sections/tests');

// Mental Wellness Section
get_template_part('template-parts/mosaics/category', '', [
    'title' => 'Mental Wellness',
    'taxonomy' => 'category',
    'term' => 'mental-wellness'
]);

// New articles Section
get_template_part('template-parts/mosaics/recommendations');
?>

<?php
get_footer();
?>