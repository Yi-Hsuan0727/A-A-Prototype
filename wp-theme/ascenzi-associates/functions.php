<?php
/**
 * Ascenzi & Associates theme bootstrap.
 */

if (!defined('ABSPATH')) { exit; }

define('ASCENZI_VERSION', '1.0.0');
define('ASCENZI_DIR', get_template_directory());
define('ASCENZI_URI', get_template_directory_uri());

/* ---------------------------------------------------------------------------
 * Theme setup
 * ------------------------------------------------------------------------ */
function ascenzi_setup() {
    load_theme_textdomain('ascenzi', ASCENZI_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', [
        'height'      => 68,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('automatic-feed-links');

    register_nav_menus([
        'primary' => __('Primary Navigation', 'ascenzi'),
    ]);

    add_image_size('ascenzi-card', 900, 600, true);
    add_image_size('ascenzi-hero', 1600, 1200, true);
}
add_action('after_setup_theme', 'ascenzi_setup');

/**
 * Custom post types register their own rewrite slugs (services/insight),
 * so permalinks need flushing once after the theme is activated.
 */
function ascenzi_flush_rewrites() {
    ascenzi_register_service_cpt();
    ascenzi_register_insight_cpt();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'ascenzi_flush_rewrites');

/* ---------------------------------------------------------------------------
 * Includes
 * ------------------------------------------------------------------------ */
require ASCENZI_DIR . '/inc/helpers.php';
require ASCENZI_DIR . '/inc/i18n-switch.php';
require ASCENZI_DIR . '/inc/cpt-service.php';
require ASCENZI_DIR . '/inc/cpt-insight.php';
require ASCENZI_DIR . '/inc/metabox-framework.php';
require ASCENZI_DIR . '/inc/metaboxes-service.php';
require ASCENZI_DIR . '/inc/metaboxes-insight.php';
require ASCENZI_DIR . '/inc/metaboxes-home.php';
require ASCENZI_DIR . '/inc/metaboxes-about.php';
require ASCENZI_DIR . '/inc/metaboxes-privacy.php';
require ASCENZI_DIR . '/inc/theme-options.php';
require ASCENZI_DIR . '/inc/contact-form-handler.php';
require ASCENZI_DIR . '/inc/nav-services.php';

/* ---------------------------------------------------------------------------
 * Assets
 * ------------------------------------------------------------------------ */
function ascenzi_enqueue_assets() {
    wp_enqueue_style('ascenzi-fonts', 'https://fonts.googleapis.com/css2?family=Questrial&family=IBM+Plex+Mono:wght@400&family=Noto+Sans+TC:wght@400&display=swap', [], null);
    wp_enqueue_style('ascenzi-style', get_stylesheet_uri(), [], ASCENZI_VERSION);
    wp_enqueue_style('ascenzi-theme', ASCENZI_URI . '/assets/css/theme.css', ['ascenzi-style'], ASCENZI_VERSION);

    wp_enqueue_script('ascenzi-main', ASCENZI_URI . '/assets/js/main.js', [], ASCENZI_VERSION, true);

    if (is_front_page()) {
        wp_enqueue_script('d3', 'https://cdn.jsdelivr.net/npm/d3@7.9.0/dist/d3.min.js', [], '7.9.0', true);
        wp_enqueue_script('topojson-client', 'https://cdn.jsdelivr.net/npm/topojson-client@3.1.0/dist/topojson-client.min.js', [], '3.1.0', true);
        wp_enqueue_script('ascenzi-road-map', ASCENZI_URI . '/assets/js/road-map.js', ['d3', 'topojson-client'], ASCENZI_VERSION, true);
    }

    wp_localize_script('ascenzi-main', 'ascenziData', [
        'ajaxUrl'    => admin_url('admin-ajax.php'),
        'contactNonce' => wp_create_nonce('ascenzi_contact'),
        'lang'       => ascenzi_current_lang(),
        'strings'    => ascenzi_js_strings(),
    ]);
}
add_action('wp_enqueue_scripts', 'ascenzi_enqueue_assets');

function ascenzi_editor_styles() {
    add_editor_style('assets/css/theme.css');
}
add_action('after_setup_theme', 'ascenzi_editor_styles');

/**
 * The site doesn't use core emoji rendering (fonts already cover the few
 * glyphs used), so drop the emoji-detection script/style — one less
 * request and one less Worker/Blob script for browsers to run.
 */
function ascenzi_disable_emoji() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'ascenzi_disable_emoji');

/* ---------------------------------------------------------------------------
 * Strings the front-end JS needs (kept small and explicit rather than
 * exposing the whole translation table to the client).
 * ------------------------------------------------------------------------ */
function ascenzi_js_strings() {
    return [
        'submitting'        => ascenzi_t('submitting') ?: (ascenzi_current_lang() === 'zh' ? '傳送中…' : 'Sending…'),
        'submitError'       => ascenzi_t('submit_error') ?: (ascenzi_current_lang() === 'zh' ? '傳送時發生問題，請直接致電我們。' : 'Something went wrong sending that — please call us directly.'),
        'subscribed'        => ascenzi_t('subscribed'),
        'enquiryKicker'     => ascenzi_t('enquiry_kicker'),
        'partnershipKicker' => ascenzi_t('partnership_kicker'),
        'complete'          => ascenzi_t('complete'),
        'stepOf'            => ascenzi_t('step_of'),
    ];
}

/* ---------------------------------------------------------------------------
 * Body classes for header/JS state
 * ------------------------------------------------------------------------ */
function ascenzi_body_class($classes) {
    $classes[] = 'lang-' . ascenzi_current_lang();
    return $classes;
}
add_filter('body_class', 'ascenzi_body_class');
