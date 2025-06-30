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
    <link rel="icon" type="image/svg+xml" href="<?php echo esc_url(get_template_directory_uri()); ?>/favicon.svg">
    <link rel="shortcut icon" type="image/svg+xml" href="<?php echo esc_url(get_template_directory_uri()); ?>/favicon.svg">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <script src="https://assets.prd.heyflow.com/builder/widget/latest/webview.js"></script>
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
    <script src="https://cdn.eu.amplitude.com/script/b1ebe560a0af47578b4306df667e3d2c.js"></script>
    <script>
        window.amplitude.add(window.sessionReplay.plugin({
            sampleRate: 1
        }));
        window.amplitude.init('b1ebe560a0af47578b4306df667e3d2c', {
            "fetchRemoteConfig": true,
            "serverZone": "EU",
            "autocapture": true
        });
    </script>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WCXW64N3"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <header class="header">
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
        </nav>

        <div class="header__actions">
            <button class="btn btn--muted" onclick="window.dispatchEvent(new CustomEvent('heyflow-modal-element:open', { detail: { modalId: '2yeWxj1NPN' }}))">
                <?php esc_html_e('Start 3 min test', 'pe-mp-theme'); ?>
            </button>
        </div>
    </header>

    <main class="main">