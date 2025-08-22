<?php
/**
 * Template Name: Test Report
 * 
 * A page template based on single.php for displaying test reports
 *
 * @package PE_MP_Theme
 */

if (! defined('ABSPATH')) {
exit; // Exit if accessed directly.
}


get_header();
$database = new PE_Quiz_Database();
$runpod_job = $database->get_runpod_job($_GET['report-id']);

if (!$runpod_job){
    return;
}

$report_status = $runpod_job->output_data['status'];

$report_input = $runpod_job->input_data;
$report_output = $runpod_job->output_data['report'];

?>

<?php
$thumbnail_id = get_post_thumbnail_id();
$background_url = '';

if ($thumbnail_id) {
// First try to get WebP version
$webp_url = pe_mp_get_webp_url($thumbnail_id);
if ($webp_url) {
    $background_url = $webp_url;
} else {
    // If no WebP, use original thumbnail
    $background_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
}
}

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

<article class="article-v2 report-v2 container">

<div class="article-v2__meta">
    <div class="article-v2__meta-item">
        <span class="article-v2__meta-label">Date</span>
        <span class="article-v2__meta-value"><?= date('d M Y'); ?></span>
    </div>
    <div class="article-v2__meta-item">
        <span class="article-v2__meta-label">Reading time</span>
        <span class="article-v2__meta-value">
            2 min.
        </span>
    </div>
    <div class="article-v2__meta-item">
        <span class="label">Diagnostics</span>
    </div>
</div>

