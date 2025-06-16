<?php
/**
 * The template for displaying the front page
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><?php echo esc_html(get_bloginfo('name')); ?></h1>
                <p><?php echo esc_html(get_bloginfo('description')); ?></p>
            </div>
        </div>
    </div>
</main>

<?php
get_footer(); 