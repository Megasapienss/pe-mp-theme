<?php
/**
 * Template part for displaying the apps/tools section
 * 
 * @package PE_MP_Theme
 */
?>

<section class="section">
    <div class="section__title">
        <h2 class="section__title-text">Helpful Tools for Integration</h2>
    </div>
    <div class="cards grid grid--2">
        <div class="card card--app">
            <img src="<?= get_template_directory_uri(); ?>/dist/images/app-placeholder.png" alt="" class="card__image">
            <div class="card__content">
                <h3 class="card__title">
                    MindTrack
                </h3>
                <div class="card__excerpt">
                    Track your mood, mindset and energy during microdosing cycles. </div>
                <div class="card__tags">
                    <span class="label label--squared label--muted">
                        Tracker App
                    </span>
                    <span class="label label--squared label--muted">
                        iOS / Android
                    </span>
                </div>
            </div>
            <div class="card__corner corner corner--right-bottom">
                <a href="#" class="arrow-btn arrow-btn--primary">
                    Download
                </a>
            </div>
        </div>
        <div class="card card--app">
            <img src="<?= get_template_directory_uri(); ?>/dist/images/app-placeholder.png" alt="" class="card__image">
            <div class="card__content">
                <h3 class="card__title">
                    JourneyNotes
                </h3>
                <div class="card__excerpt">
                    Integration journaling with prompts & voice notes.
                </div>
                <div class="card__tags">
                    <span class="label label--squared label--muted">
                        Journaling App
                    </span>
                    <span class="label label--squared label--muted">
                        iOS only
                    </span>
                </div>
            </div>
            <div class="card__corner corner corner--right-bottom">
                <a href="#" class="arrow-btn arrow-btn--primary">
                    Download
                </a>
            </div>
        </div>
    </div>
</section> 