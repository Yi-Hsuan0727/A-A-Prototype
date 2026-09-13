<?php
/**
 * Generic meta-box framework: define a field schema once, get a rendered
 * admin UI (bilingual text/textarea, image picker, icon picker, repeaters)
 * and matching save logic for free. Used by every metaboxes-*.php file.
 *
 * Field schema shape:
 *   [
 *     'key'        => 'hero_title',
 *     'label'      => 'Hero Title',
 *     'type'       => 'text' | 'textarea' | 'wysiwyg' | 'image' | 'icon' | 'checkbox' | 'select',
 *     'bilingual'  => true|false,           // renders EN + 繁中 side by side
 *     'rows'       => 3,                    // textarea only
 *     'choices'    => ['a' => 'Label'],     // select only
 *     'help'       => 'shown under the field',
 *   ]
 * Repeater schema shape:
 *   [ 'key' => 'items', 'label' => 'Cards', 'type' => 'repeater', 'fields' => [ ...field schema... ], 'max' => 6 ]
 */

if (!defined('ABSPATH')) { exit; }

class Ascenzi_Metabox {
    public $id;
    public $title;
    public $screen;
    public $fields;
    public $context;
    public $priority;
    public $template; // optional: only show when the post's page template matches (e.g. 'template-home.php')

    public function __construct($id, $title, $screen, array $fields, $context = 'normal', $priority = 'high', $template = null) {
        $this->id = $id;
        $this->title = $title;
        $this->screen = $screen;
        $this->fields = $fields;
        $this->context = $context;
        $this->priority = $priority;
        $this->template = $template;

        add_action('add_meta_boxes', [$this, 'add'], 10, 2);
        add_action('save_post', [$this, 'save']);
        add_action('admin_enqueue_scripts', [$this, 'assets']);
    }

    public function assets($hook) {
        if (!in_array($hook, ['post.php', 'post-new.php'], true)) { return; }
        wp_enqueue_media();
        wp_enqueue_style('ascenzi-admin', ASCENZI_URI . '/assets/css/admin.css', [], ASCENZI_VERSION);
        wp_enqueue_script('ascenzi-admin-repeater', ASCENZI_URI . '/assets/js/admin-repeater.js', ['jquery'], ASCENZI_VERSION, true);
    }

    protected function template_matches($post) {
        if (!$this->template || !$post) { return true; }
        return get_page_template_slug($post) === $this->template;
    }

    public function add($post_type, $post = null) {
        if ($post_type !== $this->screen) { return; }
        if (!$this->template_matches($post)) { return; }
        add_meta_box($this->id, $this->title, [$this, 'render'], $this->screen, $this->context, $this->priority);
    }

    public function render($post) {
        wp_nonce_field('ascenzi_mb_' . $this->id, 'ascenzi_mb_nonce_' . $this->id);
        echo '<div class="ascenzi-mb">';
        foreach ($this->fields as $field) {
            $this->render_field($post->ID, $field);
        }
        echo '</div>';
    }

    protected function field_name($key) {
        return sprintf('ascenzi_mb[%s][%s]', $this->id, $key);
    }

    protected function render_field($post_id, $field) {
        $key = $field['key'];
        $type = $field['type'];
        $label = $field['label'] ?? '';

        if ($type === 'repeater') {
            $this->render_repeater($post_id, $field);
            return;
        }

        echo '<div class="ascenzi-field ascenzi-field--' . esc_attr($type) . '">';
        if ($label) { echo '<label class="ascenzi-field__label">' . esc_html($label) . '</label>'; }

        if (!empty($field['bilingual'])) {
            echo '<div class="ascenzi-field__bilingual">';
            $this->render_input($post_id, $key . '_en', $type, 'EN', $field);
            $this->render_input($post_id, $key . '_zh', $type, '繁中', $field);
            echo '</div>';
        } else {
            $this->render_input($post_id, $key, $type, '', $field);
        }

        if (!empty($field['help'])) {
            echo '<p class="ascenzi-field__help">' . esc_html($field['help']) . '</p>';
        }
        echo '</div>';
    }

