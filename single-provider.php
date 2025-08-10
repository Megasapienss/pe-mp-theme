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

    <section class="single-title-v2 container">
        <?php
        get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
            'class' => 'breadcrumbs breadcrumbs--dark single-title-v2__breadcrumbs'
        ));
        ?>
    </section>

    <article class="provider-page container">

        <div class="provider-page__meta">

            <?php if (get_field('image_url')) : ?>
            <div class="provider-page__cover">
                <img src="<?= get_field('image_url'); ?>" alt="<?= get_the_title(); ?>">
            </div>
            <?php endif; ?>

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
                <?php if (get_field('logo_url')) : ?>
                    <img src="<?= get_field('logo_url'); ?>" alt="" class="provider-page__avatar">
                <?php endif; ?>
                <h1 class="provider-page__title"><?= get_the_title(); ?></h1>
            </div>
            
            <?php if (get_field('subtitle')) : ?>
                <p class="provider-page__excerpt"><?= get_field('subtitle'); ?></p>
            <?php endif; ?>

            <?php if (get_field('countries_list')) : ?>
            <div class="provider-page__meta-item">
                <?php $countries = get_field('countries_list'); ?>
                <div class="d-flex flex-row">
                    <?php foreach ($countries as $country_id) : ?>
                        <?php $country = get_post($country_id); ?>
                        <div class="icon-tag icon-tag--globe"><?= esc_html($country->name); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (get_field('phone') || get_field('website_url')) : ?>
            <div class="provider-page__buttons">
                <?php if (get_field('phone')) : ?>
                    <a href="tel:<?= get_field('phone'); ?>" class="btn btn--primary">Call Now</a>
                <?php endif; ?>
                <?php if (get_field('website_url')) : ?>
                    <a href="<?= get_field('website_url'); ?>" class="btn btn--secondary" target="_blank">Website</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if (get_field('short_description_text')) : ?>
            <div class="provider-page__section">
                <h2 class="provider-page__section-heading">Description</h2>
                <div class="provider-page__section-content">
                    <?= get_field('short_description_text'); ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (get_field('address') && !empty(get_field('address')['street'])) : ?>
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
            <?php endif; ?>

            <?php if (get_field('pricing_details') || get_field('insurance_accepted_list') || get_field('cost_range')) : ?>
            <div class="provider-page__section">
                <h2 class="provider-page__section-heading">Pricing & Insurance</h2>
                <div class="provider-page__section-content grid grid--3">
                    <?php if (get_field('pricing_details')) : ?>
                    <div class="provider-page__section-column">
                        <h3 class="heading-h5">Cost</h3>
                        <p class="body-md">
                            <?= get_field('pricing_details'); ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if (get_field('insurance_accepted_list')) : ?>
                    <div class="provider-page__section-column">
                        <h3 class="heading-h5">Insurance</h3>
                        <p class="body-md">
                            <?= get_field('insurance_accepted_list') ? implode(', ', get_field('insurance_accepted_list')) : 'Currently not covered by insurance'; ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if (get_field('cost_range')) : ?>
                    <div class="provider-page__section-column">
                        <h3 class="heading-h5">Tier</h3>
                        <p class="body-md">
                            <?= get_field('cost_range')->name; ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (get_field('services_catalogue_relation')) : ?>
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
            <?php endif; ?>

            <?php if (get_field('conditions_list')) : ?>
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
            <?php endif; ?>

            <?php if (get_field('promo_article')) : ?>
            <div class="provider-page__section">
                <h2 class="provider-page__section-heading">Program Overview</h2>
                <div class="provider-page__section-content">
                    <?= get_field('promo_article'); ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="provider-page__section provider-page__section--gallery">
                <h2 class="provider-page__section-heading">Gallery</h2>
                <div class="provider-page__section-content">
                <?php if (get_field('image_url')) : ?>
                    <div class="provider-page__cover">
                        <img src="<?= get_field('image_url'); ?>" alt="<?= get_the_title(); ?>">
                    </div>
                <?php endif; ?>
                </div>
            </div>

            <?php if (get_field('practitioners_relation')) : ?>
            <div class="provider-page__section">
                <h2 class="provider-page__section-heading">Team & Staff</h2>
                <div class="provider-page__section-content grid grid--3">
                    <?php $practitioners = get_field('practitioners_relation'); ?>
                    <?php if ($practitioners) : ?>
                        <?php foreach ($practitioners as $practitioner_id) : ?>
                            <?php $practitioner = get_post($practitioner_id); ?>
                            <div class="provider-page__section-column">
                                <h3 class="heading-h5"><?= $practitioner->post_title; ?></h3>
                                <p class="body-md"><?= get_field('subtitle', $practitioner_id); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="provider-page__share">
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

<?php endwhile; ?>

<?php

get_footer();
