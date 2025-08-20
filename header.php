<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package PE_MP_Theme
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if (is_single() || is_page()): ?>
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="article" />
        <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>" />
        <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>" />
        <meta property="og:description" content="<?php echo esc_attr(get_the_excerpt() ?: get_bloginfo('description')); ?>" />
        <?php if (has_post_thumbnail()): ?>
            <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url('large')); ?>" />
        <?php endif; ?>

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image" />
        <meta property="twitter:url" content="<?php echo esc_url(get_permalink()); ?>" />
        <meta property="twitter:title" content="<?php echo esc_attr(get_the_title()); ?>" />
        <meta property="twitter:description" content="<?php echo esc_attr(get_the_excerpt() ?: get_bloginfo('description')); ?>" />
        <?php if (has_post_thumbnail()): ?>
            <meta property="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url('large')); ?>" />
        <?php endif; ?>
    <?php endif; ?>
    <link rel="icon" type="image/svg+xml" href="<?php echo esc_url(get_template_directory_uri()); ?>/favicon.svg">
    <link rel="shortcut icon" type="image/svg+xml" href="<?php echo esc_url(get_template_directory_uri()); ?>/favicon.svg">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-WCXW64N3');
    </script>
    <!-- End Google Tag Manager -->
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WCXW64N3"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <header class="header header--fixed">
        <div class="header__content">
        <a href="<?php echo esc_url(home_url('/')); ?>">
            <img title="Go to Homepage" src="<?php echo esc_url(get_template_directory_uri()); ?>/dist/images/brand/logo-v1.svg" alt="<?php bloginfo('name'); ?>" class="header__logo">
        </a>

        <nav class="header__navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
                'menu_class'     => 'header__menu',
                'fallback_cb'    => false,
            ));
            ?>

            <img class="header__toggle toggle--off-canvas--menu" src="<?php echo esc_url(get_template_directory_uri()); ?>/dist/icons/icon-menu-open.svg" alt="Menu">
        </nav>

        <div class="header__actions">
            <?php
            // Get custom header CTA data
            $custom_cta = pe_mp_get_header_cta_data();
            
            if ($custom_cta) {
                // Use custom CTA
                $cta_text = $custom_cta['text'];
                $cta_link = $custom_cta['link'];
            } else {
                // Use default CTA
                $cta_text = __('Start 1 min test', 'pe-mp-theme');
                $cta_link = '/tests/assessment/';
            }
            ?>
            <a href="<?php echo esc_url($cta_link); ?>" class="btn btn--56 btn--accent">
                <?php echo esc_html($cta_text); ?>
            </a>
        </div>
        </div>
        
    </header>
    <aside class="off-canvas off-canvas--menu">
        <div class="off-canvas__header">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img title="Go to Homepage" src="<?php echo esc_url(get_template_directory_uri()); ?>/dist/images/brand/logo-v1.svg" alt="<?php bloginfo('name'); ?>" class="header__logo">
            </a>
            <img class="header__toggle toggle--off-canvas--menu" src="<?php echo esc_url(get_template_directory_uri()); ?>/dist/icons/icon-menu-close.svg" alt="Menu">
        </div>
        <nav class="off-canvas__navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
                'menu_class'     => 'off-canvas__menu',
                'fallback_cb'    => false,
            ));
            ?>
        </nav>
        <div class="off-canvas__actions">
            <?php
            // Get custom header CTA data (reuse the same logic)
            $custom_cta = pe_mp_get_header_cta_data();
            
            if ($custom_cta) {
                // Use custom CTA
                $cta_text = $custom_cta['text'];
                $cta_link = $custom_cta['link'];
            } else {
                // Use default CTA
                $cta_text = __('Start 1 min test', 'pe-mp-theme');
                $cta_link = '/tests/assessment/';
            }
            ?>
            <a href="<?php echo esc_url($cta_link); ?>" class="btn btn--accent">
                <?php echo esc_html($cta_text); ?>
            </a>
        </div>
    </aside>

    <main class="main">