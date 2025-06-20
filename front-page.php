<?php

/**
 * The template for displaying post archives
 * 
 * @package PE_MP_Theme
 */

get_header();
?>

<section class="hero hero--banner hero--xl" style="background-image: url('<?= get_template_directory_uri(); ?>/dist/images/cover.jpg');">
    <h1 class="hero__title title-lg">Rethink Mental Health. Rethink Psychedelics.</h1>
    <h2 class="hero__description">Science-backed guidance, tools and real human stories to help you reset, reconnect, and heal — with intention.</h2>
    <a href="#topics" class="btn btn--muted">Explore Topics</a>
</section>


<section class="container about-section">
    <div class="label label--arrow">
        About Us
    </div>
    <h2>We're building a new approach to mental health — rooted in science, ritual and empathy.
        Explore our guides, tools, specialists and curated knowledge base.</h2>
</section>

<?php get_template_part('template-parts/sections/topics', null, array(
    'section_title' => 'Explore by Topic'
)); ?>

<?php get_template_part('template-parts/sections/articles', 'top'); ?>

<?php //get_template_part('template-parts/sections/experts'); 
?>

<section class="section">
    <div class="section__title">
        <h2 class="section__title-text">Guided Retreats & Clinics</h2>
        <a href="/providers/" class="section__title-link arrow-btn arrow-btn--muted">Find More Clinics</a>
    </div>
    <div class="providers-section">
        <div class="banner" style="background-image: url('<?= get_template_directory_uri(); ?>/dist/images/banner-default.jpg');">
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

<?php //get_template_part('template-parts/sections/apps'); 
?>

<?php get_template_part('template-parts/sections/articles', 'latest'); ?>

<?php get_footer(); ?>