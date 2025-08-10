<?php

/**
 * The template for displaying all single posts
 *
 * @package PE_MP_Theme
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();

?>

<?php while (have_posts()) : the_post(); ?>

    <?php
    $background_url = get_field('image_url');
    
    // If no thumbnail or WebP, use default cover
    if (empty($background_url)) {
        $background_url = get_template_directory_uri() . '/dist/images/cover.webp';
    }
    ?>

    <section class="single-title-v2 container">
        <?php
        get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
            'class' => 'breadcrumbs breadcrumbs--dark single-title-v2__breadcrumbs'
        ));
        ?>
    </section>

    <article class="provider-page container">

        <div class="provider-page__meta">

            <div class="provider-page__cover">
                <img src="<?= $background_url; ?>" alt="<?= get_the_title(); ?>">
            </div>

            <div class="provider-page__meta-item">
                <?php
                // List of taxonomies to display
                $taxonomies = array('provider-type', 'service-delivery-method');
                foreach ($taxonomies as $taxonomy) {
                    $terms = get_the_terms(get_the_ID(), $taxonomy);
                    if (!empty($terms) && !is_wp_error($terms)) {
                        foreach ($terms as $term) {
                            ?>
                            <span class="icon-tag icon-tag--rounded"><?= esc_html($term->name); ?></span>
                            <?php
                        }
                    }
                }
                ?>
            </div>
        </div>

        <div class="provider-page__inner">

            <div class="provider-page__title-wrapper">
                <img src="<?= get_field('logo_url'); ?>" alt="" class="provider-page__avatar">
                <h1 class="provider-page__title"><?= get_the_title(); ?></h1>
            </div>
            
            <p class="provider-page__excerpt"><?= get_field('subtitle'); ?></p>

            <div class="provider-page__meta-item">
                <?php $countries = get_field('countries_list'); ?>
                <?php if ($countries) : ?>
                    <div class="d-flex flex-row">
                        <?php foreach ($countries as $country_id) : ?>
                            <?php $country = get_post($country_id); ?>
                            <div class="icon-tag icon-tag--globe"><?= esc_html($country->name); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="provider-page__buttons">
                <?php if (get_field('phone')) : ?>
                    <a href="tel:<?= get_field('phone'); ?>" class="btn btn--primary">Call Now</a>
                <?php endif; ?>
                <?php if (get_field('website_url')) : ?>
                    <a href="<?= get_field('website_url'); ?>" class="btn btn--secondary" target="_blank">Website</a>
                <?php endif; ?>
            </div>

            <div class="provider-page__section">
                <h2 class="provider-page__section-heading">Description</h2>
                <div class="provider-page__section-content">
                    <?= get_field('short_description_text'); ?>
                </div>
            </div>

            <div class="provider-page__section">
                <h2 class="provider-page__section-heading">Address</h2>
                <div class="provider-page__section-content">
                        <?php $address = get_field('address'); ?>
                        <span class="icon-tag icon-tag--map">
                            <?= $address['street'] ? $address['street'] . ', ' : ''; ?>
                            <?= $address['city'] ? $address['city'] . ', ' : ''; ?>
                            <?= $address['state'] ? $address['state'] . ', ' : ''; ?>
                            <?= $address['postal_code'] ? $address['postal_code'] . ', ' : ''; ?>
                            <?= $address['country'] ? $address['country'] : ''; ?>
                        </span>
                </div>
            </div>

            <div class="provider-page__section">
                <h2 class="provider-page__section-heading">Pricing & Insurance</h2>
                <div class="provider-page__section-content grid grid--3">
                    <div class="provider-page__section-column">
                        <h3 class="heading-h5">Cost</h3>
                        <p class="body-md">
                            <?= get_field('pricing_details'); ?>
                        </p>
                    </div>
                    <div class="provider-page__section-column">
                        <h3 class="heading-h5">Insurance</h3>
                        <p class="body-md">
                            <?= get_field('insurance_accepted_list') ? implode(', ', get_field('insurance_accepted_list')) : 'Currently not covered by insurance'; ?>
                        </p>
                    </div>
                    <div class="provider-page__section-column">
                        <h3 class="heading-h5">Tier</h3>
                        <p class="body-md">
                            <?= get_field('cost_range')->name; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="provider-page__section">
                <h2 class="provider-page__section-heading">Services Offered</h2>
                <div class="provider-page__section-content">
                    <?php $services = get_field('services_catalogue_relation'); ?>
                    <?php if ($services) : ?>
                        <div class="d-flex flex-column justify-start items-start">
                            <?php foreach ($services as $service_id) : ?>
                                <?php $service = get_post($service_id); ?>
                                <div class="icon-tag"><?= $service->post_title; ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p class="body-md">No services added yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="provider-page__section">
                <h2 class="provider-page__section-heading">Conditions Treated</h2>
                <div class="provider-page__section-content">
                    <?php $conditions = get_field('conditions_list'); ?>
                    <?php if ($conditions) : ?>
                        <div class="d-flex flex-row">
                            <?php foreach ($conditions as $condition) : ?>
                                <div class="icon-tag"><?= esc_html($condition->name); ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p class="body-md">No conditions added yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="provider-page__section">
                <h2 class="provider-page__section-heading">Program Overview</h2>
                <div class="provider-page__section-content">
                    <?= get_field('promo_article'); ?>
                </div>
            </div>

            <div class="provider-page__share mt-5">
                <span class="label label--share label--primary label--icon">Share</span>
            </div>


        </div>
        
    </article>

    <?php
    // New articles Section
    get_template_part('template-parts/mosaics/complex', '', [
        'title' => 'Editor\'s Picks'
    ]);
    ?>  

    <?php if (false) : ?>
    <article class="article container">
        <section class="article__content body-lg">
            <?php the_content(); ?>
            <div class="article__sources accordion">
                <div class="accordion__header">
                    <h2 class="accordion__title">Sources</h2>
                    <img src="<?= get_template_directory_uri(); ?>/dist/icons/icon-arrow-down.svg">
                </div>
                <div class="accordion__body">
                    <nav class="sources-list">
                        <!-- Sources will be populated by JavaScript -->
                    </nav>
                </div>
            </div>
        </section>
        <aside class="article__sidebar">
            <div class="sidebar-card sidebar-card--author">
                <div class="corner corner--right-top">
                    <span>Author</span>
                </div>
                <?php
                $author_id = get_the_author_meta('ID');
                $avatar = get_avatar_url($author_id, array('size' => 120));
                $author_name = get_the_author_meta('display_name');
                $author_description = get_the_author_meta('description');
                ?>
                <img src="<?= $avatar; ?>" class="sidebar-card__avatar">
                <h3 class="sidebar-card__title"><?= $author_name; ?></h3>
                <p class="sidebar-card__excerpt">
                    <?= $author_description; ?>
                </p>

                <!-- <div class="sidebar-card__time">
                    <span class="heading-h6">Estimated reading time</span>
                    <span class="sidebar-card__time-value">6 min</span>
                </div> -->

                <div class="sidebar-card__share">
                    <span class="label label--share label--primary label--icon">Share</span>
                    <!-- <div class="sidebar-card__share-icons d-flex flex-row">
                        <img src="<?= get_template_directory_uri(); ?>/dist/icons/badge-instagram.svg">
                        <img src="<?= get_template_directory_uri(); ?>/dist/icons/badge-x.svg">
                        <img src="<?= get_template_directory_uri(); ?>/dist/icons/badge-f.svg">
                    </div> -->
                </div>
            </div>
            <div class="sidebar-card sidebar-card--quiz">
                <h3 class="sidebar-card__title">Feeling low?</h3>
                <p class="sidebar-card__excerpt">
                    Check your mental state level and get a personalized action plan in 3 minutes.
                </p>
                <?php
                // Priority system for link:
                // 1. Assigned page from ACF field
                // 2. Hardcoded link
                $quiz_link = pe_mp_get_related_test_page_url();
                if (empty($quiz_link)) {
                    $quiz_link = PE_MP_QUIZ_DEFAULT_LINK;
                }
                ?>
                <a href="<?= esc_url($quiz_link); ?>" class="sidebar-card__link arrow-btn arrow-btn--primary">
                    <?php esc_html_e('Start test', 'pe-mp-theme'); ?>
                </a>
            </div>
        </aside>
    </article>
    <?php endif; ?>

<?php endwhile; ?>

<?php

get_footer();
