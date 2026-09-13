<?php
/**
 * Meta boxes for the page using the "About" page template (template-about.php).
 */

if (!defined('ABSPATH')) { exit; }

add_action('init', function () {
    $tpl = 'template-about.php';

    new Ascenzi_Metabox('ascenzi_about_hero', __('Hero', 'ascenzi'), 'page', [
        ['key' => 'firm_kicker', 'label' => __('Kicker', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'firm_title', 'label' => __('H1 Title', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'firm_p1', 'label' => __('Paragraph 1', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
        ['key' => 'firm_p2', 'label' => __('Paragraph 2', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
        ['key' => 'firm_image', 'label' => __('Hero Photo', 'ascenzi'), 'type' => 'image'],
    ], 'normal', 'high', $tpl);

    new Ascenzi_Metabox('ascenzi_about_spirit', __('Spirit / Quote Band', 'ascenzi'), 'page', [
        ['key' => 'spirit_label', 'label' => __('Label', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'spirit_quote', 'label' => __('Quote', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ['key' => 'spirit_attribution', 'label' => __('Attribution', 'ascenzi'), 'type' => 'text'],
    ], 'normal', 'default', $tpl);

    new Ascenzi_Metabox('ascenzi_about_leadership', __('Leadership', 'ascenzi'), 'page', [
        ['key' => 'leadership_kicker', 'label' => __('Kicker', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'leader_photo', 'label' => __('Portrait', 'ascenzi'), 'type' => 'image'],
        ['key' => 'leader_name', 'label' => __('Name', 'ascenzi'), 'type' => 'text'],
        ['key' => 'leader_role', 'label' => __('Role', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'lead_h2', 'label' => __('Heading', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'lead_p1', 'label' => __('Bio Paragraph 1', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
        ['key' => 'lead_p2', 'label' => __('Bio Paragraph 2', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
        ['key' => 'lead_p3', 'label' => __('Bio Paragraph 3', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 3],
        ['key' => 'cred_label', 'label' => __('Credentials Label', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'credentials', 'label' => __('Credentials', 'ascenzi'), 'type' => 'repeater', 'max' => 8, 'fields' => [
            ['key' => 'text', 'label' => __('Item', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ]],
    ], 'normal', 'default', $tpl);

    new Ascenzi_Metabox('ascenzi_about_cta', __('Enquiry CTA', 'ascenzi'), 'page', [
        ['key' => 'about_cta_title', 'label' => __('Heading', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
        ['key' => 'about_cta_body', 'label' => __('Body', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
    ], 'normal', 'default', $tpl);
});
