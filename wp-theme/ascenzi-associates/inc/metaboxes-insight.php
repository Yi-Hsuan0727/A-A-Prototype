<?php
/**
 * Meta boxes for the "Insight Post" post type. Featured image = hero image.
 * Category/Tags use WordPress's native taxonomy boxes (insight_category,
 * insight_tag) — no custom UI needed for those.
 */

if (!defined('ABSPATH')) { exit; }

add_action('init', function () {
    new Ascenzi_Metabox('ascenzi_insight_meta', __('Article Details', 'ascenzi'), 'insight_post', [
        ['key' => 'dek', 'label' => __('Dek (subhead under the title)', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ['key' => 'author_name', 'label' => __('Author Name', 'ascenzi'), 'type' => 'text'],
        ['key' => 'author_role', 'label' => __('Author Role', 'ascenzi'), 'type' => 'text', 'bilingual' => true],
    ], 'side', 'default');

    new Ascenzi_Metabox('ascenzi_insight_body', __('Article Body', 'ascenzi'), 'insight_post', [
        ['key' => 'pull_quote', 'label' => __('Pull Quote (optional, shown as a large blockquote)', 'ascenzi'), 'type' => 'textarea', 'bilingual' => true, 'rows' => 2],
        ['key' => 'body', 'label' => __('Body', 'ascenzi'), 'type' => 'wysiwyg', 'bilingual' => true, 'help' => __('Use headings (H2) to break up sections; tables and images can be inserted from the editor toolbar.', 'ascenzi')],
    ], 'normal', 'high');
});
