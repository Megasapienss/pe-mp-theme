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

// Get the result ID from URL parameter
$result_id = sanitize_text_field($_GET['report-id'] ?? '');

if (empty($result_id)) {
    wp_die('No result ID provided. Please provide a valid result ID.');
}

// Enqueue results scripts and styles
wp_enqueue_script('pe-quiz-results', PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/js/results.js', array(), PE_QUIZ_SYSTEM_VERSION, true);
wp_enqueue_style('pe-quiz-results', PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/css/results.css', array(), PE_QUIZ_SYSTEM_VERSION);

// Enqueue Lottie library for animated loader
wp_enqueue_script('lottie-web', 'https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js', array(), '5.12.2', true);

// Initialize database instance
$database = new PE_Quiz_Database();

// First, check the RunPod jobs table for the job data
$runpod_job = $database->get_runpod_job($result_id);

// Also check the main results table for the quiz result
global $wpdb;
$table_name = PE_Quiz_Config::get_table_name('results');
$result = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$table_name} WHERE runpod_job_id = %s",
    $result_id
));

// Debug logging
error_log('PE Quiz Results Template - Job ID: ' . $result_id);
error_log('PE Quiz Results Template - RunPod job found: ' . ($runpod_job ? 'Yes' : 'No'));
error_log('PE Quiz Results Template - Main result found: ' . ($result ? 'Yes' : 'No'));

// Prepare initial data for JavaScript
$initial_data = array(
    'resultId' => $result_id,
    'hasCachedResult' => false,
    'cachedResult' => null,
    'shouldCheckStatus' => true,
    'error' => null
);

if ($runpod_job) {
    $initial_data['hasCachedResult'] = true;
    $initial_data['cachedResult'] = $runpod_job;

    // If we have RunPod output, don't check status
    if ($runpod_job->output_data) {
        $initial_data['shouldCheckStatus'] = false;
        error_log('PE Quiz Results Template - Using RunPod job data with output');
    } else {
        error_log('PE Quiz Results Template - Using RunPod job data without output, will check status');
    }
} elseif ($result) {
    // Fallback to main results table (for old jobs)
    $initial_data['hasCachedResult'] = true;
    $initial_data['cachedResult'] = $result;

    // If we have RunPod output, don't check status
    if ($result->runpod_output) {
        $initial_data['shouldCheckStatus'] = false;
        error_log('PE Quiz Results Template - Using main result table data with output');
    } else {
        error_log('PE Quiz Results Template - Using main result table data without output, will check status');
    }
} else {
    error_log('PE Quiz Results Template - No cached data found, checking RunPod API');
    // Check if the result exists in RunPod
    $config = PE_Quiz_Config::get_runpod_config();
    $status_endpoint = str_replace('/run', '/status', $config['endpoint']);

    $response = wp_remote_get(
        $status_endpoint . "/{$result_id}",
        array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $config['api_key']
            ),
            'timeout' => $config['timeout']
        )
    );

    if (is_wp_error($response)) {
        $initial_data['error'] = array(
            'message' => 'Failed to check result status. Please try again later.',
            'code' => 'wordpress_error',
            'details' => $response->get_error_message()
        );
        error_log('PE Quiz Results Template - WordPress error: ' . $response->get_error_message());
    } else {
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = json_decode(wp_remote_retrieve_body($response), true);

        error_log('PE Quiz Results Template - RunPod API response code: ' . $response_code);
        error_log('PE Quiz Results Template - RunPod API response body: ' . json_encode($response_body));

        if ($response_code !== 200 || !$response_body) {
            $initial_data['error'] = array(
                'message' => 'Result not found. Please try taking the quiz again.',
                'code' => 'runpod_error',
                'details' => array(
                    'status_code' => $response_code,
                    'response' => $response_body
                )
            );
        } else {
            // If we got a successful response from RunPod, save it to our database
            if (isset($response_body['status']) && $response_body['status'] === 'COMPLETED' && isset($response_body['output'])) {
                // Save to RunPod jobs table if it doesn't exist
                if (!$runpod_job) {
                    $database->save_runpod_job($result_id, $result ? $result->id : null, null);
                }
                $database->update_runpod_job($result_id, 'COMPLETED', $response_body['output']);
                error_log('PE Quiz Results Template - Saved completed job to database');
            }
        }
    }
}

// Remove any null values from initial_data to avoid JSON encoding issues
$initial_data = array_filter($initial_data, function ($value) {
    return $value !== null;
});

// Localize script with data
wp_localize_script('pe-quiz-results', 'peQuizResults', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('pe_quiz_check_status'),
    'initialData' => $initial_data,
    'pluginUrl' => PE_QUIZ_SYSTEM_PLUGIN_URL
));

get_header();
?>

