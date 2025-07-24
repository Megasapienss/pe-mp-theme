<?php
/**
 * Template part for displaying provider cards
 */

// Get provider data
$url = get_field('url');
$title = get_field('title');
$status = get_field('status');
$rating = get_field('rating');
$short_description = get_field('short_description');
$insurance = get_field('insurance');
$highlights = get_field('highlights');
$main_image = get_field('main_image');
$logo = get_field('logo');
$phone = get_field('phone');
$website = get_field('website');
$address = get_field('address');
$treatment_focus = get_field('treatment_focus');
$specializations = get_field('specializations');
$verified_by_recovery = get_field('verified_by_recovery');
$founded = get_field('founded');
$estimated_cash_pay_rate = get_field('estimated_cash_pay_rate');
$occupancy = get_field('occupancy');
$who_we_treat = get_field('who_we_treat');
$accreditation = get_field('accreditation');
$languages = get_field('languages');

// Get provider types
$provider_types = get_the_terms(get_the_ID(), 'provider-type');
$provider_type_names = array();
if ($provider_types && !is_wp_error($provider_types)) {
    foreach ($provider_types as $type) {
        $provider_type_names[] = $type->name;
    }
}
?>

<article class="provider-card">
    <div class="provider-card__header">
        <div class="provider-card__image-wrapper">
            <div class="provider-card__image-container">
                <?php 
                if (has_post_thumbnail()) {
                    the_post_thumbnail('medium', array('class' => 'provider-card__image'));
                } elseif ($main_image) {
                    echo '<img src="' . esc_url($main_image) . '" alt="' . esc_attr(get_the_title()) . '" class="provider-card__image">';
                } else {
                    echo '<img src="' . get_template_directory_uri() . '/dist/images/topic-card-placeholder.webp" alt="' . esc_attr(get_the_title()) . '" class="provider-card__image">';
                }
                ?>
                
                <?php if ($verified_by_recovery): ?>
                    <span class="provider-card__badge verified" title="Verified by Recovery">
                        <svg viewBox="0 0 24 24" width="16" height="16">
                            <path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="provider-card__title">
            <h3 class="provider-card__name">
                <a href="<?php the_permalink(); ?>">
                    <?php echo $title ? esc_html($title) : get_the_title(); ?>
                </a>
            </h3>
            
            <?php if (!empty($provider_type_names)): ?>
                <div class="provider-card__specialties">
                    <?php echo esc_html(implode(', ', $provider_type_names)); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($founded): ?>
                <div class="provider-card__founded">
                    Founded <?php echo esc_html($founded); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($rating): ?>
        <div class="provider-card__rating">
            <div class="rating">
                <span class="rating__value"><?php echo number_format($rating, 1); ?></span>
                <span class="rating__stars">
                    <?php
                    $full_stars = floor($rating);
                    $half_star = ($rating - $full_stars) >= 0.5;
                    
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $full_stars) {
                            echo '<span class="star star--full">★</span>';
                        } elseif ($i == $full_stars + 1 && $half_star) {
                            echo '<span class="star star--half">★</span>';
                        } else {
                            echo '<span class="star star--empty">☆</span>';
                        }
                    }
                    ?>
                </span>
            </div>
        </div>
    <?php endif; ?>

    <div class="provider-card__badges">
        <?php if ($verified_by_recovery): ?>
            <span class="badge badge--verified">
                <svg viewBox="0 0 24 24" width="16" height="16">
                    <path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                Verified by Recovery
            </span>
        <?php endif; ?>
        
        <?php if ($status && $status !== 'closed'): ?>
            <span class="badge badge--accepting">
                <svg viewBox="0 0 24 24" width="16" height="16">
                    <path fill="currentColor" d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-2 14l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
                </svg>
                Accepting Patients
            </span>
        <?php endif; ?>
        
        <?php if ($website): ?>
            <span class="badge badge--virtual">
                <svg viewBox="0 0 24 24" width="16" height="16">
                    <path fill="currentColor" d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H3V5h18v14zM9 10v6l5-3z"/>
                </svg>
                Online Services
            </span>
        <?php endif; ?>
    </div>

    <?php if ($highlights): ?>
        <div class="provider-card__highlights">
            <?php echo wp_kses_post($highlights); ?>
        </div>
    <?php endif; ?>

    <?php if ($short_description): ?>
        <div class="provider-card__description">
            <?php echo wp_kses_post($short_description); ?>
        </div>
    <?php endif; ?>

    <?php if ($treatment_focus || $specializations): ?>
        <div class="provider-card__specializations">
            <?php if ($treatment_focus): ?>
                <div class="specialization-item">
                    <strong>Focus:</strong> <?php echo esc_html($treatment_focus); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($specializations): ?>
                <div class="specialization-item">
                    <strong>Specializations:</strong> <?php echo esc_html($specializations); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($who_we_treat): ?>
        <div class="provider-card__who-we-treat">
            <strong>Who We Treat:</strong> <?php echo esc_html($who_we_treat); ?>
        </div>
    <?php endif; ?>

    <?php if ($insurance): ?>
        <div class="provider-card__insurance">
            <strong>Insurance:</strong> <?php echo esc_html($insurance); ?>
        </div>
    <?php endif; ?>

    <?php if ($estimated_cash_pay_rate): ?>
        <div class="provider-card__pricing">
            <strong>Cash Pay Rate:</strong> <?php echo esc_html($estimated_cash_pay_rate); ?>
        </div>
    <?php endif; ?>

    <?php if ($address): ?>
        <div class="provider-card__location">
            <svg class="location-icon" viewBox="0 0 24 24" width="16" height="16">
                <path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
            <span class="location-text"><?php echo esc_html($address); ?></span>
        </div>
    <?php endif; ?>

    <?php if ($phone || $website): ?>
        <div class="provider-card__contact">
            <?php if ($phone): ?>
                <div class="contact-item">
                    <svg viewBox="0 0 24 24" width="16" height="16">
                        <path fill="currentColor" d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                    </svg>
                    <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
                </div>
            <?php endif; ?>
            
            <?php if ($website): ?>
                <div class="contact-item">
                    <svg viewBox="0 0 24 24" width="16" height="16">
                        <path fill="currentColor" d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/>
                    </svg>
                    <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener">Visit Website</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="provider-card__footer">
        <?php if ($website): ?>
            <a href="<?php echo esc_url($website); ?>" class="btn btn-primary" target="_blank" rel="noopener">
                <?php _e('Visit Website', 'pe-mp-theme'); ?>
            </a>
        <?php endif; ?>
        <a href="<?php the_permalink(); ?>" class="btn btn-secondary">
            <?php _e('View Profile', 'pe-mp-theme'); ?>
        </a>
    </div>
</article> 