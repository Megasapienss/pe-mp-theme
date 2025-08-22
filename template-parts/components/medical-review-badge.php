<?php
/**
 * Medical Review Badge Component
 * 
 * @param array $review_data Medical review data array
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get review data if not passed
if (!isset($review_data)) {
    $review_data = pe_mp_get_medical_review_data();
}

// Don't display if no review data
if (!$review_data) {
    return;
}

$reviewer_name = $review_data['name'];
$review_date = $review_data['review_date'];
?>

<div class="medical-review-badge">
    <div class="medical-review-badge__reviewer">
        <img src="<?= get_template_directory_uri(); ?>/dist/icons/checkmark.svg">
        <span class="medical-review-badge__reviewer-name">
            <span>
                Medically reviewed by
            </span>
            <a href="<?= $review_data['url']; ?>" >
                <?php echo esc_html($reviewer_name); ?>
            </a>
        </span>
    </div>
    <span class="medical-review-badge__date">
        Reviewed on <?php echo pe_mp_format_review_date($review_date); ?>
    </span>
</div>
