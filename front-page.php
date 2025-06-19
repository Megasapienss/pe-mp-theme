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
    <h2>We’re building a new approach to mental health — rooted in science, ritual and empathy.
        Explore our guides, tools, specialists and curated knowledge base.</h2>
</section>

<section class="section">
    <div class="section__title">
        <h2 class="section__title-text">Top Articles</h2>
        <a href="#" class="section__title-link arrow-btn arrow-btn--muted">See all</a>
    </div>
    <div class="cards grid grid--3">
        <?php
        // Get the current post's categories
        $categories = get_the_category();
        $category_ids = array();
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }

        // Query related posts
        $related_posts = new WP_Query(array(
            'category__in' => $category_ids,
            // 'post__not_in' => array(get_the_ID()),
            'posts_per_page' => 3,
            'orderby' => 'rand'
        ));

        // Display related posts
        if ($related_posts->have_posts()) :
            foreach ($related_posts->posts as $post) :
                get_template_part('template-parts/cards/post', 'simple', ['post' => $post]);
            endforeach;
        else :
            echo '<p>' . __('No related posts found.', 'pe-mp-theme') . '</p>';
        endif;
        ?>
    </div>
</section>


<section class="section">
    <div class="section__title">
        <h2 class="section__title-text">Looking for support?</h2>
        <a href="#" class="section__title-link arrow-btn arrow-btn--muted">See All Experts</a>
    </div>
    <div class="cards grid grid--3">
        <div class="card card--expert" style="background-image: url('<?= get_template_directory_uri(); ?>/dist/images/expert-1.jpg');">
            <div class="card__content">
                <h3 class="card__title">
                    Jesse Kwon
                </h3>
                <div class="card__excerpt">
                    Supports burnout recovery, emotional processing, integration after microdosing
                </div>
                <div class="card__tags">
                    <span class="label label--squared label--muted">
                        Psychedelic Coach
                    </span>
                    <span class="label label--squared label--muted">
                        San Francisco
                    </span>
                </div>
            </div>
            <div class="card__corner corner corner--right-bottom">
                <a href="#" class="arrow-btn arrow-btn--primary">
                    About
                </a>
            </div>
        </div>
        <div class="card card--expert" style="background-image: url('<?= get_template_directory_uri(); ?>/dist/images/expert-2.jpg');">
            <div class="card__content">
                <h3 class="card__title">
                    Dr. Sarah Lee
                </h3>
                <div class="card__excerpt">
                    Supports burnout recovery, emotional processing, integration after microdosing
                </div>
                <div class="card__tags">
                    <span class="label label--squared label--muted">
                        Psychedelic Coach
                    </span>
                    <span class="label label--squared label--muted">
                        San Francisco
                    </span>
                </div>
            </div>
            <div class="card__corner corner corner--right-bottom">
                <a href="#" class="arrow-btn arrow-btn--primary">
                    About
                </a>
            </div>
        </div>
        <div class="card card--expert" style="background-image: url('<?= get_template_directory_uri(); ?>/dist/images/expert-3.jpg');">
            <div class="card__content">
                <h3 class="card__title">
                    Dr. Hanna Feldman
                </h3>
                <div class="card__excerpt">
                    Supports burnout recovery, emotional processing, integration after microdosing
                </div>
                <div class="card__tags">
                    <span class="label label--squared label--muted">
                        Psychedelic Coach
                    </span>
                    <span class="label label--squared label--muted">
                        San Francisco
                    </span>
                </div>
            </div>
            <div class="card__corner corner corner--right-bottom">
                <a href="#" class="arrow-btn arrow-btn--primary">
                    About
                </a>
            </div>
        </div>
    </div>
</section>

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

<section class="section">
    <div class="section__title">
        <h2 class="section__title-text">Latest Articles</h2>
        <a href="#" class="section__title-link arrow-btn arrow-btn--muted">View all</a>
    </div>
    <div class="container container--wide cards grid grid--3">
        <?php
        // Get the current post's categories
        $categories = get_the_category();
        $category_ids = array();
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }

        // Query related posts
        $related_posts = new WP_Query(array(
            'category__in' => $category_ids,
            // 'post__not_in' => array(get_the_ID()),
            'posts_per_page' => 3,
            'orderby' => 'rand'
        ));

        // Display related posts
        if ($related_posts->have_posts()) :
            foreach ($related_posts->posts as $post) :
                get_template_part('template-parts/cards/post', 'compact', ['post' => $post]);
            endforeach;
        else :
            echo '<p>' . __('No related posts found.', 'pe-mp-theme') . '</p>';
        endif;
        ?>
    </div>
</section>

<?php
get_footer();
?>