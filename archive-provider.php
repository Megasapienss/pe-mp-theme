<?php

/**
 * The template for displaying post archives
 * 
 * @package PE_MP_Theme
 */

get_header();
$term = get_queried_object();
$post_count = $wp_query->found_posts;

// Get all condition terms for the filter
$conditions = get_terms(array(
    'taxonomy' => 'condition',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC'
));

// Get all services for the filter (only primary services, not additional ones)
$services = get_posts(array(
    'post_type' => 'service',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'additional_service',
            'value' => '0',
            'compare' => '='
        ),
        array(
            'key' => 'additional_service',
            'value' => '',
            'compare' => '='
        ),
        array(
            'key' => 'additional_service',
            'compare' => 'NOT EXISTS'
        )
    )
));

// Get all service delivery method terms for the filter
$service_delivery_methods = get_terms(array(
    'taxonomy' => 'service-delivery-method',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC'
));

// Get unique countries from provider addresses
$countries = array();
$providers = get_posts(array(
    'post_type' => 'provider',
    'posts_per_page' => -1,
    'post_status' => 'publish'
));

foreach ($providers as $provider) {
    $address = get_field('address', $provider->ID);
    if ($address && !empty($address['country'])) {
        $countries[] = $address['country'];
    }
}
$countries = array_unique($countries);
sort($countries);

// Get current filter values from URL
$current_condition = isset($_GET['condition']) ? sanitize_text_field($_GET['condition']) : '';
$current_service = isset($_GET['service']) ? sanitize_text_field($_GET['service']) : '';
$current_service_delivery = isset($_GET['service_delivery']) ? sanitize_text_field($_GET['service_delivery']) : '';
$current_country = isset($_GET['country']) ? sanitize_text_field($_GET['country']) : '';
?>

<section class="archive-title-v2 archive-title-v2--providers container">
    <?php
    get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
        'class' => 'breadcrumbs breadcrumbs--dark archive-title__breadcrumbs'
    ));
    ?>
    <h1 class="archive-title-v2__name">Treatment centres in the UK and EU</h1>
    <!-- <p class="archive-title-v2__description"></p> -->
    <img src="<?= get_template_directory_uri(); ?>/dist/images/cover-4x1.webp" alt="Providers" class="archive-title-v2__cover">

</section>

<?php if (!empty($conditions) || !empty($services) || !empty($service_delivery_methods) || !empty($countries)) : ?>
<section class="provider-filters container">

    <select id="country-filter" class="select-v2 country-filter">
        <option value="">Location</option>
        <?php foreach ($countries as $country) : ?>
            <option value="<?= esc_attr($country) ?>" <?= selected($current_country, $country, false) ?>>
                <?= esc_html($country) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select id="condition-filter" class="select-v2 condition-filter">
        <option value="">Conditions</option>
        <?php foreach ($conditions as $condition) : ?>
            <option value="<?= esc_attr($condition->slug) ?>" <?= selected($current_condition, $condition->slug, false) ?>>
                <?= esc_html($condition->name) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select id="service-filter" class="select-v2 service-filter">
        <option value="">Type of treatment</option>
        <?php foreach ($services as $service) : ?>
            <option value="<?= esc_attr($service->ID) ?>" <?= selected($current_service, $service->ID, false) ?>>
                <?= esc_html($service->post_title) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select id="service-delivery-filter" class="select-v2 service-delivery-filter">
        <option value="">Service delivery</option>
        <?php foreach ($service_delivery_methods as $method) : ?>
            <option value="<?= esc_attr($method->slug) ?>" <?= selected($current_service_delivery, $method->slug, false) ?>>
                <?= esc_html($method->name) ?>
            </option>
        <?php endforeach; ?>
    </select>
</section>
<?php endif; ?>

<section class="provider-archive container">
    <div class="provider-archive__inner" id="provider-results">
        <?php if (have_posts()) {
            while (have_posts()) {
                the_post();
                get_template_part('template-parts/cards/provider', '', ['post' => get_post()]); 
            }
        } else { ?>
            <p class="text-center"><?php _e('No providers found.', 'pe-mp-theme'); ?></p>
        <?php } ?>
    </div>
    <div class="provider-archive__sidebar">
        <div class="sidebar-card-v2">
            <?php

            get_template_part('template-parts/banners/test', '', [
                'test_id' => pe_mp_get_related_test_id()
            ]); 
                
            ?>
        </div>
    </div>
</section>

<?php
get_template_part('template-parts/mosaics/recommendations');
?>

<?php
get_footer();
?>