<section class="single-title-v2 container">
<?php
get_template_part('template-parts/components/breadcrumbs', 'rankmath', array(
    'class' => 'breadcrumbs breadcrumbs--dark single-title-v2__breadcrumbs'
));
?>
</section>

<div class="pe-quiz-results" data-result-id="<?php echo esc_attr($result_id); ?>">

    <?php if (isset($initial_data['error'])): ?>
        <div class="pe-quiz-results__error">
            <div class="pe-quiz-results__lottie-container">
                <div id="pe-quiz-lottie-loader"></div>
            </div>
            <h2>There was an error on our side</h2>
            <a href="/diagnostics/" class="btn btn--accent">Start the test over</a>
        </div>
    <?php elseif (
        $initial_data['hasCachedResult'] &&
        (($runpod_job && $runpod_job->output_data) || ($result && $result->runpod_output))
    ): ?>
        <!-- Display completed results -->
        <article class="article-v2 report-v2 container">
            <?php
            // Use the utility function to extract content
            $content_data = PE_Quiz_Utils::extract_runpod_content(
                $runpod_job && $runpod_job->output_data ? $runpod_job->output_data : 
                (!empty($result->runpod_output) ? json_decode($result->runpod_output, true) : null)
            );
            
            $report = $content_data['has_content'] ? $content_data['report'] : null;
            // Extract all data variables
            $report_input = $content_data['input'] ?? array();
            $report_output = $content_data['output'] ?? array();
            $personal_info = isset($report_input['personal_info']) ? $report_input['personal_info'] : array();
            $quiz_responses = isset($report_input['quiz_responses']) ? $report_input['quiz_responses'] : array();
            $quiz_metadata = isset($report_input['quiz_metadata']) ? $report_input['quiz_metadata'] : array();
            $raw_input = isset($report_input['raw_input']) ? $report_input['raw_input'] : array();
            
            // V2 structure data
            $report_data = isset($report['report']) ? $report['report'] : array();
            $crisis_support = isset($report['crisis_support']) ? $report['crisis_support'] : array();
            $relevant_links = isset($report['relevant_links']) ? $report['relevant_links'] : array();
            
            // V2 specific data
            $condition_data = isset($report_data['condition']) ? $report_data['condition'] : array();
            $body_and_mind_state = isset($report_data['body_and_mind_state']) ? $report_data['body_and_mind_state'] : '';
            $changes_with_practices = isset($report_data['changes_with_practices']) ? $report_data['changes_with_practices'] : array();
            $changes_with_therapist = isset($report_data['changes_with_therapist']) ? $report_data['changes_with_therapist'] : array();
            $encouragement = isset($report_data['encouragement']) ? $report_data['encouragement'] : '';
            $important_notes = isset($report_data['important_notes']) ? $report_data['important_notes'] : '';
            $information = isset($report_data['information']) ? $report_data['information'] : '';
            
            // V2 recommendations
            $mental_health_recommendations = isset($report_data['mental_health_recommendations']) ? $report_data['mental_health_recommendations'] : array();
            $nutrition_recommendations = isset($report_data['nutrition_recommendations']) ? $report_data['nutrition_recommendations'] : array();
            $physical_activity_recommendations = isset($report_data['physical_activity_recommendations']) ? $report_data['physical_activity_recommendations'] : array();
            $sleep_recommendations = isset($report_data['sleep_recommendations']) ? $report_data['sleep_recommendations'] : array();
            $what_you_can_do = isset($report_data['what_you_can_do']) ? $report_data['what_you_can_do'] : '';

            // Legacy data (for backward compatibility)
            $analysis = isset($report['analysis']) ? $report['analysis'] : array();
            $recommendations = isset($report['recommendations']) ? $report['recommendations'] : array();
            $scientific_links = isset($report['scientific_links']) ? $report['scientific_links'] : array();

            // Determine severity from V2 structure or legacy
            $severity_v2 = '';
            if (isset($condition_data['diagnosis_score'])) {
                $severity_v2 = ucfirst($condition_data['diagnosis_score']);
            } elseif (isset($analysis['severity'])) {
                $severity_v2 = $analysis['severity'];
            }

            // Determine condition name from V2 structure or legacy
            $condition_name = '';
            if (isset($condition_data['diagnosis_name'])) {
                $condition_name = $condition_data['diagnosis_name'];
            } elseif (isset($analysis['condition'])) {
                $condition_name = $analysis['condition'];
            }

            ?>

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
                <h1 class="article-v2__title"><?php echo $condition_name; ?> Assessment Report</h1>
                <p class="heading-h4"><span class="bold">Severity:</span> <?php echo $severity_v2 ? $severity_v2 : $severity; ?></p>

                <div class="report-v2__graph">
                    <!-- <img src="<?= $background_url; ?>"> -->
                </div>

                <div class="report-v2__actions">
                    <button class="btn btn--secondary btn--48 share-trigger">Invite others to take this test</button>
                    <a href="/diagnostics/" class="btn btn--secondary btn--48">Take another test</a>
                </div>

                <div class="article-v2__content-wrapper">
                    <?php if (get_the_content()): ?>      
                        <?php the_content(); ?>
                    <?php else: ?>

                    <div class="report-v2__main-content">
                        
                        <div class="pe-quiz-results__main">
                            <div class="pe-quiz-results__main-content">

                            <!-- V2 CRISIS SUPPORT BANNER -->
                            <?php if (
                                $crisis_support && 
                                is_array($crisis_support) && 
                                !empty($crisis_support) && 
                                (
                                    in_array(strtolower($severity), array('moderate', 'severe', 'extreme')) ||
                                    in_array(strtolower($severity_v2), array('moderate', 'severe', 'extreme'))
                                )
                            ): ?>
                                <div class="pe-quiz-results__banner">
                                    <h4 class="pe-quiz-results__banner-title">If you're in crisis:</h4>
                                    <div class="pe-quiz-results__banner-text">
                                        <?php foreach ($crisis_support as $key => $crisis_support_item): ?>
                                            <div>
                                                <p><strong><?php echo $key + 1; ?>. <?php echo esc_html($crisis_support_item['name']); ?></strong></p>
                                                <p><?php echo esc_html($crisis_support_item['description']); ?></p>
                                                <div class="pe-quiz-results__banner-contacts">
                                                    <?php if (isset($crisis_support_item['phone_number']) && $crisis_support_item['phone_number'] != 'NULL'): ?>
                                                        <p><strong>Phone:</strong> <a href="tel:<?php echo esc_attr($crisis_support_item['phone_number']); ?>"><?php echo esc_html($crisis_support_item['phone_number']); ?></a></p>
                                                    <?php endif; ?>
                                                    <?php if (isset($crisis_support_item['email']) && $crisis_support_item['email'] != 'NULL'): ?>
                                                        <p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($crisis_support_item['email']); ?>"><?php echo esc_html($crisis_support_item['email']); ?></a></p>
                                                    <?php endif; ?>
                                                    <?php if (isset($crisis_support_item['website']) && $crisis_support_item['website'] != 'NULL'): ?>
                                                        <?php
                                                        $website_url = $crisis_support_item['website'];
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
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- V2 ENCOURAGEMENT SECTION -->
                            <?php if ($encouragement): ?>
                                <div class="pe-quiz-results__section pe-quiz-results__section--encouragement">
                                    <h4 class="pe-quiz-results__section-title">❤️ And remember</h4>
                                    <h4 class="pe-quiz-results__italics"><?php echo esc_html($encouragement); ?></h4>
                                </div>
                            <?php endif; ?>

                            <!-- V2 BODY AND MIND STATE SECTION -->
                            <?php if ($body_and_mind_state): ?>
                            <div class="pe-quiz-results__section">
                                <h4 class="pe-quiz-results__section-title" data-toc="true">What is happening inside</h4>
    
                                <?php if ($information): ?>
                                    <div class="pe-quiz-results__grid-item">
                                        <p><?php echo esc_html($information); ?></p>
                                    </div>
                                <?php endif; ?>
                                <h5>What is going on in your brain and body now</h5>
                                <p><?php echo esc_html($body_and_mind_state); ?></p>
                            </div>
                            <?php endif; ?>

                            <!-- V2 CHANGES WITH THERAPIST SECTION -->
                            <?php if ($changes_with_therapist && is_array($changes_with_therapist) && !empty($changes_with_therapist)): ?>
                            <div class="pe-quiz-results__section">
                                <h5 class="pe-quiz-results__section-title" data-toc="true">Changes after going to a therapist</h5>
                                <ul class="pe-quiz-results__list">
                                    <?php if (isset($changes_with_therapist['week_1'])): ?>
                                    <li class="pe-quiz-results__list-item">
                                        <b>1 Week – </b><?php echo esc_html($changes_with_therapist['week_1']); ?></p>
                                    </li>
                                    <?php endif; ?>
                                    <?php if (isset($changes_with_therapist['week_4'])): ?>
                                        <li class="pe-quiz-results__list-item">
                                        <b>4 Weeks – </b><?php echo esc_html($changes_with_therapist['week_4']); ?></p>
                                    </li>
                                    <?php endif; ?>
                                    <?php if (isset($changes_with_therapist['monht_3'])): ?>
                                    <li class="pe-quiz-results__list-item">
                                        <b>3 Months – </b><?php echo esc_html($changes_with_therapist['monht_3']); ?></p>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <?php endif; ?>

                            <!-- V2 CHANGES WITH PRACTICES SECTION -->
                            <?php if ($changes_with_practices && is_array($changes_with_practices) && !empty($changes_with_practices)): ?>
                            <div class="pe-quiz-results__section">
                                <h5 class="pe-quiz-results__section-title" data-toc="true">Changes with practices</h5>
                                <ul class="pe-quiz-results__list">
                                    <?php if (isset($changes_with_practices['week_1'])): ?>
                                    <li class="pe-quiz-results__list-item">
                                        <b>1 Week – </b><?php echo esc_html($changes_with_practices['week_1']); ?></p>
                                    </li>
                                    <?php endif; ?>
                                    <?php if (isset($changes_with_practices['week_4'])): ?>
                                    <li class="pe-quiz-results__list-item">
                                        <b>4 Weeks – </b><?php echo esc_html($changes_with_practices['week_4']); ?></p>
                                    </li>
                                    <?php endif; ?>
                                    <?php if (isset($changes_with_practices['monht_3'])): ?>
                                    <li class="pe-quiz-results__list-item">
                                        <b>3 Months – </b><?php echo esc_html($changes_with_practices['monht_3']); ?></p>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <?php endif; ?>

                            <!-- V2 WHAT YOU CAN DO SECTION -->
                            <?php if ($what_you_can_do): ?>
                            <div class="pe-quiz-results__section">
                                <h4 class="pe-quiz-results__section-title" data-toc="true">What you can do</h4>
                                <p class=""><?php echo esc_html($what_you_can_do); ?></p>
                            </div>
                            <?php endif; ?>

                            <!-- V2 SLEEP RECOMMENDATIONS -->
                            <?php if ($sleep_recommendations && is_array($sleep_recommendations) && isset($sleep_recommendations['instructions'])): ?>
                            <div class="pe-quiz-results__section">
                                <div class="pe-quiz-results__accordion">
                                    <div class="pe-quiz-results__accordion-header">
                                        <h4 class="pe-quiz-results__section-title" data-toc="true">Sleep</h4>
                                        <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/icons/arrow-down.svg'; ?>">
                                    </div>
                                    <div class="pe-quiz-results__accordion-body">
                                        <p class="bold">Instructions</p>
                                        <ol class="pe-quiz-results__list">
                                            <?php foreach ($sleep_recommendations['instructions'] as $instruction): ?>
                                                <?php if (is_array($instruction) && isset($instruction['instruction'])): ?>
                                                    <li class="pe-quiz-results__list-item">
                                                        <p><?php echo esc_html($instruction['instruction']); ?></p>
                                                        <?php if (isset($instruction['after_1_day'])): ?>
                                                            <p><strong>After 1 day:</strong> <?php echo esc_html($instruction['after_1_day']); ?></p>
                                                        <?php endif; ?>
                                                        <?php if (isset($instruction['after_7_days'])): ?>
                                                            <p><strong>After 7 days:</strong> <?php echo esc_html($instruction['after_7_days']); ?></p>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ol>
                                    <?php if (isset($sleep_recommendations['tip'])): ?>
                                        <p>Tip: <?php echo esc_html($sleep_recommendations['tip']); ?></p>
                                    <?php endif; ?>
                                    </div>
                                </div>        
                            </div>
                            <?php endif; ?>

                            <!-- V2 NUTRITION RECOMMENDATIONS -->
                            <?php if ($nutrition_recommendations && is_array($nutrition_recommendations) && isset($nutrition_recommendations['instructions'])): ?>
                            <div class="pe-quiz-results__section">
                                <div class="pe-quiz-results__accordion">
                                    <div class="pe-quiz-results__accordion-header">
                                        <h4 class="pe-quiz-results__section-title" data-toc="true">Nutrition</h4>
                                        <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/icons/arrow-down.svg'; ?>">
                                    </div>
                                    <div class="pe-quiz-results__accordion-body">
                                        <p class="bold">Instructions</p>
                                        <ol class="pe-quiz-results__list">
                                    <?php foreach ($nutrition_recommendations['instructions'] as $instruction): ?>
                                        <?php if (is_array($instruction) && isset($instruction['instruction'])): ?>
                                            <li class="pe-quiz-results__list-item">
                                                <p><?php echo esc_html($instruction['instruction']); ?></p>
                                                <?php if (isset($instruction['after_1_day'])): ?>
                                                    <p><strong>After 1 day:</strong> <?php echo esc_html($instruction['after_1_day']); ?></p>
                                                <?php endif; ?>
                                                <?php if (isset($instruction['after_7_days'])): ?>
                                                    <p><strong>After 7 days:</strong> <?php echo esc_html($instruction['after_7_days']); ?></p>
                                                <?php endif; ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                        </ol>
                                    <?php if (isset($nutrition_recommendations['tip'])): ?>
                                        <p>Tip: <?php echo esc_html($nutrition_recommendations['tip']); ?></p>
                                    <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- V2 PHYSICAL ACTIVITY RECOMMENDATIONS -->
                            <?php if ($physical_activity_recommendations && is_array($physical_activity_recommendations) && isset($physical_activity_recommendations['instructions'])): ?>
                            <div class="pe-quiz-results__section">
                                <div class="pe-quiz-results__accordion">
                                    <div class="pe-quiz-results__accordion-header">
                                        <h4 class="pe-quiz-results__section-title" data-toc="true">Physical activity</h4>
                                        <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/icons/arrow-down.svg'; ?>">
                                    </div>
                                    <div class="pe-quiz-results__accordion-body">
                                        <p class="bold">Instructions</p>
                                        <ol class="pe-quiz-results__list">
                                    <?php foreach ($physical_activity_recommendations['instructions'] as $instruction): ?>
                                        <?php if (is_array($instruction) && isset($instruction['instruction'])): ?>
                                            <li class="pe-quiz-results__list-item">
                                                <p><?php echo esc_html($instruction['instruction']); ?></p>
                                                <?php if (isset($instruction['after_1_day'])): ?>
                                                    <p><strong>After 1 day:</strong> <?php echo esc_html($instruction['after_1_day']); ?></p>
                                                <?php endif; ?>
                                                <?php if (isset($instruction['after_7_days'])): ?>
                                                    <p><strong>After 7 days:</strong> <?php echo esc_html($instruction['after_7_days']); ?></p>
                                                <?php endif; ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    </ol>
                                    <?php if (isset($physical_activity_recommendations['tip'])): ?>
                                        <p>Tip: <?php echo esc_html($physical_activity_recommendations['tip']); ?></p>
                                    <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>     
                            
                            <!-- V2 MENTAL HEALTH RECOMMENDATIONS -->
                            <?php if ($mental_health_recommendations && is_array($mental_health_recommendations) && isset($mental_health_recommendations['instructions'])): ?>
                            <div class="pe-quiz-results__section">
                                <div class="pe-quiz-results__accordion">
                                    <div class="pe-quiz-results__accordion-header">
                                        <h4 class="pe-quiz-results__section-title" data-toc="true">Mental health</h4>
                                        <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/icons/arrow-down.svg'; ?>">
                                    </div>
                                    <div class="pe-quiz-results__accordion-body">
                                        <p class="bold">Instructions</p>
                                        <ol class="pe-quiz-results__list">
                                    <?php foreach ($mental_health_recommendations['instructions'] as $instruction): ?>
                                        <?php if (is_array($instruction) && isset($instruction['instruction'])): ?>
                                            <li class="pe-quiz-results__list-item">
                                                <p><?php echo esc_html($instruction['instruction']); ?></p>
                                                <?php if (isset($instruction['after_1_day'])): ?>
                                                    <p><strong>After 1 day:</strong> <?php echo esc_html($instruction['after_1_day']); ?></p>
                                                <?php endif; ?>
                                                <?php if (isset($instruction['after_7_days'])): ?>
                                                    <p><strong>After 7 days:</strong> <?php echo esc_html($instruction['after_7_days']); ?></p>
                                                <?php endif; ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                        </ol>
                                    <?php if (isset($mental_health_recommendations['tip'])): ?>
                                        <p>Tip: <?php echo esc_html($mental_health_recommendations['tip']); ?></p>
                                    <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- V2 IMPORTANT NOTES SECTION -->
                            <?php if ($important_notes): ?>
                            <div class="pe-quiz-results__section">
                                <h4 class="pe-quiz-results__section-title" data-toc="true">Important notes</h4>
                                <p><?php echo esc_html($important_notes); ?></p>
                            </div>
                            <?php endif; ?>

                            <!-- LEGACY CRISIS SUPPORT BANNER (for backward compatibility) -->
                            <?php if (
                                isset($recommendations['crisis_support']) && 
                                is_array($recommendations['crisis_support']) && 
                                !empty($recommendations['crisis_support']) && 
                                (
                                    strtolower($severity) == 'moderate' ||
                                    strtolower($severity) == 'severe' ||
                                    strtolower($severity) == 'extreme'
                                )
                            ): ?>
                                <div class="pe-quiz-results__banner">
                                    <h3 class="pe-quiz-results__banner-title">If you're in crisis - contact immediate support</h3>
                                    <div class="pe-quiz-results__banner-text">
                                        <?php foreach ($recommendations['crisis_support'] as $key => $crisis_support): ?>
                                            <div>
                                                <p><strong><?php echo $key + 1; ?>. <?php echo esc_html($crisis_support['name']); ?></strong></p>
                                                <p><?php echo esc_html($crisis_support['description']); ?></p>
                                                <div class="pe-quiz-results__banner-contacts">
                                                    <?php if (isset($crisis_support['phone_number']) && $crisis_support['phone_number'] != 'NULL'): ?>
                                                        <p><strong>Phone:</strong> <a href="tel:<?php echo esc_attr($crisis_support['phone_number']); ?>"><?php echo esc_html($crisis_support['phone_number']); ?></a></p>
                                                    <?php endif; ?>
                                                    <?php if (isset($crisis_support['email']) && $crisis_support['email'] != 'NULL'): ?>
                                                        <p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($crisis_support['email']); ?>"><?php echo esc_html($crisis_support['email']); ?></a></p>
                                                    <?php endif; ?>
                                                    <?php if (isset($crisis_support['website']) && $crisis_support['website'] != 'NULL'): ?>
                                                        <?php
                                                        $website_url = $crisis_support['website'];
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
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- LEGACY SECTIONS (for backward compatibility) -->
                            <?php if (isset($analysis['body_state']) || isset($analysis['mind_state'])): ?>
                            <div class="pe-quiz-results__section">
                                <h3 class="pe-quiz-results__section-title" data-toc="true">What is happening inside</h3>
                                <?php if (isset($analysis['body_state'])): ?>
                                <h4>Body</h4>
                                <p><?php echo esc_html($analysis['body_state']); ?></p>
                                <?php endif; ?>
                                <?php if (isset($analysis['mind_state'])): ?>
                                <h4>Mind</h4>
                                <p><?php echo esc_html($analysis['mind_state']); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <?php if (isset($analysis['without_action']) || isset($analysis['with_action'])): ?>
                            <div class="pe-quiz-results__section">
                                <h3 class="pe-quiz-results__section-title" data-toc="true">Where you are now</h3>
                                <div class="pe-quiz-results__grid">
                                    <?php if (isset($analysis['without_action'])): ?>
                                    <div class="pe-quiz-results__grid-item">
                                        <h4>Without action</h4>
                                        <p><?php echo esc_html($analysis['without_action']); ?></p>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (isset($analysis['with_action'])): ?>
                                    <div class="pe-quiz-results__grid-item pe-quiz-results__grid-item--active">
                                        <h4>With action</h4>
                                        <p><?php echo esc_html($analysis['with_action']); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/pe-quiz-image--ways.avif'; ?>" alt="PE Quiz Results Hero">
                            </div>
                            <?php endif; ?>

                            <?php if (isset($analysis['why_happening'])): ?>
                            <div class="pe-quiz-results__section">
                                <h3 class="pe-quiz-results__section-title" data-toc="true">Why it's happening</h3>
                                <p><?php echo esc_html($analysis['why_happening']); ?></p>
                            </div>
                            <?php endif; ?>

                            <!-- LEGACY RECOMMENDATIONS (for backward compatibility) -->
                            <?php if (isset($recommendations['body_exercises']) && is_array($recommendations['body_exercises'])): ?>
                            <div class="pe-quiz-results__section pe-quiz-results__recommendations">
                                <h3 class="pe-quiz-results__section-title" data-toc="true">What you can do about it</h3>
                                <div class="pe-quiz-results__grid">
                                    <div class="pe-quiz-results__recommendations-title">
                                        <h4>For your body</h4>
                                        <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/pe-quiz-image--body.png'; ?>">
                                    </div>
                                    <?php foreach ($recommendations['body_exercises'] as $exercise): ?>
                                        <?php if (is_array($exercise) && isset($exercise['name']) && isset($exercise['description'])): ?>
                                            <div class="pe-quiz-results__grid-item">
                                                <h4><?php echo esc_html($exercise['name']); ?></h4>
                                                <p><?php echo esc_html($exercise['description']); ?></p>

                                                <?php if (isset($exercise['instructions']) && is_array($exercise['instructions'])): ?>
                                                    <p><strong>Instructions</strong></p>
                                                    <ol>
                                                        <?php foreach ($exercise['instructions'] as $instruction): ?>
                                                            <li><?php echo esc_html($instruction); ?></li>
                                                        <?php endforeach; ?>
                                                    </ol>
                                                <?php endif; ?>

                                                <?php if (isset($exercise['duration_and_frequency'])): ?>
                                                    <p><strong>Duration & Frequency</strong></p>
                                                    <ul>
                                                        <li><?php echo esc_html($exercise['duration_and_frequency']); ?></li>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if (isset($recommendations['emotional_techniques']) && is_array($recommendations['emotional_techniques'])): ?>
                            <div class="pe-quiz-results__section pe-quiz-results__recommendations">
                                <h3 class="pe-quiz-results__section-title" data-toc="true">What you can do about it</h3>
                                <div class="pe-quiz-results__grid">
                                    <div class="pe-quiz-results__recommendations-title">
                                        <h4>For your mind</h4>
                                        <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/pe-quiz-image--mind.png'; ?>">
                                    </div>
                                    <?php foreach ($recommendations['emotional_techniques'] as $technique): ?>
                                        <?php if (is_array($technique) && isset($technique['name']) && isset($technique['description'])): ?>
                                            <div class="pe-quiz-results__grid-item">
                                                <h4><?php echo esc_html($technique['name']); ?></h4>
                                                <p><?php echo esc_html($technique['description']); ?></p>

                                                <?php if (isset($technique['instructions']) && is_array($technique['instructions'])): ?>
                                                    <p><strong>Instructions</strong></p>
                                                    <ol>
                                                        <?php foreach ($technique['instructions'] as $instruction): ?>
                                                            <li><?php echo esc_html($instruction); ?></li>
                                                        <?php endforeach; ?>
                                                    </ol>
                                                <?php endif; ?>

                                                <?php if (isset($technique['duration_and_frequency'])): ?>
                                                    <p><strong>Duration & Frequency</strong></p>
                                                    <ul>
                                                        <li><?php echo esc_html($technique['duration_and_frequency']); ?></li>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- LEGACY PROFESSIONAL SUPPORT (for backward compatibility) -->
                            <?php if (is_array($recommendations['professional_support'])): ?>
                            <div class="pe-quiz-results__section">
                                <h3 class="pe-quiz-results__section-title" data-toc="true">Professional support matters</h3>
                                <div class="pe-quiz-results__grid">
                                    <?php if (isset($recommendations['professional_support']['therapy_types'])): ?>
                                        <div class="pe-quiz-results__grid-item">
                                            <h4>Recommended Therapy Types</h4>
                                            <p><?php echo esc_html($recommendations['professional_support']['therapy_types']); ?></p>
                                            <img class="pe-quiz-results__grid-item-number" src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/numbers/1.svg'; ?>">
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($recommendations['professional_support']['credentials_required'])): ?>
                                        <div class="pe-quiz-results__grid-item">
                                            <h4>Credentials to Look For</h4>
                                            <p><?php echo esc_html($recommendations['professional_support']['credentials_required']); ?></p>
                                            <img class="pe-quiz-results__grid-item-number" src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/numbers/2.svg'; ?>">
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($recommendations['professional_support']['treatment_expectations'])): ?>
                                        <div class="pe-quiz-results__grid-item">
                                            <h4>What to Expect</h4>
                                            <p><?php echo esc_html($recommendations['professional_support']['treatment_expectations']); ?></p>
                                            <img class="pe-quiz-results__grid-item-number" src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/numbers/3.svg'; ?>">
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($recommendations['professional_support']['finding_professionals'])): ?>
                                        <div class="pe-quiz-results__grid-item">
                                            <h4>How to Find Professionals</h4>
                                            <p><?php echo esc_html($recommendations['professional_support']['finding_professionals']); ?></p>
                                            <img class="pe-quiz-results__grid-item-number" src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/numbers/4.svg'; ?>">
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($recommendations['professional_support']['cost_considerations'])): ?>
                                        <div class="pe-quiz-results__grid-item">
                                            <h4>Cost Considerations</h4>
                                            <p><?php echo esc_html($recommendations['professional_support']['cost_considerations']); ?></p>
                                            <img class="pe-quiz-results__grid-item-number" src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/numbers/5.svg'; ?>">
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($recommendations['professional_support']['time_commitment'])): ?>
                                        <div class="pe-quiz-results__grid-item">
                                            <h4>Time Commitment</h4>
                                            <p><?php echo esc_html($recommendations['professional_support']['time_commitment']); ?></p>
                                            <img class="pe-quiz-results__grid-item-number" src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/images/numbers/6.svg'; ?>">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- LEGACY EMERGING TREATMENT (for backward compatibility) -->
                            <?php if (
                                ($assessment_data['bipolar_disorder'] ?? '') == 'NONE' &&
                                isset($recommendations['unconvetional_treatments']) &&
                                isset($assessment_data['personal_info']['age']) &&
                                $assessment_data['personal_info']['age'] >= 17 &&
                                isset($analysis['severity']) && 
                                (
                                    strtolower($analysis['severity']) == 'moderate' ||
                                    strtolower($analysis['severity']) == 'severe' ||
                                    strtolower($analysis['severity']) == 'extreme'
                                )
                            ): ?>
                                <div class="pe-quiz-results__section">
                                    <h3 class="pe-quiz-results__section-title" data-toc="true">Emerging treatment</h3>
                                    <p class="pe-quiz-results__section-highlight"><?php echo esc_html($recommendations['unconvetional_treatments']); ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- LEGACY IMMEDIATE EXERCISE (for backward compatibility) -->
                            <?php if (isset($recommendations['immediate_exercise']) && is_array($recommendations['immediate_exercise']) && isset($recommendations['immediate_exercise']['name']) && isset($recommendations['immediate_exercise']['description'])): ?>
                            <div class="pe-quiz-results__section">
                                <h3 class="pe-quiz-results__section-title" data-toc="true">One practice right now</h3>
                                <div class="pe-quiz-results__grid-item">
                                    <h4><?php echo esc_html($recommendations['immediate_exercise']['name']); ?></h4>
                                    <p><?php echo esc_html($recommendations['immediate_exercise']['description']); ?></p>

                                    <?php if (isset($recommendations['immediate_exercise']['instructions']) && is_array($recommendations['immediate_exercise']['instructions'])): ?>
                                        <p><strong>Instructions</strong></p>
                                        <ol>
                                            <?php foreach ($recommendations['immediate_exercise']['instructions'] as $instruction): ?>
                                                <li><?php echo esc_html($instruction); ?></li>
                                            <?php endforeach; ?>
                                        </ol>
                                    <?php endif; ?>

                                    <?php if (isset($recommendations['immediate_exercise']['duration_and_frequency'])): ?>
                                        <p><strong>Duration & Frequency</strong></p>
                                        <ul>
                                            <li><?php echo esc_html($recommendations['immediate_exercise']['duration_and_frequency']); ?></li>
                                        </ul>
                                    <?php endif; ?>

                                    <?php if (isset($recommendations['immediate_exercise']['benefits']) && is_array($recommendations['immediate_exercise']['benefits'])): ?>
                                        <p><strong>Benefits</strong></p>
                                        <ul>
                                            <?php foreach ($recommendations['immediate_exercise']['benefits'] as $benefit): ?>
                                                <li><?php echo esc_html($benefit); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <?php if (isset($recommendations['immediate_exercise']['modifications']) && is_array($recommendations['immediate_exercise']['modifications'])): ?>
                                        <p><strong>Modifications</strong></p>
                                        <ul>
                                            <?php foreach ($recommendations['immediate_exercise']['modifications'] as $modification): ?>
                                                <li><?php echo esc_html($modification); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <?php if (isset($recommendations['immediate_exercise']['safety_considerations']) && is_array($recommendations['immediate_exercise']['safety_considerations'])): ?>
                                        <p><strong>Safety Considerations</strong></p>
                                        <ul>
                                            <?php foreach ($recommendations['immediate_exercise']['safety_considerations'] as $safety): ?>
                                                <li><?php echo esc_html($safety); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- V2 RELEVANT LINKS SECTION -->
                            <?php if (isset($relevant_links['articles']) && is_array($relevant_links['articles'])): ?>
                                <?php if (count($relevant_links['articles']) > 0): ?>
                                <div class="pe-quiz-results__section">
                                    <div class="pe-quiz-results__accordion">
                                        <div class="pe-quiz-results__accordion-header">
                                            <h4 class="pe-quiz-results__section-title" data-toc="true">Recommended articles</h4>
                                            <img src="<?php echo PE_QUIZ_SYSTEM_PLUGIN_URL . 'assets/icons/arrow-down.svg'; ?>">
                                        </div>
                                        <div class="pe-quiz-results__accordion-body">
                                            <?php foreach ($relevant_links['articles'] as $article): ?>
                                                <a class="pe-quiz-results__accordion-link" href="<?php echo esc_url($article['url']); ?>" target="_blank" rel="noopener noreferrer">
                                                    <?php echo esc_html($article['title']); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- LEGACY SCIENTIFIC LINKS (for backward compatibility) -->
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
                                                    <?php echo esc_html($link['title']); ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                
                    <?php if (!$report || !is_array($report)): ?>
                        <div class="pe-quiz-results__error">
                            <h2 class="pe-quiz-results__error-title">No Results Available</h2>
                            <p class="pe-quiz-results__error-message">Unable to load quiz results. Please try again later.</p>
                            <?php if ($report): ?>
                                <p class="pe-quiz-results__error-debug">Debug: Report data type: <?php echo gettype($report); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php endif; ?>       

                    <div class="article-v2__sidebar">
                        
                    </div>

                </div>

            </div>

        </article>
    <?php endif; ?>

    <!-- Loading state (always present, controlled by JavaScript) -->
    <div class="pe-quiz-results__loading" style="display: none;">
        <div class="pe-quiz-results__loader">
            <div class="pe-quiz-results__lottie-container">
                <div id="pe-quiz-lottie-loader"></div>
            </div>
            <h2 class="pe-quiz-results__loader-title">Creating your results</h2>
            <p class="pe-quiz-results__loader-message">
                This takes about 40 seconds. Our system matches your profile (age, gender, and medication details) with expert guidance, selecting the best practices and recommendations from over 100 evidence-based options. Please don't close this tab—your results will load automatically.
            </p>
            <p class="pe-quiz-results__loader-message">
                While you wait, breathe along with our animation. Even 30 seconds of slow, calm breathing can soothe your nervous system and boost your productivity and creativity.
            </p>
            <div class="pe-quiz-results__progress">
                <div class="pe-quiz-results__progress-bar"></div>
            </div>
        </div>
    </div>
</div>

<?php
get_template_part('template-parts/mosaics/recommendations');
?> 

<?php
get_footer();
?>
