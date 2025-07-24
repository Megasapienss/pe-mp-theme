<?php

/**
 * The template for displaying the footer
 */
?>


<?php wp_footer(); ?>
</main>

<footer class="footer footer--focused">
    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/dist/images/brand/logo-v1.svg" alt="<?php bloginfo('name'); ?>" class="footer__logo">
    <div class="d-flex footer__links">
        <a href="/privacy-policy/">
            <?php esc_html_e('Privacy Policy', 'pe-mp-theme'); ?>
        </a>
        <a href="/terms-and-conditions/">
            <?php esc_html_e('Terms and Conditions', 'pe-mp-theme'); ?>
        </a>
    </div>
    <div class="corner corner--left-bottom">
        <span>@Created by States of Mind</span>
    </div>
    <div class="corner corner--right-bottom">
        <span>All Rights Reserved</span>
    </div>
</footer>

</html>