<?php

/**
 * Template Name: Test Landing Page
 *
 * The template for displaying test landing pages with custom fields
 *
 * @package PE_MP_Theme
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

?>

<?php while (have_posts()) : the_post(); ?>

    <div class="test-landing container">
        
        <section class="test-landing__inner">
            <div class="test-landing__content">
            <?php the_content(); ?>
            <?php if (get_field('related_test')) : ?>
                <?php 
                $test_link = get_field('related_test');

                if ($test_link && !empty($test_link)) : ?>
                    <a href="<?php echo esc_url($test_link); ?>" class="test-landing__link btn btn--accent btn--icon btn--56">
                        Start the test
                    </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="test-landing__image">
                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full') ?>" alt="">
            </div>
        </section>

        <?php if (get_field('disclaimer')) : ?>
            <div class="test-landing__disclaimer">
                <h2 class="test-landing__disclaimer-title">Disclaimer</h2>
                <p><?php echo wp_kses_post(get_field('disclaimer')); ?></p>
            </div>
        <?php endif; ?>

        <?php if (have_rows('faq')) : ?>
            <section class="test-landing__faq faq">
                <h2 class="faq__title">FAQ:</h2>
                <div class="faq__list">
                    <?php while (have_rows('faq')) : the_row(); ?>
                        <div class="faq__item">
                            <h3 class="faq__question"><?php echo esc_html(get_sub_field('question')); ?></h3>
                            <div class="faq__answer">
                                <?php echo wp_kses_post(get_sub_field('answer')); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php endif; ?>

    </div>

<?php endwhile; ?>

<?php

get_footer();
