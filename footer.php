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
$term = get_queried_object();
if (
    !is_front_page() &&
    !(is_category('diagnostics') || (is_tax() && $term && !is_wp_error($term) && $term->slug === 'diagnostics'))
) :
    get_template_part('template-parts/sections/tests');
endif;
?>

<?php if ( is_front_page() ) : ?>
<section class="section-v2 container community-section">
    <h2>Meaningful connection <br>can improve your well-being</h2>
    <div class="community-section__text">
        <p>Come join our little community inner talks</p>
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
<?php endif; ?>

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

    <?php if ( !is_front_page() ) : ?>
    <div class="community-banner">
        <div class="d-flex flex-column">
            <h4>Meaningful connection can improve your well-being</h4>
            <p>Come join our little community inner talks</p>
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
    <?php endif; ?>

    <div class="footer__copyright">
            <p>© 2025 States of Mind. All rights reserved.</p>
            <div class="footer__links">
                <a href="/privacy-policy/">
                    <?php esc_html_e('Privacy Policy', 'pe-mp-theme'); ?>
                </a>
                <a href="/terms-and-conditions/">
                    <?php esc_html_e('Terms and Conditions', 'pe-mp-theme'); ?>
                </a>
                <a href="/imprint/">
                    <?php esc_html_e('Imprint', 'pe-mp-theme'); ?>
                </a>
            </div>
        </div>
</footer>

</html>