    protected function render_input($post_id, $meta_key, $type, $sublabel, $field) {
        $value = get_post_meta($post_id, $meta_key, true);
        $name = $this->field_name($meta_key);
        $id = 'ascenzi-' . $this->id . '-' . $meta_key;

        echo '<div class="ascenzi-field__col">';
        if ($sublabel) { echo '<span class="ascenzi-field__sublabel">' . esc_html($sublabel) . '</span>'; }

        switch ($type) {
            case 'textarea':
                printf('<textarea id="%s" name="%s" rows="%d" class="widefat">%s</textarea>', esc_attr($id), esc_attr($name), (int) ($field['rows'] ?? 4), esc_textarea($value));
                break;
            case 'wysiwyg':
                wp_editor($value, sanitize_key($id), ['textarea_name' => $name, 'textarea_rows' => 8, 'media_buttons' => false, 'teeny' => true]);
                break;
            case 'image':
                $img_url = $value ? wp_get_attachment_image_url($value, 'medium') : '';
                echo '<div class="ascenzi-image-field" data-target="' . esc_attr($id) . '">';
                echo '<div class="ascenzi-image-preview">' . ($img_url ? '<img src="' . esc_url($img_url) . '">' : '') . '</div>';
                printf('<input type="hidden" id="%s" name="%s" value="%s">', esc_attr($id), esc_attr($name), esc_attr($value));
                echo '<button type="button" class="button ascenzi-media-btn">' . esc_html__('Select Image', 'ascenzi') . '</button> ';
                echo '<button type="button" class="button ascenzi-media-remove">' . esc_html__('Remove', 'ascenzi') . '</button>';
                echo '</div>';
                break;
            case 'icon':
                echo '<select id="' . esc_attr($id) . '" name="' . esc_attr($name) . '">';
                echo '<option value="">' . esc_html__('— none —', 'ascenzi') . '</option>';
                foreach (ascenzi_icon_choices() as $slug => $iconlabel) {
                    printf('<option value="%s" %s>%s</option>', esc_attr($slug), selected($value, $slug, false), esc_html($iconlabel));
                }
                echo '</select>';
                break;
            case 'select':
                echo '<select id="' . esc_attr($id) . '" name="' . esc_attr($name) . '">';
                foreach ($field['choices'] ?? [] as $slug => $choicelabel) {
                    printf('<option value="%s" %s>%s</option>', esc_attr($slug), selected($value, $slug, false), esc_html($choicelabel));
                }
                echo '</select>';
                break;
            case 'checkbox':
                printf('<input type="checkbox" id="%s" name="%s" value="1" %s>', esc_attr($id), esc_attr($name), checked($value, '1', false));
                break;
            case 'text':
            default:
                printf('<input type="text" id="%s" name="%s" value="%s" class="widefat">', esc_attr($id), esc_attr($name), esc_attr($value));
                break;
        }
        echo '</div>';
    }

    protected function render_repeater($post_id, $field) {
        $key = $field['key'];
        $rows = ascenzi_repeater($post_id, $key);
        $sub_fields = $field['fields'];
        $max = $field['max'] ?? 0;

        echo '<div class="ascenzi-field ascenzi-field--repeater">';
        echo '<label class="ascenzi-field__label">' . esc_html($field['label']) . '</label>';
        if (!empty($field['help'])) { echo '<p class="ascenzi-field__help">' . esc_html($field['help']) . '</p>'; }

        echo '<div class="ascenzi-repeater" data-repeater-key="' . esc_attr($key) . '" data-max="' . (int) $max . '">';
        echo '<div class="ascenzi-repeater__rows">';
        if ($rows) {
            foreach ($rows as $i => $row) {
                $this->render_repeater_row($key, $i, $row, $sub_fields);
            }
        }
        echo '</div>';
        echo '<button type="button" class="button ascenzi-repeater__add">' . esc_html__('+ Add row', 'ascenzi') . '</button>';

        echo '<template class="ascenzi-repeater__template">';
        $this->render_repeater_row($key, '__i__', [], $sub_fields);
        echo '</template>';
        echo '</div>';
        echo '</div>';
    }

    protected function render_repeater_row($key, $index, $row, $sub_fields) {
        echo '<div class="ascenzi-repeater__row">';
        echo '<div class="ascenzi-repeater__row-fields">';
        foreach ($sub_fields as $sub) {
            $sub_key = $sub['key'];
            $type = $sub['type'];
            echo '<div class="ascenzi-field ascenzi-field--' . esc_attr($type) . '">';
            if (!empty($sub['label'])) { echo '<label class="ascenzi-field__label">' . esc_html($sub['label']) . '</label>'; }

            if (!empty($sub['bilingual'])) {
                echo '<div class="ascenzi-field__bilingual">';
                foreach (['en' => 'EN', 'zh' => '繁中'] as $suffix => $sublabel) {
                    $name = sprintf('ascenzi_mb[%s][%s][%s][%s_%s]', $this->id, $key, $index, $sub_key, $suffix);
                    $value = $row[$sub_key . '_' . $suffix] ?? '';
                    echo '<div class="ascenzi-field__col"><span class="ascenzi-field__sublabel">' . esc_html($sublabel) . '</span>';
                    $this->repeater_input($type, $name, $value, $sub);
                    echo '</div>';
                }
                echo '</div>';
            } else {
                $name = sprintf('ascenzi_mb[%s][%s][%s][%s]', $this->id, $key, $index, $sub_key);
                $value = $row[$sub_key] ?? '';
                $this->repeater_input($type, $name, $value, $sub);
            }
            echo '</div>';
        }
        echo '</div>';
        echo '<button type="button" class="button-link ascenzi-repeater__remove">' . esc_html__('Remove', 'ascenzi') . '</button>';
        echo '</div>';
    }

