<?php
/**
 * Template part for displaying provider cards
 */

// Get provider data
$address = get_field('address');
$rating = get_field('rating');
$reviews_count = get_field('reviews_count');
$short_description = get_field('short_description');
$image_url = get_field('image_url');
$booking_url = get_field('booking_url');

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
                } elseif ($image_url) {
                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '" class="provider-card__image">';
                }
                ?>
                <span class="provider-card__badge verified" title="Verified Provider">
                    <svg viewBox="0 0 24 24" width="16" height="16">
                        <path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                </span>
            </div>
        </div>

        <div class="provider-card__title">
            <h3 class="provider-card__name">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h3>
            
            <?php if (!empty($provider_type_names)): ?>
                <div class="provider-card__specialties">
                    <?php echo esc_html(implode(', ', $provider_type_names)); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($rating || $reviews_count): ?>
        <div class="provider-card__rating">
            <?php if ($rating): ?>
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
            <?php endif; ?>
            
            <?php if ($reviews_count): ?>
                <div class="reviews-count">
                    <?php 
                    printf(
                        _n('%s review', '%s reviews', $reviews_count, 'pe-mp-theme'),
                        number_format_i18n($reviews_count)
                    ); 
                    ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="provider-card__badges">
        <span class="badge badge--verified">
            <svg viewBox="0 0 24 24" width="16" height="16">
                <path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
            Verified
        </span>
        <span class="badge badge--accepting">
            <svg viewBox="0 0 24 24" width="16" height="16">
                <path fill="currentColor" d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-2 14l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
            </svg>
            Accepting New Patients
        </span>
        <span class="badge badge--virtual">
            <svg viewBox="0 0 24 24" width="16" height="16">
                <path fill="currentColor" d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H3V5h18v14zM9 10v6l5-3z"/>
            </svg>
            Virtual Visit Available
        </span>
    </div>

    <?php if ($short_description): ?>
        <div class="provider-card__description">
            <?php echo wp_kses_post($short_description); ?>
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

    <div class="provider-card__footer">
        <?php if ($booking_url): ?>
            <a href="<?php echo esc_url($booking_url); ?>" class="btn btn-primary">
                <?php _e('Request Now', 'pe-mp-theme'); ?>
            </a>
        <?php endif; ?>
        <a href="<?php the_permalink(); ?>" class="btn btn-secondary">
            <?php _e('View Profile', 'pe-mp-theme'); ?>
        </a>
    </div>
</article> 