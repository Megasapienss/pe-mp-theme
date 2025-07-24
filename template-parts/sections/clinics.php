<?php
/**
 * Template part for displaying the clinics section
 * 
 * @package PE_MP_Theme
 */
?>

<section class="section">
    <div class="section__title">
        <h2 class="section__title-text">Guided Retreats & Clinics</h2>
        <a href="/providers/" class="section__title-link arrow-btn arrow-btn--muted">Find More Clinics</a>
    </div>
    <div class="providers-section">
        <div class="banner" style="background-image: url('<?= get_template_directory_uri(); ?>/dist/images/banner-default.webp');">
            <h2 class="banner__title title-lg">Integration Journey</h2>
            <p class="banner__description body-lg">
                Your path to healing begins with safe, supported experiences. Explore retreat centers that focus on reconnection, grounding and long-term transformation.
            </p>
            <a href="/providers/" class="banner__link arrow-btn arrow-btn--muted">
                View All Retreats
            </a>
        </div>
        <div class="providers-section__list">
            <div class="card card--provider">
                <h3 class="card__title">
                    Healing Forest (Portugal)
                </h3>
                <div class="card__excerpt">
                    This forest retreat combines psilocybin-assisted integration, bodywork and silent reflection in lush natural surroundings.
                </div>
                <div class="card__tags tags">
                    <h6 class="tags__title">Program Milestones</h6>
                    <span class="label label--squared label--muted">
                        Guided microdosing protocol
                    </span>
                    <span class="label label--squared label--muted">
                        Somatic sessions & breathwork
                    </span>
                    <span class="label label--squared label--muted">
                        Shared integration circles
                    </span>
                </div>
                <div class="card__corner corner corner--right-bottom">
                    <a href="#" class="arrow-btn arrow-btn--primary">
                        Book Consultation
                    </a>
                </div>
            </div>
            <div class="card card--provider">
                <h3 class="card__title">
                    Soma Mind Clinic (Netherlands)
                </h3>
                <div class="card__excerpt">
                    An urban outpatient clinic focused on mental health recovery through structured psilocybin sessions and psychotherapy. </div>
                <div class="card__tags tags">
                    <h6 class="tags__title">Program Milestones</h6>
                    <span class="label label--squared label--muted">
                        Legal therapeutic sessions
                    </span>
                    <span class="label label--squared label--muted">
                        Trauma-informed coaching
                    </span>
                    <span class="label label--squared label--muted">
                        Ongoing psychiatric check-ins
                    </span>
                </div>
                <div class="card__corner corner corner--right-bottom">
                    <a href="#" class="arrow-btn arrow-btn--primary">
                        Book Consultation
                    </a>
                </div>
            </div>
        </div>
    </div>
</section> 