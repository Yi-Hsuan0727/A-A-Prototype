<?php
/**
 * Contact / Partnership enquiry form — real submission via wp_mail(),
 * posted over admin-ajax.php so the modal never reloads the page (same UX
 * as the original prototype's in-place "step" transitions).
 *
 * The newsletter signup boxes are intentionally NOT wired here — they stay
 * a front-end-only confirmation, same as the prototype, since there is no
 * email-list service account to connect them to.
 */

if (!defined('ABSPATH')) { exit; }

function ascenzi_handle_contact_submit() {
    check_ajax_referer('ascenzi_contact', 'nonce');

    // Honeypot: a hidden field real visitors never fill in.
    if (!empty($_POST['website'])) {
        wp_send_json_success(['message' => 'ok']); // silently pretend success to the bot
    }

    $mode = sanitize_text_field(wp_unslash($_POST['mode'] ?? 'contact'));
    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $company = sanitize_text_field(wp_unslash($_POST['company'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $org_type = sanitize_text_field(wp_unslash($_POST['org_type'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));

    if (!$name || !is_email($email)) {
        wp_send_json_error(['message' => __('Please provide your name and a valid email address.', 'ascenzi')], 400);
    }

    $to = ascenzi_option('contact_email', get_option('admin_email'));
    $cc = ascenzi_option('contact_email_cc', '');

    $subject = $mode === 'partner'
        ? sprintf('[%s] New partnership enquiry from %s', get_bloginfo('name'), $name)
        : sprintf('[%s] New contact enquiry from %s', get_bloginfo('name'), $name);

    $lines = [];
    $lines[] = 'Mode: ' . ($mode === 'partner' ? 'Partnership' : 'Contact');
    $lines[] = 'Name: ' . $name;
    if ($company) { $lines[] = 'Company: ' . $company; }
    $lines[] = 'Email: ' . $email;
    if ($phone) { $lines[] = 'Phone: ' . $phone; }
    if ($org_type) { $lines[] = 'Organization type: ' . $org_type; }
    if ($message) { $lines[] = "\nMessage:\n" . $message; }
    $lines[] = "\n—\nSubmitted from " . home_url('/') . ' at ' . current_time('mysql');

    $headers = ['Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>'];
    if ($cc && is_email($cc)) { $headers[] = 'Cc: ' . $cc; }

    $sent = wp_mail($to, $subject, implode("\n", $lines), $headers);

    if (!$sent) {
        wp_send_json_error(['message' => __('Something went wrong sending that. Please call us directly.', 'ascenzi')], 500);
    }

    /**
     * Fires after a contact/partnership enquiry is successfully emailed.
     * Hook here to also log the enquiry to a CPT, a spreadsheet integration,
     * a CRM, etc. — kept out of core theme code on purpose.
     */
    do_action('ascenzi_contact_submitted', $mode, compact('name', 'company', 'email', 'phone', 'org_type', 'message'));

    wp_send_json_success(['message' => __('Received.', 'ascenzi')]);
}
add_action('wp_ajax_ascenzi_contact_submit', 'ascenzi_handle_contact_submit');
add_action('wp_ajax_nopriv_ascenzi_contact_submit', 'ascenzi_handle_contact_submit');
