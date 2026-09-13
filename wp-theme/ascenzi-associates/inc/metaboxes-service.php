<?php
/**
 * Meta boxes for the "Service" post type. Featured image = hero photo.
 */

if (!defined('ABSPATH')) { exit; }

add_action('init', function () {
    new Ascenzi_Metabox('ascenzi_service_summary', __('Card Summary (used in package grid, header dropdown & other-services cards)', 'ascenzi'), 'service', [
        ['key' => 'icon', 'label' => __('Icon', 'ascenzi'), 'type' => 'icon'],
        ['key' => 'summary', 'label' => __('One-line Summary', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'items', 'label' => __('Bullet Items (shown on hover)', 'ascenzi'), 'type' => 'repeater', 'max' => 6, 'fields' => [
            ['key' => 'text', 'label' => __('Item', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ]],
    ]);

    new Ascenzi_Metabox('ascenzi_service_hero', __('Hero', 'ascenzi'), 'service', [
        ['key' => 'hero_kicker', 'label' => __('Kicker', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'hero_title', 'label' => __('H1 Title', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'client_types', 'label' => __('Client Type Tags', 'ascenzi'), 'type' => 'repeater', 'max' => 6, 'fields' => [
            ['key' => 'label', 'label' => __('Tag', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ]],
        ['key' => 'hero_sub', 'label' => __('Subheading', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ['key' => 'hero_p1', 'label' => __('Intro Paragraph 1', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
        ['key' => 'hero_p2', 'label' => __('Intro Paragraph 2', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
    ], 'normal', 'high');

    new Ascenzi_Metabox('ascenzi_service_handle', __('What We Handle (checklist)', 'ascenzi'), 'service', [
        ['key' => 'handle_head', 'label' => __('Section Heading', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'handle_items', 'label' => __('Checklist Rows', 'ascenzi'), 'type' => 'repeater', 'max' => 10, 'fields' => [
            ['key' => 'title', 'label' => __('Title', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
            ['key' => 'desc', 'label' => __('Description', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ]],
    ]);

    new Ascenzi_Metabox('ascenzi_service_how', __('How We Work (steps) — leave heading blank to hide this section', 'ascenzi'), 'service', [
        ['key' => 'how_head', 'label' => __('Section Heading', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'how_steps', 'label' => __('Steps', 'ascenzi'), 'type' => 'repeater', 'max' => 8, 'fields' => [
            ['key' => 'title', 'label' => __('Title', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
            ['key' => 'desc', 'label' => __('Description', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ]],
    ]);

    new Ascenzi_Metabox('ascenzi_service_callout', __('Callout Box', 'ascenzi'), 'service', [
        ['key' => 'callout_label', 'label' => __('Label', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'callout_body', 'label' => __('Body', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
    ]);

    new Ascenzi_Metabox('ascenzi_service_why', __('Why Ascenzi & Associates (2x2 cards)', 'ascenzi'), 'service', [
        ['key' => 'why_head', 'label' => __('Section Heading', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'why_cards', 'label' => __('Cards', 'ascenzi'), 'type' => 'repeater', 'max' => 4, 'fields' => [
            ['key' => 'icon', 'label' => __('Icon', 'ascenzi'), 'type' => 'icon'],
            ['key' => 'title', 'label' => __('Title', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
            ['key' => 'desc', 'label' => __('Description', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ]],
    ]);
});
