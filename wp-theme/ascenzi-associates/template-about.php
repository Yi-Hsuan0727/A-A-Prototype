<?php
/**
 * Template Name: About
 */
if (!defined('ABSPATH')) { exit; }
get_header();
$id = get_the_ID();
$firm_img = ascenzi_meta_raw($id, 'firm_image');
$firm_img_url = $firm_img ? wp_get_attachment_image_url($firm_img, 'ascenzi-hero') : '';
$leader_photo = ascenzi_meta_raw($id, 'leader_photo');
$leader_photo_url = $leader_photo ? wp_get_attachment_image_url($leader_photo, 'medium') : '';
?>

<section class="dark-hero" style="background:#FFFFFF; color:#0B0B0B">
    <div class="hero-grid">
        <div style="padding:clamp(44px,6vw,72px) var(--gutter) clamp(48px,6vw,80px)">
            <div class="kicker">
                <span class="kicker__rule"></span>
                <span class="kicker__label"><?php echo esc_html(ascenzi_meta($id, 'firm_kicker')); ?></span>
            </div>
            <h1 style="font-size:clamp(34px,5.4vw,58px); line-height:1.08; letter-spacing:-1.4px; font-weight:400; text-wrap:pretty"><?php echo esc_html(ascenzi_meta($id, 'firm_title')); ?></h1>
            <div style="height:1px; background:#0028FF; margin:clamp(28px,4vw,40px) 0 clamp(24px,3vw,32px); max-width:520px"></div>
            <p style="margin:0; font-size:16.5px; line-height:1.75; color:#4A5057; max-width:600px; text-wrap:pretty"><?php echo esc_html(ascenzi_meta($id, 'firm_p1')); ?></p>
            <p style="margin:22px 0 0; font-size:16.5px; line-height:1.75; color:#4A5057; max-width:600px; text-wrap:pretty"><?php echo esc_html(ascenzi_meta($id, 'firm_p2')); ?></p>
        </div>
        <div class="hero-photo" style="<?php echo $firm_img_url ? 'background-image:url(' . esc_url($firm_img_url) . ')' : ''; ?>"></div>
    </div>
</section>

<section class="contact-cta contact-cta--light">
    <div class="container container--narrow" style="text-align:center">
        <div style="font-family:var(--font-mono); font-size:10.5px; letter-spacing:1.6px; text-transform:uppercase; color:rgba(255,255,255,0.72)"><?php echo esc_html(ascenzi_meta($id, 'spirit_label')); ?></div>
        <p style="margin:clamp(24px,3vw,34px) 0 0; font-size:clamp(24px,3.6vw,40px); line-height:1.32; letter-spacing:-0.7px; text-wrap:balance">&ldquo;<?php echo esc_html(ascenzi_meta($id, 'spirit_quote')); ?>&rdquo;</p>
        <div style="font-family:var(--font-mono); font-size:11px; letter-spacing:2px; text-transform:uppercase; color:rgba(255,255,255,0.82); margin-top:28px">&mdash; <?php echo esc_html(ascenzi_meta_raw($id, 'spirit_attribution')); ?></div>
    </div>
</section>

<section id="leadership" class="section" style="background:#FFFFFF">
    <div class="container">
        <div class="kicker" style="margin-bottom:clamp(28px,4vw,40px)">
            <span class="kicker__rule"></span>
            <span class="kicker__label"><?php echo esc_html(ascenzi_meta($id, 'leadership_kicker')); ?></span>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(min(320px,100%), 1fr)); gap:clamp(32px,5vw,64px); align-items:start">
            <div style="border:1px solid var(--hairline-light); border-radius:3px; padding:14px 14px 0; background:#FBFBFC">
                <?php if ($leader_photo_url) : ?>
                    <img src="<?php echo esc_url($leader_photo_url); ?>" alt="<?php echo esc_attr(ascenzi_meta_raw($id, 'leader_name')); ?>" style="width:100%; aspect-ratio:4/5; object-fit:cover; object-position:center top; display:block; background:#EDEFF3">
                <?php endif; ?>
                <div style="border-left:2px solid #0028FF; padding:18px 0 22px 16px; margin-top:16px">
                    <div style="font-size:21px; line-height:1.25; letter-spacing:-0.3px; color:#0B0B0B"><?php echo esc_html(ascenzi_meta_raw($id, 'leader_name')); ?></div>
                    <div style="font-family:var(--font-mono); font-size:10.5px; letter-spacing:1.4px; text-transform:uppercase; color:#6E7373; margin-top:8px"><?php echo esc_html(ascenzi_meta($id, 'leader_role')); ?></div>
                </div>
            </div>
            <div>
                <h2 class="road__h2"><?php echo esc_html(ascenzi_meta($id, 'lead_h2')); ?></h2>
                <p style="margin-top:24px; font-size:16px; line-height:1.75; color:#4A5057; text-wrap:pretty"><?php echo esc_html(ascenzi_meta($id, 'lead_p1')); ?></p>
                <p style="margin-top:18px; font-size:16px; line-height:1.75; color:#4A5057; text-wrap:pretty"><?php echo esc_html(ascenzi_meta($id, 'lead_p2')); ?></p>
                <p style="margin-top:18px; font-size:16px; line-height:1.75; color:#4A5057; text-wrap:pretty"><?php echo esc_html(ascenzi_meta($id, 'lead_p3')); ?></p>
                <div style="height:1px; background:#0028FF; margin:clamp(28px,3.5vw,38px) 0 24px"></div>
                <div style="font-family:var(--font-mono); font-size:10.5px; letter-spacing:1.6px; text-transform:uppercase; color:#0028FF; margin-bottom:18px"><?php echo esc_html(ascenzi_meta($id, 'cred_label')); ?></div>
                <div style="display:flex; flex-direction:column; gap:12px">
                    <?php foreach (ascenzi_repeater($id, 'credentials') as $c) : ?>
                        <span style="display:flex; gap:16px; align-items:baseline; font-size:15px; line-height:1.55; color:#0B0B0B"><span style="color:#0028FF">&mdash;</span><span><?php echo esc_html(ascenzi_row_field($c, 'text')); ?></span></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="enquiry" class="contact-cta">
    <div class="container contact-cta__grid">
        <div>
            <h2 class="contact-cta__title"><?php echo esc_html(ascenzi_meta($id, 'about_cta_title')); ?></h2>
            <p class="contact-cta__body"><?php echo esc_html(ascenzi_meta($id, 'about_cta_body')); ?></p>
        </div>
        <div class="contact-cta__tiles">
            <button type="button" class="contact-cta__submit" data-open-contact="contact"><?php echo esc_html(ascenzi_t('submit_enquiry')); ?> <span>&rarr;</span></button>
            <a href="<?php echo esc_url(ascenzi_phone_href()); ?>" class="contact-cta__tile">
                <span class="contact-cta__tile-label"><?php echo esc_html(ascenzi_t('phone')); ?></span>
                <span class="contact-cta__tile-value"><?php echo esc_html(ascenzi_phone()); ?></span>
            </a>
            <div class="contact-cta__tile">
                <span class="contact-cta__tile-label"><?php echo esc_html(ascenzi_t('office')); ?></span>
                <span class="contact-cta__tile-value"><?php echo esc_html(ascenzi_office()); ?></span>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
