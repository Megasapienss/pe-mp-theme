<?php

/**
 * The template for displaying the footer
 */
?>


<?php wp_footer(); ?>
</main>

<footer class="footer">
    <div class="d-flex flex-column">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/dist/images/brand/logo-v1.svg" alt="<?php bloginfo('name'); ?>" class="footer__logo">
        <div class="community-banner">
            <h4>JOIN OUR COMMUNITY STATES OF MIND</h4>
            <p>From deep psychedelic talks to live therapist support in chat, from microdosing practices to fully held emotional deep work — we’re mapping what’s next in mental health to help you find the right tool.</p>
            <div class="community-banner__actions">
                <a href="https://discord.gg/XwGZ82yeXz" target="_blank" class="arrow-btn">
                    <?php esc_html_e('Discord', 'pe-mp-theme'); ?>
                </a>
                <a href="https://www.facebook.com/groups/1281883746512308" target="_blank" class="arrow-btn">
                    <?php esc_html_e('Facebook', 'pe-mp-theme'); ?>
                </a>
                <a href="https://chat.whatsapp.com/DmBpa5sQJ01Dc9uwYUnnkB" target="_blank" class="arrow-btn">
                    <?php esc_html_e('WhatsApp', 'pe-mp-theme'); ?>
                </a>
            </div>
            <h4>Why join our community?</h4>
            <ul class="list-basic">
                <li>Get helpful guides and tips made with an evidence-based approach from those who know the topic firsthand — scientists and therapists</li>
                <li>Find support from professionals and a like-minded community — you don’t need to get through it all alone</li>
                <li>Be really heard — we are more intimate than Reddit or Shroomery, more focused on individual experience and knowledge</li>
                <li>Clear it out — we tackle the questions Reddit left hanging and no one else dares to answer</li>
            </ul>
        </div>
        <div class="d-flex footer__links">
            <a href="/privacy-policy/">
                <?php esc_html_e('Privacy Policy', 'pe-mp-theme'); ?>
            </a>
            <a href="/terms-and-conditions/">
                <?php esc_html_e('Terms and Conditions', 'pe-mp-theme'); ?>
            </a>
        </div>
    </div>
    <div class="d-flex flex-column">
        <script src="https://js-eu1.hsforms.net/forms/embed/developer/146352434.js" defer></script>
        <div class="newsletter-section__card">
            <div class="newsletter-section__content">
                <p class="heading-h6 text-accent">
                    Newsletter
                </p>
                <h2 class="heading-h1">
                    Subscribe to our Newsletter!
                </h2>
                <div class="hs-form-html" data-region="eu1" data-form-id="ab76425d-a602-4457-8367-191cb709b159" data-portal-id="146352434"></div>
            </div>
        </div>
    </div>
    <div class="corner corner--left-bottom">
        <span>@Created by States of Mind</span>
    </div>
    <div class="corner corner--right-bottom">
        <span>All Rights Reserved</span>
    </div>
</footer>

</html>