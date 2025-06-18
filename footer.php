<?php

/**
 * The template for displaying the footer
 */
?>


<?php wp_footer(); ?>
</main>
<footer class="footer">
    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/dist/images/brand/logo-placeholder--footer.svg" alt="<?php bloginfo('name'); ?>" class="footer__logo">
    <div class="corner corner--left-bottom">
        <span>@Created by</span>
    </div>
    <div class="corner corner--right-bottom">
        <span>All Rights Reserved</span>
    </div>
</footer>
</body>

</html>