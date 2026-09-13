<?php
/**
 * Shared helper functions: language detection, bilingual field access,
 * icon rendering, phone/office/formatting helpers.
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Current front-end language: 'en' or 'zh'. Resolution order matches the
 * original prototype: ?lang= query param -> cookie -> 'en' default.
 */
function ascenzi_current_lang() {
    static $lang = null;
    if ($lang !== null) { return $lang; }

    $lang = 'en';
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'zh'], true)) {
        $lang = sanitize_text_field(wp_unslash($_GET['lang']));
    } elseif (isset($_COOKIE['ascenzi_lang']) && in_array($_COOKIE['ascenzi_lang'], ['en', 'zh'], true)) {
        $lang = sanitize_text_field(wp_unslash($_COOKIE['ascenzi_lang']));
    }
    return $lang;
}

/**
 * Get a bilingual post-meta field. Stored as "{$key}_en" / "{$key}_zh".
 * Falls back to English if the Chinese value is empty.
 */
function ascenzi_meta($post_id, $key, $lang = null) {
    $lang = $lang ?: ascenzi_current_lang();
    $value = get_post_meta($post_id, $key . '_' . $lang, true);
    if ($value === '' && $lang !== 'en') {
        $value = get_post_meta($post_id, $key . '_en', true);
    }
    return $value;
}

/** Get a raw (non-bilingual) post-meta field with a default fallback. */
function ascenzi_meta_raw($post_id, $key, $default = '') {
    $value = get_post_meta($post_id, $key, true);
    return $value === '' ? $default : $value;
}

/** Get a repeater field (stored as a serialized array of assoc rows). */
function ascenzi_repeater($post_id, $key) {
    $rows = get_post_meta($post_id, $key, true);
    return is_array($rows) ? $rows : [];
}

/** Pull the localized value out of one repeater row for a given field key. */
function ascenzi_row_field($row, $key, $lang = null) {
    $lang = $lang ?: ascenzi_current_lang();
    if (isset($row[$key . '_' . $lang]) && $row[$key . '_' . $lang] !== '') {
        return $row[$key . '_' . $lang];
    }
    return $row[$key . '_en'] ?? ($row[$key] ?? '');
}

/** Read a repeater row's "one bullet per line" textarea field for the current language. */
function ascenzi_row_lines($row, $base_key, $lang = null) {
    $lang = $lang ?: ascenzi_current_lang();
    $text = ($lang === 'zh' && !empty($row[$base_key . '_zh'])) ? $row[$base_key . '_zh'] : ($row[$base_key] ?? '');
    return ascenzi_lines($text);
}

/** Split a textarea's lines into a clean array (used for simple bullet lists). */
function ascenzi_lines($text) {
    if (!$text) { return []; }
    $lines = preg_split('/\r\n|\r|\n/', $text);
    $lines = array_map('trim', $lines);
    return array_values(array_filter($lines, fn($l) => $l !== ''));
}

/**
 * Registered icon set (mask-based mono icons, same as the prototype's icons/
 * folder). Value => [file, label].
 */
function ascenzi_icon_choices() {
    return [
        'modular'        => __('Modular / Talent', 'ascenzi'),
        'permitting'     => __('Permitting / Licensing', 'ascenzi'),
        'market'         => __('Market Entry', 'ascenzi'),
        'labor'          => __('Labor / Growth', 'ascenzi'),
        'all-services'   => __('All Services', 'ascenzi'),
        'road-decision'  => __('Road: Decision', 'ascenzi'),
        'road-entity'    => __('Road: Entity & Visas', 'ascenzi'),
        'road-site'      => __('Road: Site & Permits', 'ascenzi'),
        'road-build'     => __('Road: Talent & Build', 'ascenzi'),
        'road-operating' => __('Road: Operating', 'ascenzi'),
        'academic'       => __('Academic (partner)', 'ascenzi'),
        'institutional'  => __('Institutional (partner)', 'ascenzi'),
        'professional'   => __('Professional (partner)', 'ascenzi'),
        'specialist'     => __('Specialist (partner)', 'ascenzi'),
        'folder'         => __('Folder / Knowledge', 'ascenzi'),
        'grid'           => __('2x2 Grid', 'ascenzi'),
        'pin'            => __('Map Pin', 'ascenzi'),
        'network'        => __('Connected Circles', 'ascenzi'),
    ];
}

