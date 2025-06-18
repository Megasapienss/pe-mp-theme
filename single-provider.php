<?php
/**
 * The template for displaying single provider
 */

get_header();

while (have_posts()) :
    the_post();
    
    // Get provider data
    $address = get_field('address');
    $latitude = get_field('latitude');
    $longitude = get_field('longitude');
    $short_description = get_field('short_description');
    $full_description = get_field('full_description');
    $rating = get_field('rating');
    $reviews_count = get_field('reviews_count');
    $booking_url = get_field('booking_url');
    $image_url = get_field('image_url');
?>

<div class="provider-single">
    <div class="container">
        <!-- Provider Header -->
        <div class="provider-header">
            <div class="provider-main-info">
                <div class="provider-image">
                    <?php 
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('large', array('class' => 'provider-photo'));
                    } elseif ($image_url) {
                        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '" class="provider-photo">';
                    }
                    ?>
                </div>
                
                <div class="provider-details">
                    <h1 class="provider-name"><?php the_title(); ?></h1>
                    
                    <div class="provider-badges">
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
                    
                    <?php if ($address): ?>
                        <div class="provider-address">
                            <?php echo esc_html($address); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($rating || $reviews_count): ?>
                        <div class="provider-rating">
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

                    <?php if ($booking_url): ?>
                        <div class="provider-actions mt-5">
                            <a href="<?php echo esc_url($booking_url); ?>" class="btn btn-primary" target="_blank">
                                <?php _e('Request now', 'pe-mp-theme'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Provider Content -->
        <div class="provider-content">

            <?php if ($full_description): ?>
                <div class="provider-full-description">
                    <?php echo wp_kses_post($full_description); ?>
                </div>
            <?php endif; ?>

            <?php if ($latitude && $longitude): ?>
                <div class="provider-map" 
                     data-lat="<?php echo esc_attr($latitude); ?>" 
                     data-lng="<?php echo esc_attr($longitude); ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
endwhile;

get_footer();
?> 