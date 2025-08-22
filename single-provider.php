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

    <article class="provider-single container">

        <div class="provider-single__meta">

            <?php if (get_field('image_url')) : ?>
            <div class="provider-single__cover">
                <img src="<?= get_field('image_url'); ?>" alt="<?= get_the_title(); ?>">
            </div>
            <?php endif; ?>

            <div class="provider-single__meta-item">
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

        <div class="provider-single__inner">

            <div class="provider-single__content">

                <div class="provider-single__title-wrapper">
                    <?php if (get_field('logo_url')) : ?>
                        <img src="<?= get_field('logo_url'); ?>" alt="" class="provider-single__avatar">
                    <?php endif; ?>
                    <h1 class="provider-single__title"><?= get_the_title(); ?></h1>
                    <?php
                    $card_tier = get_field('card_tier_badge');
                    if ($card_tier && is_object($card_tier)) {
                        $tier_slug = $card_tier->slug;
                        $tier_name = $card_tier->name;
                        $icon_path = get_template_directory_uri() . '/dist/icons/badges/' . $tier_slug . '.svg';
                        if (file_exists(get_template_directory() . '/dist/icons/badges/' . $tier_slug . '.svg')) {
                            ?>
                            <img src="<?= esc_url($icon_path); ?>" alt="<?= esc_attr($tier_name); ?>" class="provider-single__tier">
                            <?php
                        }
                    }
                    ?>
                </div>
                
                <?php if (get_field('subtitle')) : ?>
                    <p class="provider-single__excerpt"><?= get_field('subtitle'); ?></p>
                <?php endif; ?>

                
                <div class="provider-single__meta-item">
                    <?php if (get_field('address') && get_field('address')['country']) : ?>
                        <div class="provider-single__country">
                            <img src="<?= get_template_directory_uri(); ?>/dist/icons/countries/Country=<?= get_field('address')['country']; ?>, Style=Flag, Radius=Off.svg">
                            <?= esc_html(get_field('address')['country']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (get_field('countries_list')) : ?>
                        <?php $countries = get_field('countries_list'); ?>
                        <div class="d-flex flex-row">
                            <span class="text-muted ml-1 mr-1">Countries served:</span>
                            <?php foreach ($countries as $country_id) : ?>
                                <?php $country = get_post($country_id); ?>
                                <div class="provider-single__country">
                                    <img src="<?= get_template_directory_uri(); ?>/dist/icons/countries/Country=<?= $country->name; ?>, Style=Flag, Radius=Off.svg">
                                    <?= esc_html($country->name); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                

                <?php if (get_field('phone') || get_field('website_url')) : ?>
                <div class="provider-single__buttons">
                    <?php if (get_field('phone')) : ?>
                        <a href="tel:<?= get_field('phone'); ?>" class="btn btn--primary btn--show-phone" 
                            onmouseover="this.querySelector('.btn__phone-number').style.display='inline'; this.querySelector('.btn__call-now').style.display='none';" 
                            onmouseout="this.querySelector('.btn__phone-number').style.display='none'; this.querySelector('.btn__call-now').style.display='inline';">
                            <span class="btn__call-now">Call Now</span>
                            <span class="btn__phone-number" style="display:none;"><?= esc_html(get_field('phone')); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if (get_field('website_url')) : ?>
                        <a href="<?= get_field('website_url'); ?>" class="btn btn--secondary" target="_blank">Website</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if (get_field('short_description_text')) : ?>
                <div class="provider-single__section">
                    <h2 class="provider-single__section-heading">Description</h2>
                    <div class="provider-single__section-content">
                        <?= get_field('short_description_text'); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (get_field('address') && !empty(get_field('address')['street'])) : ?>
                <div class="provider-single__section">
                    <h2 class="provider-single__section-heading">Address</h2>
                    <div class="provider-single__section-content">
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
                <div class="provider-single__section pb-0">
                    <h2 class="provider-single__section-heading">Pricing & Insurance</h2>
                    <div class="provider-single__section-content">
                        <?php if (get_field('pricing_details')) : ?>
                        <div class="provider-single__section-column">
                            <h3 class="heading-h5">Cost</h3>
                            <p class="body-md">
                                <?= get_field('pricing_details'); ?>
                            </p>
                        </div>
                        <?php endif; ?>

                        <div class="provider-single__section-column">
                            <h3 class="heading-h5">Insurance</h3>
                            <p class="body-md">
                                <?php 
                                $insurance_terms = get_field('insurance_accepted_list');
                                if ($insurance_terms) {
                                    $insurance_names = array_map(function($term) {
                                        return $term->name;
                                    }, $insurance_terms);
                                    echo implode(', ', $insurance_names);
                                } else {
                                    echo 'Currently not covered by insurance';
                                }
                                ?>
                            </p>
                        </div>

                        <!-- <?php if (get_field('cost_range')) : ?>
                        <div class="provider-single__section-column">
                            <h3 class="heading-h5">Tier</h3>
                            <p class="body-md">
                                <?= get_field('cost_range')->name; ?>
                            </p>
                        </div>
                        <?php endif; ?> -->
                    </div>
                </div>
                <?php endif; ?>

                <?php if (get_field('gallery_list')) : ?>
                <div class="provider-single__section provider-single__section--gallery">
                    <h2 class="provider-single__section-heading">Gallery</h2>
                    <div class="provider-single__section-content">
                        <?php $gallery = get_field('gallery_list'); ?>
                        <?php if ($gallery && count($gallery) > 0) : ?>
                            <!-- Debug: Gallery count: <?= count($gallery) ?> -->
                            <div class="swiper gallery-swiper">
                                <div class="swiper-wrapper">
                                    <?php foreach ($gallery as $image) : ?>
                                        <div class="swiper-slide">
                                            <img src="<?= esc_url($image); ?>" alt="Gallery image">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <!-- Navigation arrows -->
                                <!-- <div class="swiper-button-next" style="display: flex !important;">
                                        <img src="<?= get_template_directory_uri(); ?>/dist/icons/ph_arrow-right.svg" alt="Next">
                                </div>
                                <div class="swiper-button-prev" style="display: flex !important;">
                                        <img src="<?= get_template_directory_uri(); ?>/dist/icons/ph_arrow-left.svg" alt="Previous">
                                </div> -->
                            </div>
                            
                            <!-- Thumbnail gallery -->
                            <div class="swiper gallery-thumbs">
                                <div class="swiper-wrapper">
                                    <?php foreach ($gallery as $image) : ?>
                                        <div class="swiper-slide">
                                            <img src="<?= esc_url($image); ?>" alt="Gallery thumbnail">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <p class="body-md">No gallery images available.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (get_field('services_catalogue_relation')) : ?>
                <?php 
                $services = get_field('services_catalogue_relation');
                $primary_services = array();
                $additional_services = array();
                
                if ($services) {
                    foreach ($services as $service_id) {
                        $service = get_post($service_id);
                        $is_additional = get_field('additional_service', $service_id);
                        
                        if ($is_additional) {
                            $additional_services[] = $service;
                        } else {
                            $primary_services[] = $service;
                        }
                    }
                }
                ?>
                
                <?php if (!empty($primary_services)) : ?>
                <div class="provider-single__section">
                    <h2 class="provider-single__section-heading">Services Offered</h2>
                    <div class="provider-single__section-content">
                        <div class="d-flex flex-column justify-start items-start">
                            <?php foreach ($primary_services as $service) : ?>
                                <div class="icon-tag"><?= $service->post_title; ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <?php if (get_field('conditions_list')) : ?>
                <div class="provider-single__section">
                    <h2 class="provider-single__section-heading">Conditions Treated</h2>
                    <div class="provider-single__section-content">
                        <?php $conditions = get_field('conditions_list'); ?>
                        <?php if ($conditions) : ?>
                            <div class="d-flex flex-row">
                                <?php foreach ($conditions as $condition) : ?>
                                    <div class="icon-tag icon-tag--condition"><?= esc_html($condition->name); ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="body-md">No conditions added yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (get_field('services_catalogue_relation') && !empty($additional_services)) : ?>
                <div class="provider-single__section">
                    <h2 class="provider-single__section-heading">Additional Services</h2>
                    <div class="provider-single__section-content">
                        <div class="d-flex flex-column justify-start items-start">
                            <?php foreach ($additional_services as $service) : ?>
                                <div class="icon-tag"><?= $service->post_title; ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (get_field('program_overview')) : ?>
                <div class="provider-single__section">
                    <h2 class="provider-single__section-heading">Program Overview</h2>
                    <div class="provider-single__section-content">
                        <?= get_field('program_overview'); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (get_field('video_url')) : ?>
                <div class="provider-single__section provider-single__section--video">
                    <h2 class="provider-single__section-heading">Video</h2>
                    <div class="provider-single__section-content">
                        <?php 
                        $video_url = get_field('video_url');
                        if ($video_url) {
                            pe_mp_video_embed($video_url, array(
                                'class' => 'provider-video-embed',
                                'width' => 640,
                                'height' => 360,
                                'preload_metadata' => false,
                                'lazy_load' => true
                            ));
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (get_field('practitioners_relation')) : ?>
                <div class="provider-single__section">
                    <h2 class="provider-single__section-heading">Team & Staff</h2>
                    <div class="provider-single__section-content grid grid--2">
                        <?php $practitioners = get_field('practitioners_relation'); ?>
                        <?php if ($practitioners) : ?>
                            <?php foreach ($practitioners as $practitioner_id) : ?>
                                <?php $practitioner = get_post($practitioner_id); ?>
                                <div class="provider-single__section-grid-item">
                                    <h3 class="heading-h5"><?= $practitioner->post_title; ?></h3>
                                    <p class="body-md"><?= get_field('subtitle', $practitioner_id); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="provider-single__share">
                    <span class="icon-tag icon-tag--rounded icon-tag--globe share-trigger">Share this provider</span>
                </div>

            </div>

            <div class="provider-single__sidebar">
                <?php
                // Get custom sidebar CTA data
                $custom_sidebar_cta = pe_mp_get_sidebar_cta_data();
                
                if ($custom_sidebar_cta) {
                    // Use custom sidebar image
                    ?>
                    <a href="<?php echo esc_url($custom_sidebar_cta['link']); ?>" target="_blank" class="sidebar-banner">
                        <img src="<?php echo esc_url($custom_sidebar_cta['image']); ?>">
                    </a>
                    <?php
                } else {
                    // Use default test banner
                    get_template_part('template-parts/banners/test', '', [
                        'test_id' => 'assessment'
                    ]);
                }
                ?>
            </div>

        </div>
        
    </article>

    <?php
    get_template_part('template-parts/mosaics/recommendations');
    ?> 

<?php endwhile; ?>

<?php

get_footer();