/** Render a mask-based icon <span>. $extra_style is appended raw (already-escaped) CSS. */
function ascenzi_icon($slug, $size = 26, $color = '#0028FF', $class = '', $extra_style = '') {
    if (!$slug) { return ''; }
    $url = esc_url(ASCENZI_URI . '/assets/images/icons/' . sanitize_file_name($slug) . '.svg');
    $style = sprintf(
        'width:%1$dpx; height:%1$dpx; background-color:%2$s; mask-image:url("%3$s"); -webkit-mask-image:url("%3$s"); %4$s',
        (int) $size, esc_attr($color), $url, $extra_style
    );
    printf('<span aria-hidden="true" class="%s" style="%s"></span>', esc_attr($class), esc_attr($style));
}
function ascenzi_icon_get($slug, $size = 26, $color = '#0028FF', $class = '', $extra_style = '') {
    ob_start();
    ascenzi_icon($slug, $size, $color, $class, $extra_style);
    return ob_get_clean();
}

/* ---------------------------------------------------------------------------
 * Theme options (Settings -> Ascenzi Settings)
 * ------------------------------------------------------------------------ */
function ascenzi_option($key, $default = '') {
    $opts = get_option('ascenzi_options', []);
    return $opts[$key] ?? $default;
}

function ascenzi_phone() { return ascenzi_option('phone', '888-523-8168'); }
function ascenzi_phone_href() { return 'tel:+1' . preg_replace('/\D/', '', ascenzi_phone()); }
function ascenzi_office($lang = null) {
    $lang = $lang ?: ascenzi_current_lang();
    return $lang === 'zh'
        ? ascenzi_option('office_zh', '美國亞利桑那州鳳凰城')
        : ascenzi_option('office_en', 'Phoenix, Arizona, United States');
}

/** URL of the page assigned the given page template (returns '#' if none is set up yet). */
function ascenzi_url_for_template($template_file) {
    static $cache = [];
    if (isset($cache[$template_file])) { return $cache[$template_file]; }
    $pages = get_posts([
        'post_type'   => 'page',
        'meta_key'    => '_wp_page_template',
        'meta_value'  => $template_file,
        'numberposts' => 1,
        'post_status' => 'publish',
    ]);
    $url = $pages ? get_permalink($pages[0]) : '#';
    $cache[$template_file] = $url;
    return $url;
}
function ascenzi_about_url() { return ascenzi_url_for_template('template-about.php'); }
function ascenzi_privacy_url() { return ascenzi_url_for_template('template-legal.php'); }
function ascenzi_home_url() { return home_url('/'); }

/** The header/footer language switcher markup. */
function ascenzi_render_lang_switch() {
    $lang = ascenzi_current_lang();
    ?>
    <div class="nav-lang" data-nav-lang>
        <button type="button" class="nav-lang__trigger" aria-haspopup="listbox" aria-expanded="false" aria-label="<?php esc_attr_e('Language', 'ascenzi'); ?>" data-lang-trigger>
            <svg width="17" height="17" viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.4"/><path d="M2 10h16M10 2c2.5 2.4 3.8 5.1 3.8 8s-1.3 5.6-3.8 8c-2.5-2.4-3.8-5.1-3.8-8S7.5 4.4 10 2z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
            <span class="nav-lang__code"><?php echo $lang === 'zh' ? '繁中' : 'EN'; ?></span>
            <svg width="12" height="12" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3.5 6l4.5 4.5L12.5 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="nav-lang__menu" role="listbox" data-lang-menu>
            <button type="button" class="nav-lang__option<?php echo $lang === 'en' ? ' is-active' : ''; ?>" data-lang-option="en" role="option" aria-selected="<?php echo $lang === 'en' ? 'true' : 'false'; ?>">
                <span>English</span>
                <?php if ($lang === 'en') : ?><svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M3 8.5l3.2 3L13 4.5" stroke="#0028FF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg><?php endif; ?>
            </button>
            <button type="button" class="nav-lang__option<?php echo $lang === 'zh' ? ' is-active' : ''; ?>" data-lang-option="zh" role="option" aria-selected="<?php echo $lang === 'zh' ? 'true' : 'false'; ?>">
                <span>繁體中文</span>
                <?php if ($lang === 'zh') : ?><svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M3 8.5l3.2 3L13 4.5" stroke="#0028FF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg><?php endif; ?>
            </button>
        </div>
    </div>
    <?php
}

