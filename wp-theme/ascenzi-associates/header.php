<?php
/**
 * Site header: logo, primary nav, Services mega-dropdown, language switch,
 * Contact Us button / mobile floating button.
 */
if (!defined('ABSPATH')) { exit; }
$lang = ascenzi_current_lang();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site-wrap">

    <header class="site-header" data-site-header>
        <div class="site-header__inner">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-header__logo">
                <?php if (has_custom_logo()) : the_custom_logo(); else : ?>
                    <img src="<?php echo esc_url(ASCENZI_URI . '/assets/images/logo-horizon.png'); ?>" alt="<?php bloginfo('name'); ?>">
                <?php endif; ?>
            </a>

            <button type="button" class="site-header__toggle" aria-label="<?php esc_attr_e('Menu', 'ascenzi'); ?>" aria-expanded="false" data-menu-toggle>
                <span></span><span></span><span></span>
            </button>

            <nav class="site-header__nav" data-site-nav>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-link<?php echo is_front_page() ? ' is-active' : ''; ?>"><?php esc_html_e('Home', 'ascenzi'); ?></a>
                <a href="<?php echo esc_url(ascenzi_about_url()); ?>" class="nav-link<?php echo is_page_template('template-about.php') ? ' is-active' : ''; ?>"><?php esc_html_e('About Us', 'ascenzi'); ?></a>
                <?php ascenzi_render_services_dropdown(); ?>
                <a href="<?php echo esc_url(get_post_type_archive_link('insight_post')); ?>" class="nav-link<?php echo (is_post_type_archive('insight_post') || is_singular('insight_post')) ? ' is-active' : ''; ?>"><?php esc_html_e('Insight', 'ascenzi'); ?></a>

                <div class="nav-lang-row mobile-only">
                    <?php ascenzi_render_lang_switch(); ?>
                </div>
            </nav>

            <div class="desktop-only" style="display:flex; align-items:center; gap:20px;">
                <?php ascenzi_render_lang_switch(); ?>
                <button type="button" class="contact-fab" data-open-contact="contact">
                    <?php ascenzi_icon_button_chat(); ?>
                    <span class="contact-fab__label"><?php esc_html_e('Contact Us', 'ascenzi'); ?></span>
                </button>
            </div>
        </div>
    </header>

    <button type="button" class="contact-fab mobile-only" aria-label="<?php esc_attr_e('Contact Us', 'ascenzi'); ?>" title="<?php esc_attr_e('Contact Us', 'ascenzi'); ?>" data-open-contact="contact">
        <?php ascenzi_icon_button_chat(); ?>
    </button>
