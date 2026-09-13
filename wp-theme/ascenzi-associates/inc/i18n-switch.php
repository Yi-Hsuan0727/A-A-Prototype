<?php
/**
 * Lightweight EN / Traditional Chinese switch — no multilingual plugin.
 *
 * All visible bilingual content is stored as paired post-meta fields
 * ("{key}_en" / "{key}_zh") and resolved through ascenzi_meta() / ascenzi_t()
 * in inc/helpers.php, so switching language is just: set a cookie, reload,
 * and every template naturally renders the other language's fields.
 *
 * We additionally map the WordPress locale so core-generated strings (date
 * formats, "Leave a comment", etc.) follow along if a zh_TW translation is
 * ever installed.
 */

if (!defined('ABSPATH')) { exit; }

add_filter('locale', function ($locale) {
    if (is_admin()) { return $locale; }
    return ascenzi_current_lang() === 'zh' ? 'zh_TW' : $locale;
});

add_action('wp_head', function () {
    $lang = ascenzi_current_lang();
    echo '<meta name="ascenzi-lang" content="' . esc_attr($lang) . '">' . "\n";
});

add_filter('language_attributes', function ($output) {
    if (ascenzi_current_lang() === 'zh') {
        $output = str_replace('lang="' . get_bloginfo('language') . '"', 'lang="zh-Hant"', $output);
        if (strpos($output, 'lang=') === false) {
            $output .= ' lang="zh-Hant"';
        }
    }
    return $output;
});
