<?php

/**
 * The template for displaying the footer
 */
?>


<?php wp_footer(); ?>
</main>

<?php
get_template_part('template-parts/sections/newsletter', 'v2');
?>

<?php
get_template_part('template-parts/sections/tests');
?>

<section class="section-v2 container community-section">
    <h2>Join our private community States of Mind</h2>
    <div class="community-section__text">
    <p>We're mapping what's next in mental health to help you find the right tool.</p>
    <p>
        From deep psychedelic talks to live therapist support in chat.<br>
        From microdosing practices to fully held emotional deep work.
    </p>
    </div>
    <div class="community-section__actions">
        <a href="https://discord.gg/XwGZ82yeXz" target="_blank" class="arrow-btn arrow-btn--primary">
            <?php esc_html_e('Discord', 'pe-mp-theme'); ?>
        </a>
        <a href="https://www.facebook.com/groups/1281883746512308" target="_blank" class="arrow-btn arrow-btn--primary">
            <?php esc_html_e('Facebook', 'pe-mp-theme'); ?>
        </a>
        <a href="https://chat.whatsapp.com/DmBpa5sQJ01Dc9uwYUnnkB" target="_blank" class="arrow-btn arrow-btn--primary">
            <?php esc_html_e('WhatsApp', 'pe-mp-theme'); ?>
        </a>
    </div>
    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/dist/images/community.webp" alt="Community">
</section>

<footer class="footer">
    <div class="footer__menu">
        <div class="footer__menu-top">
            <p>States of Mind</p>
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/dist/images/brand/logo-v1.svg" alt="<?php bloginfo('name'); ?>" class="footer__logo">
        </div>
        <div class="footer__menu-bottom">
            <div class="footer__links">
                <a href="/mental-wellness/">
                    <?php esc_html_e('Mental Wellness', 'pe-mp-theme'); ?>
                </a>
                <a href="/science-innovation/">
                    <?php esc_html_e('Science & Innovation', 'pe-mp-theme'); ?>
                </a>
                <a href="/diagnostics/">
                    <?php esc_html_e('Diagnostics', 'pe-mp-theme'); ?>
                </a>
            </div>
        </div>
    </div>
    <div class="community-banner">
        <div class="d-flex flex-column">
            <h4>Join our community States of Mind</h4>
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
        </div>
        <div class="d-flex flex-column">
            <h4>Why join our community?</h4>
            <ul class="list-basic">
                <li>Get helpful guides and tips made with an evidence-based approach from those who know the topic firsthand — scientists and therapists</li>
                <li>Find support from professionals and a like-minded community — you don’t need to get through it all alone</li>
                <li>Be really heard — we are more intimate than Reddit or Shroomery, more focused on individual experience and knowledge</li>
                <li>Clear it out — we tackle the questions Reddit left hanging and no one else dares to answer</li>
            </ul>
        </div>

        
    </div>
    <div class="footer__copyright">
            <p>© 2025 States of Mind. All rights reserved.</p>
            <div class="footer__links">
                <a href="/privacy-policy/">
                    <?php esc_html_e('Privacy Policy', 'pe-mp-theme'); ?>
                </a>
                <a href="/terms-and-conditions/">
                    <?php esc_html_e('Terms and Conditions', 'pe-mp-theme'); ?>
                </a>
            </div>
        </div>
</footer>

</html>