<div class="article-v2__inner">
    <h1 class="article-v2__title">Test Report</h1>
    <p class="article-v2__excerpt">Severity: <?php echo $report_output['analysis']['severity']; ?></p>
    <p class="article-v2__excerpt">Age: <?php echo $report_input['personal_info']['age']; ?></p>
    <p class="article-v2__excerpt">Sex: <?php echo $report_input['personal_info']['sex']; ?></p>
    <p class="article-v2__excerpt">Country: <?php echo $report_input['personal_info']['country']; ?></p>
    <p class="article-v2__excerpt">Email: <?php echo $report_input['personal_info']['email']; ?></p>

    <div class="report-v2__graph">
        <img src="<?= $background_url; ?>">
    </div>

    <div class="report-v2__actions">
        <button class="btn btn--secondary btn--48 share-trigger"">Share this report</button>
        <button class="btn btn--secondary btn--48 share-trigger"">Share this report</button>
    </div>

    <div class="article-v2__content-wrapper">

        <div class="article-v2__content body-md">
 
            <div class="pe-quiz-results__main-content report-v2__main-content">
                <?php if (
                    isset($report_output['analysis']['severity']) && (
                        strtolower($report_output['analysis']['severity']) == 'moderate' ||
                        strtolower($report_output['analysis']['severity']) == 'severe' ||
                        strtolower($report_output['analysis']['severity']) == 'extreme'
                    )
                ): ?>
                    <div class="pe-quiz-results__banner">
                        <h3 class="pe-quiz-results__banner-title">If you're in crisis - contact immediate support</h3>
                        <div class="pe-quiz-results__banner-text">
                            <!-- <p>Contact immediate support:</p> -->
                            <?php if (isset($report_output['recommendations']['crisis_support']) && is_array($report_output['recommendations']['crisis_support'])): ?>
                                <?php foreach ($report_output['recommendations']['crisis_support'] as $key => $crisis_support): ?>
                                    <div>
                                        <p><strong><?php echo $key + 1; ?>. <?php echo $crisis_support['name']; ?></strong></p>
                                        <p><?php echo $crisis_support['description']; ?></p>
                                        <div class="pe-quiz-results__banner-contacts">
                                            <?php if (isset($crisis_support['phone_number']) && $crisis_support['phone_number'] != 'NULL'): ?>
                                                <p><strong>Phone:</strong> <a href="tel:<?php echo esc_attr($crisis_support['phone_number']); ?>"><?php echo $crisis_support['phone_number']; ?></a></p>
                                            <?php endif; ?>
                                            <?php if (isset($crisis_support['email']) && $crisis_support['email'] != 'NULL'): ?>
                                                <p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($crisis_support['email']); ?>"><?php echo $crisis_support['email']; ?></a></p>
                                            <?php endif; ?>
                                            <?php if (isset($crisis_support['website']) && $crisis_support['website'] != 'NULL'): ?>
                                                <?php
                                                // Extract domain from URL, fallback to "open link"
                                                $website_url = isset($crisis_support['website']) ? $crisis_support['website'] : '';
                                                $website_domain = '';
                                                if (!empty($website_url)) {
                                                    $parsed_url = parse_url($website_url);
                                                    if (isset($parsed_url['host']) && !empty($parsed_url['host'])) {
                                                        $website_domain = $parsed_url['host'];
                                                    } else {
                                                        $website_domain = 'Open link';
                                                    }
                                                }
                                                ?>
                                                <p><strong>Website:</strong> <a href="<?php echo esc_url($website_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($website_domain); ?></a></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php echo $report_output['recommendations']['crisis_support']; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 1. What is happening inside -->
                <div class="pe-quiz-results__section">
                    <h3 class="pe-quiz-results__section-title" data-toc="true">What is happening inside</h3>
                    <h4>Body</h4>
                    <p><?php echo isset($report_output['analysis']['body_state']) ? $report_output['analysis']['body_state'] : 'Information not available'; ?></p>
                    <h4>Mind</h4>
                    <p><?php echo isset($report_output['analysis']['mind_state']) ? $report_output['analysis']['mind_state'] : 'Information not available'; ?></p>
                </div>

                <!-- 2. Where you are now -->
                <div class="pe-quiz-results__section">
                    <h3 class="pe-quiz-results__section-title" data-toc="true">Where you are now</h3>
                    <div class="pe-quiz-results__grid">
                        <div class="pe-quiz-results__grid-item">
                            <h4>Without action</h4>
                            <p><?php echo isset($report_output['analysis']['without_action']) ? $report_output['analysis']['without_action'] : 'Information not available'; ?></p>
                        </div>
                        <div class="pe-quiz-results__grid-item pe-quiz-results__grid-item--active">
                            <h4>With action</h4>
                            <p><?php echo isset($report_output['analysis']['with_action']) ? $report_output['analysis']['with_action'] : 'Information not available'; ?></p>
                        </div>
                    </div>
                    <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/pe-quiz-image--ways.avif'; ?>" alt="PE Quiz Results Hero">
                </div>

                <!-- 3. Why it's happening -->
                <div class="pe-quiz-results__section">
                    <h3 class="pe-quiz-results__section-title" data-toc="true">Why it's happening</h3>
                    <p><?php echo isset($report_output['analysis']['why_happening']) ? $report_output['analysis']['why_happening'] : 'Information not available'; ?></p>
                </div>

                <!-- 4. What you can do about it -->
                <div class="pe-quiz-results__section pe-quiz-results__recommendations">
                    <h3 class="pe-quiz-results__section-title" data-toc="true">What you can do about it</h3>
                    <?php if (isset($report_output['recommendations']['body_exercises']) && is_array($report_output['recommendations']['body_exercises'])): ?>
                        <div class="pe-quiz-results__grid">
                            <div class="pe-quiz-results__recommendations-title">
                                <h4>For your body</h4>
                                <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/pe-quiz-image--body.png'; ?>">
                            </div>
                            <?php foreach ($report_output['recommendations']['body_exercises'] as $exercise): ?>
                                <?php if (is_array($exercise) && isset($exercise['name']) && isset($exercise['description'])): ?>
                                    <div class="pe-quiz-results__grid-item">
                                        <h4><?php echo $exercise['name']; ?></h4>
                                        <p><?php echo $exercise['description']; ?></p>

                                        <?php if (isset($exercise['instructions']) && is_array($exercise['instructions'])): ?>
                                            <p><strong>Instructions</strong></p>
                                            <ol>
                                                <?php foreach ($exercise['instructions'] as $instruction): ?>
                                                    <li><?php echo $instruction; ?></li>
                                                <?php endforeach; ?>
                                            </ol>
                                        <?php endif; ?>

                                        <?php if (isset($exercise['duration_and_frequency'])): ?>
                                            <p><strong>Duration & Frequency</strong></p>
                                            <ul>
                                                <li><?php echo $exercise['duration_and_frequency']; ?></li>
                                            </ul>
                                        <?php endif; ?>

                                        <!-- <?php if (isset($exercise['benefits']) && is_array($exercise['benefits'])): ?>
                                    <p><strong>Benefits:</strong></p>
                                    <ul>
                                        <?php foreach ($exercise['benefits'] as $benefit): ?>
                                            <li><?php echo $benefit; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>

                                <?php if (isset($exercise['modifications']) && is_array($exercise['modifications'])): ?>
                                    <p><strong>Modifications</strong></p>
                                    <ul>
                                        <?php foreach ($exercise['modifications'] as $modification): ?>
                                            <li><?php echo $modification; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>

                                <?php if (isset($exercise['safety_considerations']) && is_array($exercise['safety_considerations'])): ?>
                                    <p><strong>Safety Considerations</strong></p>
                                    <ul>
                                        <?php foreach ($exercise['safety_considerations'] as $safety): ?>
                                            <li><?php echo $safety; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?> -->
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($report_output['recommendations']['emotional_techniques']) && is_array($report_output['recommendations']['emotional_techniques'])): ?>
                        <div class="pe-quiz-results__grid">
                            <div class="pe-quiz-results__recommendations-title">
                                <h4>For your mind</h4>
                                <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/pe-quiz-image--mind.png'; ?>">
                            </div>
                            <?php foreach ($report_output['recommendations']['emotional_techniques'] as $technique): ?>
                                <?php if (is_array($technique) && isset($technique['name']) && isset($technique['description'])): ?>
                                    <div class="pe-quiz-results__grid-item">
                                        <h4><?php echo $technique['name']; ?></h4>
                                        <p><?php echo $technique['description']; ?></p>

                                        <?php if (isset($technique['instructions']) && is_array($technique['instructions'])): ?>
                                            <p><strong>Instructions</strong></p>
                                            <ol>
                                                <?php foreach ($technique['instructions'] as $instruction): ?>
                                                    <li><?php echo $instruction; ?></li>
                                                <?php endforeach; ?>
                                            </ol>
                                        <?php endif; ?>

                                        <?php if (isset($technique['duration_and_frequency'])): ?>
                                            <p><strong>Duration & Frequency</strong></p>
                                            <ul>
                                                <li><?php echo $technique['duration_and_frequency']; ?></li>
                                            </ul>
                                        <?php endif; ?>

                                        <!-- <?php if (isset($technique['benefits']) && is_array($technique['benefits'])): ?>
                                    <p><strong>Benefits</strong></p>
                                    <ul>
                                        <?php foreach ($technique['benefits'] as $benefit): ?>
                                            <li><?php echo $benefit; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>

                                <?php if (isset($technique['modifications']) && is_array($technique['modifications'])): ?>
                                    <p><strong>Modifications</strong></p>
                                    <ul>
                                        <?php foreach ($technique['modifications'] as $modification): ?>
                                            <li><?php echo $modification; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>

                                <?php if (isset($technique['safety_considerations']) && is_array($technique['safety_considerations'])): ?>
                                    <p><strong>Safety Considerations</strong></p>
                                    <ul>
                                        <?php foreach ($technique['safety_considerations'] as $safety): ?>
                                            <li><?php echo $safety; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?> -->
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="pe-quiz-results__section">
                    <h3 class="pe-quiz-results__section-title" data-toc="true">Professional support matters</h3>
                    <?php if (is_array($report_output['recommendations']['professional_support'])): ?>
                        <div class="pe-quiz-results__grid">
                            <?php if (isset($report_output['recommendations']['professional_support']['therapy_types'])): ?>
                                <div class="pe-quiz-results__grid-item">
                                    <h4>Recommended Therapy Types</h4>
                                    <p><?php echo $report_output['recommendations']['professional_support']['therapy_types']; ?></p>
                                    <img class="pe-quiz-results__grid-item-number" src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/numbers/1.svg'; ?>">
                                </div>
                            <?php endif; ?>
                            <?php if (isset($report_output['recommendations']['professional_support']['credentials_required'])): ?>
                                <div class="pe-quiz-results__grid-item">
                                    <h4>Credentials to Look For</h4>
                                    <p><?php echo $report_output['recommendations']['professional_support']['credentials_required']; ?></p>
                                    <img class="pe-quiz-results__grid-item-number" src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/numbers/2.svg'; ?>">
                                </div>
                            <?php endif; ?>
                            <?php if (isset($report_output['recommendations']['professional_support']['treatment_expectations'])): ?>
                                <div class="pe-quiz-results__grid-item">
                                    <h4>What to Expect</h4>
                                    <p><?php echo $report_output['recommendations']['professional_support']['treatment_expectations']; ?></p>
                                    <img class="pe-quiz-results__grid-item-number" src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/numbers/3.svg'; ?>">
                                </div>
                            <?php endif; ?>
                            <?php if (isset($report_output['recommendations']['professional_support']['finding_professionals'])): ?>
                                <div class="pe-quiz-results__grid-item">
                                    <h4>How to Find Professionals</h4>
                                    <p><?php echo $report_output['recommendations']['professional_support']['finding_professionals']; ?></p>
                                    <img class="pe-quiz-results__grid-item-number" src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/numbers/4.svg'; ?>">
                                </div>
                            <?php endif; ?>
                            <?php if (isset($report_output['recommendations']['professional_support']['cost_considerations'])): ?>
                                <div class="pe-quiz-results__grid-item">
                                    <h4>Cost Considerations</h4>
                                    <p><?php echo $report_output['recommendations']['professional_support']['cost_considerations']; ?></p>
                                    <img class="pe-quiz-results__grid-item-number" src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/numbers/5.svg'; ?>">
                                </div>
                            <?php endif; ?>
                            <?php if (isset($report_output['recommendations']['professional_support']['time_commitment'])): ?>
                                <div class="pe-quiz-results__grid-item">
                                    <h4>Time Commitment</h4>
                                    <p><?php echo $report_output['recommendations']['professional_support']['time_commitment']; ?></p>
                                    <img class="pe-quiz-results__grid-item-number" src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/numbers/6.svg'; ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p class="pe-quiz-results__section-highlight"><?php echo $report_output['recommendations']['professional_support']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- 5. Emerging treatment -->
                <?php if (
                    $report_input['bipolar_disorder'] == 'NONE' &&
                    isset($report_output['recommendations']['unconvetional_treatments']) &&
                    isset($report_input['personal_info']['age']) &&
                    $report_input['personal_info']['age'] >= 17 &&
                    isset($report_output['analysis']['severity']) && 
                    (
                        strtolower($report_output['analysis']['severity']) == 'moderate' ||
                        strtolower($report_output['analysis']['severity']) == 'severe' ||
                        strtolower($report_output['analysis']['severity']) == 'extreme'
                    )
                ): ?>
                    <div class="pe-quiz-results__section">
                        <h3 class="pe-quiz-results__section-title" data-toc="true">Emerging treatment</h3>
                        <p class="pe-quiz-results__section-highlight"><?php echo $report_output['recommendations']['unconvetional_treatments']; ?></p>
                    </div>
                <?php endif; ?>

                <!-- 6. One practice right now -->
                <div class="pe-quiz-results__section">
                    <h3 class="pe-quiz-results__section-title" data-toc="true">One practice right now</h3>
                    <?php if (isset($report_output['recommendations']['immediate_exercise']) && is_array($report_output['recommendations']['immediate_exercise']) && isset($report_output['recommendations']['immediate_exercise']['name']) && isset($report_output['recommendations']['immediate_exercise']['description'])): ?>
                        <div class="pe-quiz-results__grid-item">
                            <h4><?php echo $report_output['recommendations']['immediate_exercise']['name']; ?></h4>
                            <p><?php echo $report_output['recommendations']['immediate_exercise']['description']; ?></p>

                            <?php if (isset($report_output['recommendations']['immediate_exercise']['instructions']) && is_array($report_output['recommendations']['immediate_exercise']['instructions'])): ?>
                                <p><strong>Instructions</strong></p>
                                <ol>
                                    <?php foreach ($report_output['recommendations']['immediate_exercise']['instructions'] as $instruction): ?>
                                        <li><?php echo $instruction; ?></li>
                                    <?php endforeach; ?>
                                </ol>
                            <?php endif; ?>

                            <?php if (isset($report_output['recommendations']['immediate_exercise']['duration_and_frequency'])): ?>
                                <p><strong>Duration & Frequency</strong></p>
                                <ul>
                                    <li><?php echo $report_output['recommendations']['immediate_exercise']['duration_and_frequency']; ?></li>
                                </ul>
                            <?php endif; ?>

                            <?php if (isset($report_output['recommendations']['immediate_exercise']['benefits']) && is_array($report_output['recommendations']['immediate_exercise']['benefits'])): ?>
                                <p><strong>Benefits</strong></p>
                                <ul>
                                    <?php foreach ($report_output['recommendations']['immediate_exercise']['benefits'] as $benefit): ?>
                                        <li><?php echo $benefit; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if (isset($report_output['recommendations']['immediate_exercise']['modifications']) && is_array($report_output['recommendations']['immediate_exercise']['modifications'])): ?>
                                <p><strong>Modifications</strong></p>
                                <ul>
                                    <?php foreach ($report_output['recommendations']['immediate_exercise']['modifications'] as $modification): ?>
                                        <li><?php echo $modification; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if (isset($report_output['recommendations']['immediate_exercise']['safety_considerations']) && is_array($report_output['recommendations']['immediate_exercise']['safety_considerations'])): ?>
                                <p><strong>Safety Considerations</strong></p>
                                <ul>
                                    <?php foreach ($report_output['recommendations']['immediate_exercise']['safety_considerations'] as $safety): ?>
                                        <li><?php echo $safety; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="pe-quiz-results__section-highlight"><?php echo $report_output['recommendations']['immediate_exercise']; ?></div>
                    <?php endif; ?>
                </div>

                <?php print_r($report_output['relevant_links']); ?>
                <!-- 7. Articles and sources -->
                <?php if (isset($report_output['relevant_links']['articles']) && is_array($report_output['relevant_links']['articles'])): ?>
                    <div class="pe-quiz-results__section">
                        <div class="pe-quiz-results__accordion">
                            <div class="pe-quiz-results__accordion-header">
                                <h3 class="pe-quiz-results__section-title" data-toc="true">Recommended articles</h3>
                                <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/icons/arrow-down.svg'; ?>">
                            </div>
                            <div class="pe-quiz-results__accordion-body">
                                <?php foreach ($report_output['relevant_links']['articles'] as $article): ?>
                                    <a class="pe-quiz-results__accordion-link" href="<?php echo esc_url($article['url']); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo $article['title']; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($scientific_links['links']) && is_array($scientific_links['links'])): ?>
                    <div class="pe-quiz-results__section">
                        <div class="pe-quiz-results__accordion">
                            <div class="pe-quiz-results__accordion-header">
                                <h3 class="pe-quiz-results__section-title" data-toc="true">Scientific sources</h3>
                                <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/icons/arrow-down.svg'; ?>">
                            </div>
                            <div class="pe-quiz-results__accordion-body">
                                <?php foreach ($scientific_links['links'] as $link): ?>
                                    <a class="pe-quiz-results__accordion-link" href="<?php echo esc_url($link['url']); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo $link['title']; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Encouragement Section -->
                <?php if (isset($encouragement)): ?>
                    <div class="pe-quiz-results__section">
                        <h3 class="pe-quiz-results__section-title">❤️ And remember</h3>
                        <h3 class="pe-quiz-results__italics"><?php echo $encouragement; ?></h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="article-v2__sidebar">
            
        </div>

    </div>

</div>

</article>

<?php
get_template_part('template-parts/mosaics/complex');
?> 


<?php

get_footer();
