<?php
/**
 * "Insight Post" custom post type — the articles list/detail (formerly
 * "Blog"). Body copy is a bilingual WYSIWYG meta field rather than native
 * post_content, so one post holds both languages (see metaboxes-insight.php).
 */

if (!defined('ABSPATH')) { exit; }

function ascenzi_register_insight_cpt() {
    register_post_type('insight_post', [
        'labels' => [
            'name'          => __('Insight Posts', 'ascenzi'),
            'singular_name' => __('Insight Post', 'ascenzi'),
            'add_new_item'  => __('Add New Insight Post', 'ascenzi'),
            'edit_item'     => __('Edit Insight Post', 'ascenzi'),
            'all_items'     => __('Insight Posts', 'ascenzi'),
            'menu_name'     => __('Insight', 'ascenzi'),
        ],
        'public'       => true,
        'has_archive'  => 'insight',
        'show_in_rest' => false,
        'menu_icon'    => 'dashicons-media-document',
        'supports'     => ['title', 'thumbnail'],
        'rewrite'      => ['slug' => 'insight', 'with_front' => false],
    ]);

    register_taxonomy('insight_category', 'insight_post', [
        'labels' => [
            'name'          => __('Insight Categories', 'ascenzi'),
            'singular_name' => __('Insight Category', 'ascenzi'),
        ],
        'hierarchical'      => true,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'insight-category'],
    ]);

    register_taxonomy('insight_tag', 'insight_post', [
        'labels' => [
            'name'          => __('Topic Tags', 'ascenzi'),
            'singular_name' => __('Topic Tag', 'ascenzi'),
        ],
        'hierarchical'      => false,
        'show_admin_column' => true,
        'rewrite'           => ['slug' => 'insight-tag'],
    ]);
}
add_action('init', 'ascenzi_register_insight_cpt');

/** Seed the default category set on first activation (admins can add more later). */
function ascenzi_seed_insight_categories() {
    if (get_option('ascenzi_categories_seeded')) { return; }
    $defaults = ['Industry News', 'U.S. Market Entry', 'Workforce & Talent', 'Regulatory & Compliance', 'Project Insights'];
    foreach ($defaults as $name) {
        if (!term_exists($name, 'insight_category')) {
            wp_insert_term($name, 'insight_category');
        }
    }
    update_option('ascenzi_categories_seeded', 1);
}
add_action('init', 'ascenzi_seed_insight_categories', 20);

/** Bilingual-friendly category label: falls back to the term name if no translation meta is set. */
function ascenzi_term_label($term) {
    if (!$term) { return ''; }
    $lang = ascenzi_current_lang();
    $translated = get_term_meta($term->term_id, 'name_' . $lang, true);
    return $translated ?: $term->name;
}

/* ---------------------------------------------------------------------------
 * Add a "Chinese Name" field to insight_category / insight_tag term forms,
 * stored as term meta (name_zh) — this is how ascenzi_term_label() above
 * finds a translated label instead of falling back to the English term name.
 * ------------------------------------------------------------------------ */
function ascenzi_term_zh_field_add($taxonomy) {
    ?>
    <div class="form-field">
        <label for="name_zh"><?php esc_html_e('Chinese Name (繁中)', 'ascenzi'); ?></label>
        <input type="text" name="name_zh" id="name_zh" value="">
        <p><?php esc_html_e('Optional — shown when a visitor has switched to Traditional Chinese.', 'ascenzi'); ?></p>
    </div>
    <?php
}
function ascenzi_term_zh_field_edit($term) {
    $value = get_term_meta($term->term_id, 'name_zh', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="name_zh"><?php esc_html_e('Chinese Name (繁中)', 'ascenzi'); ?></label></th>
        <td>
            <input type="text" name="name_zh" id="name_zh" value="<?php echo esc_attr($value); ?>">
            <p class="description"><?php esc_html_e('Optional — shown when a visitor has switched to Traditional Chinese.', 'ascenzi'); ?></p>
        </td>
    </tr>
    <?php
}
function ascenzi_term_zh_field_save($term_id) {
    if (isset($_POST['name_zh'])) {
        update_term_meta($term_id, 'name_zh', sanitize_text_field(wp_unslash($_POST['name_zh'])));
    }
}
foreach (['insight_category', 'insight_tag'] as $tax) {
    add_action($tax . '_add_form_fields', 'ascenzi_term_zh_field_add');
    add_action($tax . '_edit_form_fields', 'ascenzi_term_zh_field_edit');
    add_action('created_' . $tax, 'ascenzi_term_zh_field_save');
    add_action('edited_' . $tax, 'ascenzi_term_zh_field_save');
}
