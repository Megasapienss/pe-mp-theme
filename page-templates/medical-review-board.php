<?php

/**
 * Template Name: Medical Review Board
 *
 * @package PE_MP_Theme
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

?>

<section class="single-title-v2 container">
    <?php
    get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
        'class' => 'breadcrumbs breadcrumbs--dark'
    ));
    ?>
</section>
<div class="grid grid--2 container">
    <div class="column gap-2">
        <h1 class="heading-h2 bold mb-2">Meet Our Medical Expert Board</h1>
        <p class="heading-h4">
        Our board-certified physicians verify the medical accuracy of the articles you read on our site.
        </p>
        <p>These experienced medical professionals join us in our mission to empower you to confidently take the next steps in your health journey—for yourself or for a loved one. They review articles that contain medical information, ensuring that the content is thorough and reflects the latest in evidence-based research and health information.</p>
    </div>
    <div class="column">
        <img src="<?= get_template_directory_uri() ?>/dist/images/medical-review-board.webp" alt="Medical Review Board">
    </div>
</div>

<?php

// Query providers by taxonomy and term
$query_args = array(
    'post_type' => 'provider',
    'posts_per_page' => 99,
    'orderby' => 'title',
    'order' => 'ASC'
);

// Build taxonomy query array
$tax_queries = array();

// Always exclude practitioner provider-type
$tax_queries[] = array(
    'taxonomy' => 'provider-type',
    'field' => 'slug',
    'terms' => 'practitioner',
    'operator' => 'IN'
);

// Add tax_query if we have any queries
if (!empty($tax_queries)) {
    $query_args['tax_query'] = array(
        'relation' => 'AND',
        $tax_queries
    );
}

$practitioners_array = get_posts($query_args);

?>

<section class="section-v2 container mb-4">
    <div class="section-v2__title">
        <h2>Our Experts</h2>
    </div>
</section>

<div class="page-v2 container page-v2--medical-review-board">
    <div class="page-v2__right-sidebar"></div>
    <div class="page-v2__inner">
        <div class="provider-archive__inner">
                <?php 
            
            if (!empty($practitioners_array)){
                foreach($practitioners_array as $practitioner){
                    get_template_part('template-parts/cards/provider', 'practitioner', ['post' => $practitioner]); 
                }  
            }
            
            ?>
        </div>

    </div>
    <div class="page-v2__left-sidebar"></div>
</div>


<?php

get_footer();