    protected function repeater_input($type, $name, $value, $sub) {
        $uid = 'ascenzi-rep-' . md5($name);
        switch ($type) {
            case 'textarea':
                printf('<textarea name="%s" rows="%d" class="widefat">%s</textarea>', esc_attr($name), (int) ($sub['rows'] ?? 3), esc_textarea($value));
                break;
            case 'icon':
                echo '<select name="' . esc_attr($name) . '">';
                echo '<option value="">' . esc_html__('— none —', 'ascenzi') . '</option>';
                foreach (ascenzi_icon_choices() as $slug => $iconlabel) {
                    printf('<option value="%s" %s>%s</option>', esc_attr($slug), selected($value, $slug, false), esc_html($iconlabel));
                }
                echo '</select>';
                break;
            case 'image':
                $img_url = $value ? wp_get_attachment_image_url($value, 'medium') : '';
                echo '<div class="ascenzi-image-field" data-target="' . esc_attr($uid) . '">';
                echo '<div class="ascenzi-image-preview">' . ($img_url ? '<img src="' . esc_url($img_url) . '">' : '') . '</div>';
                printf('<input type="hidden" id="%s" name="%s" value="%s">', esc_attr($uid), esc_attr($name), esc_attr($value));
                echo '<button type="button" class="button ascenzi-media-btn">' . esc_html__('Select', 'ascenzi') . '</button> ';
                echo '<button type="button" class="button ascenzi-media-remove">' . esc_html__('Remove', 'ascenzi') . '</button>';
                echo '</div>';
                break;
            case 'text':
            default:
                printf('<input type="text" name="%s" value="%s" class="widefat">', esc_attr($name), esc_attr($value));
                break;
        }
    }

    public function save($post_id) {
        $nonce_key = 'ascenzi_mb_nonce_' . $this->id;
        if (!isset($_POST[$nonce_key]) || !wp_verify_nonce($_POST[$nonce_key], 'ascenzi_mb_' . $this->id)) { return; }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
        if (!current_user_can('edit_post', $post_id)) { return; }
        if (!isset($_POST['ascenzi_mb'][$this->id])) { return; }

        $data = wp_unslash($_POST['ascenzi_mb'][$this->id]);

        foreach ($this->fields as $field) {
            if ($field['type'] === 'repeater') {
                $this->save_repeater($post_id, $field, $data);
                continue;
            }
            $this->save_field($post_id, $field, $data);
        }
    }

    protected function save_field($post_id, $field, $data) {
        $key = $field['key'];
        if (!empty($field['bilingual'])) {
            update_post_meta($post_id, $key . '_en', $this->sanitize($field['type'], $data[$key . '_en'] ?? ''));
            update_post_meta($post_id, $key . '_zh', $this->sanitize($field['type'], $data[$key . '_zh'] ?? ''));
        } else {
            $value = $field['type'] === 'checkbox' ? (isset($data[$key]) ? '1' : '0') : ($data[$key] ?? '');
            update_post_meta($post_id, $key, $this->sanitize($field['type'], $value));
        }
    }

    protected function save_repeater($post_id, $field, $data) {
        $key = $field['key'];
        $raw_rows = $data[$key] ?? [];
        if (!is_array($raw_rows)) { $raw_rows = []; }
        unset($raw_rows['__i__']);

        $clean_rows = [];
        foreach ($raw_rows as $raw_row) {
            if (!is_array($raw_row)) { continue; }
            $row = [];
            $has_content = false;
            foreach ($field['fields'] as $sub) {
                $sub_key = $sub['key'];
                if (!empty($sub['bilingual'])) {
                    $row[$sub_key . '_en'] = $this->sanitize($sub['type'], $raw_row[$sub_key . '_en'] ?? '');
                    $row[$sub_key . '_zh'] = $this->sanitize($sub['type'], $raw_row[$sub_key . '_zh'] ?? '');
                    if ($row[$sub_key . '_en'] !== '' || $row[$sub_key . '_zh'] !== '') { $has_content = true; }
                } else {
                    $row[$sub_key] = $this->sanitize($sub['type'], $raw_row[$sub_key] ?? '');
                    if ($row[$sub_key] !== '') { $has_content = true; }
                }
            }
            if ($has_content) { $clean_rows[] = $row; }
        }
        update_post_meta($post_id, $key, $clean_rows);
    }

    protected function sanitize($type, $value) {
        switch ($type) {
            case 'textarea':
            case 'wysiwyg':
                return wp_kses_post($value);
            case 'image':
                return absint($value);
            case 'checkbox':
                return $value ? '1' : '0';
            case 'icon':
            case 'select':
                return sanitize_text_field($value);
            case 'text':
            default:
                return sanitize_text_field($value);
        }
    }
}
