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
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <header class="header grid">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/dist/images/brand/logo-placeholder.svg" alt="<?php bloginfo('name'); ?>" class="header__logo">

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
            <a href="<?php echo esc_url(home_url('/login')); ?>" class="btn btn--muted">
                <?php esc_html_e('Start the Quiz', 'pe-mp-theme'); ?>
            </a>
        </div>
    </header>

    <main class="main">