<?php
/**
 * Meta boxes for the page using the "Home" page template (template-home.php).
 * Package/service cards and the Insight preview are pulled automatically
 * from the Service / Insight Post post types — only Home-specific sections
 * (hero, What We Do, road timeline, partnership, FAQ, contact CTA) live here.
 */

if (!defined('ABSPATH')) { exit; }

add_action('init', function () {
    $tpl = 'template-home.php';

    new Ascenzi_Metabox('ascenzi_home_hero', __('Hero', 'ascenzi'), 'page', [
        ['key' => 'hero_title', 'label' => __('Title', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ['key' => 'hero_sub', 'label' => __('Subheading', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
        ['key' => 'hero_cta_primary', 'label' => __('Primary Button Label', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'hero_cta_secondary', 'label' => __('Secondary Button Label', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'hero_slides', 'label' => __('Carousel Photos (masked into the logo mark)', 'ascenzi'), 'type' => 'repeater', 'max' => 6, 'fields' => [
            ['key' => 'image', 'label' => __('Photo', 'ascenzi'), 'type' => 'image'],
        ]],
    ], 'normal', 'high', $tpl);

    new Ascenzi_Metabox('ascenzi_home_tagline', __('Typed Tagline', 'ascenzi'), 'page', [
        ['key' => 'tagline_a', 'label' => __('Part 1 (typed in ink color)', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'tagline_b', 'label' => __('Part 2 (typed in blue)', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
    ], 'normal', 'default', $tpl);

    new Ascenzi_Metabox('ascenzi_home_purpose', __('"What We Do" Section', 'ascenzi'), 'page', [
        ['key' => 'purpose_kicker', 'label' => __('Kicker', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'purpose_title', 'label' => __('Heading', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'purpose_body', 'label' => __('Body', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
        ['key' => 'purpose_cards', 'label' => __('Cards', 'ascenzi'), 'type' => 'repeater', 'max' => 4, 'fields' => [
            ['key' => 'title', 'label' => __('Title', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
            ['key' => 'blurb', 'label' => __('Blurb', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
            ['key' => 'image', 'label' => __('Background Photo', 'ascenzi'), 'type' => 'image'],
            ['key' => 'items', 'label' => __('Bullet Items (one per line, EN)', 'ascenzi'), 'type' => 'textarea', 'rows' => 4],
            ['key' => 'items_zh', 'label' => __('Bullet Items (one per line, 繁中)', 'ascenzi'), 'type' => 'textarea', 'rows' => 4],
        ]],
    ], 'normal', 'default', $tpl);

    new Ascenzi_Metabox('ascenzi_home_road', __('"Long Road" Timeline', 'ascenzi'), 'page', [
        ['key' => 'road_title', 'label' => __('Heading', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ['key' => 'road_body', 'label' => __('Body', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ['key' => 'road_steps', 'label' => __('Steps', 'ascenzi'), 'type' => 'repeater', 'max' => 6, 'fields' => [
            ['key' => 'icon', 'label' => __('Icon', 'ascenzi'), 'type' => 'icon'],
            ['key' => 'title', 'label' => __('Title', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
            ['key' => 'desc', 'label' => __('Description', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ]],
    ], 'normal', 'default', $tpl);

    new Ascenzi_Metabox('ascenzi_home_coverage', __('"Bridge / Coverage" Section', 'ascenzi'), 'page', [
        ['key' => 'coverage_headline', 'label' => __('Headline', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ['key' => 'coverage_highlight', 'label' => __('Highlighted Phrase (colored blue)', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'coverage_body', 'label' => __('Body', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
    ], 'normal', 'default', $tpl);

    new Ascenzi_Metabox('ascenzi_home_capabilities', __('"Services" Section Header', 'ascenzi'), 'page', [
        ['key' => 'svc_kicker', 'label' => __('Kicker', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'svc_title', 'label' => __('Heading', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'svc_body', 'label' => __('Body', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ['key' => 'svc_cta_title', 'label' => __('Blue "Not sure?" Card Title', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'svc_cta_body', 'label' => __('Blue Card Body', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ['key' => 'svc_cta_button', 'label' => __('Blue Card Button Label', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
    ], 'normal', 'default', $tpl);

    new Ascenzi_Metabox('ascenzi_home_insights', __('"Insight" Preview Header', 'ascenzi'), 'page', [
        ['key' => 'insight_kicker', 'label' => __('Kicker', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'insight_title', 'label' => __('Heading', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'insight_more', 'label' => __('"View more" Link Label', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
    ], 'normal', 'default', $tpl);

    new Ascenzi_Metabox('ascenzi_home_partnership', __('Partnership Section', 'ascenzi'), 'page', [
        ['key' => 'partner_bg', 'label' => __('Background Photo', 'ascenzi'), 'type' => 'image'],
        ['key' => 'partner_kicker', 'label' => __('Kicker', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'partner_title', 'label' => __('Heading', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'partner_body', 'label' => __('Body', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
        ['key' => 'partner_cta', 'label' => __('Button Label', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'partner_cards', 'label' => __('Partner Type Cards', 'ascenzi'), 'type' => 'repeater', 'max' => 6, 'fields' => [
            ['key' => 'icon', 'label' => __('Icon', 'ascenzi'), 'type' => 'icon'],
            ['key' => 'title', 'label' => __('Title', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
            ['key' => 'desc', 'label' => __('Description', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ]],
    ], 'normal', 'default', $tpl);

    new Ascenzi_Metabox('ascenzi_home_faq', __('FAQ', 'ascenzi'), 'page', [
        ['key' => 'faq_kicker', 'label' => __('Kicker', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'faq_title', 'label' => __('Heading', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'faq_body', 'label' => __('Body', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ['key' => 'faq_items', 'label' => __('Questions', 'ascenzi'), 'type' => 'repeater', 'max' => 10, 'fields' => [
            ['key' => 'q', 'label' => __('Question', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
            ['key' => 'a', 'label' => __('Answer', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
        ]],
    ], 'normal', 'default', $tpl);

    new Ascenzi_Metabox('ascenzi_home_contact', __('Contact CTA', 'ascenzi'), 'page', [
        ['key' => 'contact_title', 'label' => __('Heading', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ['key' => 'contact_body', 'label' => __('Body', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
    ], 'normal', 'default', $tpl);
});