/** The chat-bubble icon shared by the header Contact Us button and hero CTA. */
function ascenzi_icon_button_chat($color = '#FFFFFF') {
    ?><svg width="17" height="17" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="flex:none"><path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h13A1.5 1.5 0 0 1 20 5.5v9a1.5 1.5 0 0 1-1.5 1.5H10l-4.4 3.6V16H5.5A1.5 1.5 0 0 1 4 14.5v-9z" stroke="<?php echo esc_attr($color); ?>" stroke-width="1.5" stroke-linejoin="round"/><path d="M8 9h8M8 12h5" stroke="<?php echo esc_attr($color); ?>" stroke-width="1.5" stroke-linecap="round"/></svg><?php
}

/* ---------------------------------------------------------------------------
 * Small labels shared across many templates (chrome, not "content" per se —
 * kept centralized so they only need translating once).
 * ------------------------------------------------------------------------ */
function ascenzi_t($key) {
    $lang = ascenzi_current_lang();
    $strings = [
        'phone'   => ['en' => 'Phone',   'zh' => '電話'],
        'office'  => ['en' => 'Office',  'zh' => '辦公室'],
        'by'      => ['en' => 'By',      'zh' => '作者'],
        'back'    => ['en' => 'Back',    'zh' => '返回'],
        'explore' => ['en' => 'Explore', 'zh' => '了解更多'],
        'read_more' => ['en' => 'View more articles', 'zh' => '查看更多文章'],
        'submit_enquiry' => ['en' => 'Submit an enquiry', 'zh' => '送出諮詢'],
        'talk_to_us' => ['en' => 'Talk to us', 'zh' => '與我們聯絡'],
        'close'   => ['en' => 'Close', 'zh' => '關閉'],
        'skip'    => ['en' => 'Skip', 'zh' => '略過'],
        'submit'  => ['en' => 'Submit', 'zh' => '送出'],
        'received' => ['en' => 'Received', 'zh' => '已收到'],
        'previous' => ['en' => 'Previous', 'zh' => '上一篇'],
        'next'    => ['en' => 'Next', 'zh' => '下一篇'],
        'all'     => ['en' => 'All', 'zh' => '全部'],
        'search_placeholder' => ['en' => 'Search by keyword', 'zh' => '輸入關鍵字搜尋'],
        'no_results' => ['en' => 'No articles match your filters.', 'zh' => '沒有符合篩選條件的文章。'],
        'clear_filters' => ['en' => 'Clear filters', 'zh' => '清除篩選'],
        'name' => ['en' => 'Name', 'zh' => '姓名'],
        'company' => ['en' => 'Company', 'zh' => '公司'],
        'email' => ['en' => 'Work email', 'zh' => '公司電子郵件'],
        'phone_optional' => ['en' => 'Phone (optional)', 'zh' => '電話（選填）'],
        'more_info' => ['en' => 'Anything we should know', 'zh' => '其他我們應該知道的事'],
        'need' => ['en' => 'What do you need', 'zh' => '您的需求'],
        'footer_blurb' => ['en' => 'Consulting for the advanced manufacturing construction sector. Workforce, licensing, market entry and the support that follows.', 'zh' => '為先進製造建設產業提供顧問服務，涵蓋人力、執照、市場進入與後續支援。'],
        'newsletter' => ['en' => 'Newsletter', 'zh' => '電子報'],
        'newsletter_blurb' => ['en' => 'Industry perspective on US fab delivery.', 'zh' => '美國廠房建設的產業觀點。'],
        'subscribe' => ['en' => 'Subscribe', 'zh' => '訂閱'],
        'subscribed' => ['en' => "Thanks — you're on the list.", 'zh' => '謝謝——已加入訂閱名單。'],
        'privacy_terms' => ['en' => 'Privacy Policy & Terms', 'zh' => '隱私權政策與使用條款'],
        'org_general_contractor' => ['en' => 'General Contractor', 'zh' => '總承包商'],
        'org_owner' => ['en' => 'Owner / end user', 'zh' => '業主／終端使用者'],
        'org_vendor' => ['en' => 'Vendor or fabricator', 'zh' => '供應商或製造廠'],
        'org_other' => ['en' => 'Others', 'zh' => '其他'],
        'org_question' => ['en' => 'Which best describes your organization?', 'zh' => '以下哪一項最符合貴組織？'],
        'reach_question' => ['en' => 'How do we reach you?', 'zh' => '我們如何聯絡您？'],
        'partner_title' => ['en' => 'Tell us who you are and what you bring to U.S. advanced manufacturing.', 'zh' => '告訴我們您是誰，以及您能為美國先進製造帶來什麼。'],
        'enquiry_kicker' => ['en' => 'Enquiry', 'zh' => '諮詢'],
        'partnership_kicker' => ['en' => 'Partnership', 'zh' => '合作夥伴'],
        'complete' => ['en' => 'Complete', 'zh' => '完成'],
        'note' => ['en' => 'Every enquiry is reviewed by a principal. No cost, no obligation.', 'zh' => '每一則諮詢皆由負責人親自審閱。免費，且無任何義務。'],
        'done_title' => ['en' => 'Thank you — a principal will review this personally.', 'zh' => '謝謝您——負責人將親自審閱這則諮詢。'],
        'done_body' => ['en' => 'We typically respond within two business days with a direct assessment of fit.', 'zh' => '我們通常會在兩個工作天內回覆，並直接評估是否適合合作。'],
        'more_placeholder' => ['en' => 'Scope, site, timeline and what is already in place.', 'zh' => '工作範圍、地點、時程，以及目前已就緒的項目。'],
        'need_placeholder' => ['en' => 'Your organization, the work you do, and how you would like to collaborate.', 'zh' => '貴組織、您所從事的工作，以及希望如何合作。'],
        'prefer_call' => ['en' => 'Prefer to talk now?', 'zh' => '想直接聯絡我們？'],
        'step_of' => ['en' => 'Step {n} / 2', 'zh' => '第 {n} / 2 步'],
        'submitting' => ['en' => 'Sending…', 'zh' => '傳送中…'],
        'submit_error' => ['en' => 'Something went wrong sending that — please call us directly.', 'zh' => '傳送時發生問題，請直接致電我們。'],
        'service_cta_title' => ['en' => 'Submit an enquiry or call the office directly.', 'zh' => '送出諮詢，或直接致電辦公室。'],
        'service_cta_body' => ['en' => 'Every enquiry is reviewed by a principal. No cost, no obligation.', 'zh' => '每一則諮詢皆由負責人親自審閱。免費，且無任何義務。'],
        'insight_kicker_archive' => ['en' => 'Insight', 'zh' => '洞察觀點'],
        'insight_archive_title' => ['en' => 'Technical writing and project record.', 'zh' => '技術寫作與專案紀錄。'],
        'insight_archive_blurb' => ['en' => 'Field notes on US fab delivery — schedule, labor, licensing and market entry. Published when there is something worth publishing.', 'zh' => '關於美國廠房交付的第一線筆記——時程、人力、執照與市場進入。只有在有值得分享的內容時才會發布。'],
        'newsletter_cta_title' => ['en' => 'Get industry perspective in your inbox.', 'zh' => '將產業觀點直接送到您的信箱。'],
    ];
    return $strings[$key][$lang] ?? ($strings[$key]['en'] ?? '');
}
