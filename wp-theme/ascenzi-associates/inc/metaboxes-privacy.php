<?php
/**
 * Meta boxes for the page using the "Legal Page" template (template-legal.php).
 * Reused for Privacy Policy and could be reused for a future Terms page too.
 */

if (!defined('ABSPATH')) { exit; }

add_action('init', function () {
    $tpl = 'template-legal.php';

    new Ascenzi_Metabox('ascenzi_legal_intro', __('Header', 'ascenzi'), 'page', [
        ['key' => 'legal_kicker', 'label' => __('Kicker', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'legal_effective', 'label' => __('Effective Date Line', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'legal_intro', 'label' => __('Intro Paragraph', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
        ['key' => 'legal_contact_line', 'label' => __('Closing Contact Line', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
    ], 'normal', 'high', $tpl);

    new Ascenzi_Metabox('ascenzi_legal_sections', __('Numbered Sections', 'ascenzi'), 'page', [
        ['key' => 'legal_sections', 'label' => __('Sections', 'ascenzi'), 'type' => 'repeater', 'max' => 20, 'fields' => [
            ['key' => 'title', 'label' => __('Title', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
            ['key' => 'body', 'label' => __('Body', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
            ['key' => 'list', 'label' => __('Optional Bullet List — one per line (EN)', 'ascenzi'), 'type' => 'textarea', 'rows' => 3],
            ['key' => 'list_zh', 'label' => __('Optional Bullet List — one per line (繁中)', 'ascenzi'), 'type' => 'textarea', 'rows' => 3],
        ]],
    ], 'normal', 'default', $tpl);
});
