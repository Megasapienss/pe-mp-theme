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
        'class' => 'breadcrumbs breadcrumbs--dark single-title-v2__breadcrumbs'
    ));
    ?>
    <h1 class="single-title-v2__title heading-h1">Medical Review Board</h1>
</section>

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


<div class="page-v2 container">
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
