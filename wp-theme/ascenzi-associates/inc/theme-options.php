<?php
/**
 * "Ascenzi Settings" admin page — the handful of values reused across many
 * templates (phone number, office address, and where contact-form enquiries
 * get emailed).
 */

if (!defined('ABSPATH')) { exit; }

function ascenzi_options_menu() {
    add_options_page(
        __('Ascenzi Settings', 'ascenzi'),
        __('Ascenzi Settings', 'ascenzi'),
        'manage_options',
        'ascenzi-settings',
        'ascenzi_options_page'
    );
}
add_action('admin_menu', 'ascenzi_options_menu');

function ascenzi_options_register() {
    register_setting('ascenzi_options_group', 'ascenzi_options', 'ascenzi_options_sanitize');
}
add_action('admin_init', 'ascenzi_options_register');

function ascenzi_options_sanitize($input) {
    return [
        'phone'            => sanitize_text_field($input['phone'] ?? ''),
        'office_en'        => sanitize_text_field($input['office_en'] ?? ''),
        'office_zh'        => sanitize_text_field($input['office_zh'] ?? ''),
        'contact_email'    => sanitize_email($input['contact_email'] ?? get_option('admin_email')),
        'contact_email_cc' => sanitize_email($input['contact_email_cc'] ?? ''),
        'privacy_page_id'  => absint($input['privacy_page_id'] ?? 0),
    ];
}

function ascenzi_options_page() {
    $opts = get_option('ascenzi_options', []);
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Ascenzi Settings', 'ascenzi'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('ascenzi_options_group'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="ascenzi_phone"><?php esc_html_e('Phone Number', 'ascenzi'); ?></label></th>
                    <td><input type="text" id="ascenzi_phone" name="ascenzi_options[phone]" value="<?php echo esc_attr($opts['phone'] ?? '888-523-8168'); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ascenzi_office_en"><?php esc_html_e('Office Address (English)', 'ascenzi'); ?></label></th>
                    <td><input type="text" id="ascenzi_office_en" name="ascenzi_options[office_en]" value="<?php echo esc_attr($opts['office_en'] ?? 'Phoenix, Arizona, United States'); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ascenzi_office_zh"><?php esc_html_e('Office Address (繁中)', 'ascenzi'); ?></label></th>
                    <td><input type="text" id="ascenzi_office_zh" name="ascenzi_options[office_zh]" value="<?php echo esc_attr($opts['office_zh'] ?? '美國亞利桑那州鳳凰城'); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ascenzi_contact_email"><?php esc_html_e('Send Enquiries To', 'ascenzi'); ?></label></th>
                    <td>
                        <input type="email" id="ascenzi_contact_email" name="ascenzi_options[contact_email]" value="<?php echo esc_attr($opts['contact_email'] ?? get_option('admin_email')); ?>" class="regular-text">
                        <p class="description"><?php esc_html_e('Where the Contact Us / Become a Partner form submissions are emailed.', 'ascenzi'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ascenzi_contact_email_cc"><?php esc_html_e('CC (optional)', 'ascenzi'); ?></label></th>
                    <td><input type="email" id="ascenzi_contact_email_cc" name="ascenzi_options[contact_email_cc]" value="<?php echo esc_attr($opts['contact_email_cc'] ?? ''); ?>" class="regular-text"></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <hr>
        <p>
            <?php esc_html_e('Reminder: set the site\'s Front Page under Settings → Reading to the Page using the "Home" template, and assign the "About" and "Legal Page" templates to your About Us and Privacy Policy pages respectively.', 'ascenzi'); ?>
        </p>
    </div>
    <?php
}
