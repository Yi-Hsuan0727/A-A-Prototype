<?php
/**
 * Shared enquiry modal — the only intake channel on the site.
 * mode="contact": 2-step enquiry (organization type, then details).
 * mode="partner": single-step partnership form.
 * Submission posts to admin-ajax.php (ascenzi_contact_submit) via main.js.
 */
if (!defined('ABSPATH')) { exit; }
?>
<div class="contact-modal-overlay" data-contact-overlay role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Enquiry', 'ascenzi'); ?>">
    <div class="contact-modal" data-contact-modal data-mode="contact">
        <div class="contact-modal__head">
            <div>
                <div class="contact-modal__kicker" data-contact-kicker><?php echo esc_html(ascenzi_t('enquiry_kicker')); ?></div>
                <div class="contact-modal__firm"><?php bloginfo('name'); ?></div>
            </div>
            <button type="button" class="contact-modal__close" aria-label="<?php echo esc_attr(ascenzi_t('close')); ?>" data-contact-close>&times;</button>
        </div>

        <div class="contact-modal__progress" data-contact-progress>
            <div class="contact-modal__progress-bar" style="width:50%"></div>
        </div>

        <!-- Contact step 1: organization type -->
        <div class="contact-modal__step" data-contact-step="org">
            <h3 class="contact-modal__question"><?php echo esc_html(ascenzi_t('org_question')); ?></h3>
            <div class="contact-modal__org-grid" data-org-grid>
                <?php foreach (['general_contractor', 'owner', 'vendor', 'other'] as $org_key) : ?>
                    <button type="button" class="contact-modal__org-option" data-org-option="<?php echo esc_attr($org_key); ?>"><?php echo esc_html(ascenzi_t('org_' . $org_key)); ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Contact step 2: details -->
        <div class="contact-modal__step" data-contact-step="details">
            <h3 class="contact-modal__question"><?php echo esc_html(ascenzi_t('reach_question')); ?></h3>
            <div class="contact-modal__fields">
                <label class="contact-modal__field">
                    <span class="contact-modal__label"><?php echo esc_html(ascenzi_t('name')); ?></span>
                    <input type="text" name="name" autocomplete="name" required>
                </label>
                <label class="contact-modal__field">
                    <span class="contact-modal__label"><?php echo esc_html(ascenzi_t('company')); ?></span>
                    <input type="text" name="company" autocomplete="organization">
                </label>
                <label class="contact-modal__field contact-modal__field--full">
                    <span class="contact-modal__label"><?php echo esc_html(ascenzi_t('email')); ?></span>
                    <input type="email" name="email" autocomplete="email" required>
                </label>
                <label class="contact-modal__field contact-modal__field--full">
                    <span class="contact-modal__label"><?php echo esc_html(ascenzi_t('more_info')); ?></span>
                    <textarea name="message" rows="3" placeholder="<?php echo esc_attr(ascenzi_t('more_placeholder')); ?>"></textarea>
                </label>
            </div>
            <p class="contact-modal__error" data-contact-error></p>
        </div>

        <!-- Partnership: single step -->
        <div class="contact-modal__step" data-contact-step="partner">
            <h3 class="contact-modal__question" style="margin-bottom:26px"><?php echo esc_html(ascenzi_t('partner_title')); ?></h3>
            <div class="contact-modal__fields">
                <label class="contact-modal__field">
                    <span class="contact-modal__label"><?php echo esc_html(ascenzi_t('name')); ?></span>
                    <input type="text" name="name" autocomplete="name" required>
                </label>
                <label class="contact-modal__field">
                    <span class="contact-modal__label"><?php echo esc_html(ascenzi_t('company')); ?></span>
                    <input type="text" name="company" autocomplete="organization">
                </label>
                <label class="contact-modal__field">
                    <span class="contact-modal__label"><?php echo esc_html(ascenzi_t('email')); ?></span>
                    <input type="email" name="email" autocomplete="email" required>
                </label>
                <label class="contact-modal__field">
                    <span class="contact-modal__label"><?php echo esc_html(ascenzi_t('phone_optional')); ?></span>
                    <input type="tel" name="phone" autocomplete="tel">
                </label>
                <label class="contact-modal__field contact-modal__field--full">
                    <span class="contact-modal__label"><?php echo esc_html(ascenzi_t('need')); ?></span>
                    <textarea name="message" rows="4" placeholder="<?php echo esc_attr(ascenzi_t('need_placeholder')); ?>"></textarea>
                </label>
            </div>
            <p class="contact-modal__error" data-contact-error></p>
        </div>

        <!-- Confirmation -->
        <div class="contact-modal__done" data-contact-done>
            <div class="contact-modal__received"><?php echo esc_html(ascenzi_t('received')); ?></div>
            <h3 class="contact-modal__done-title"><?php echo esc_html(ascenzi_t('done_title')); ?></h3>
            <p class="contact-modal__done-body"><?php echo esc_html(ascenzi_t('done_body')); ?></p>
            <p class="contact-modal__done-contact"><?php echo esc_html(ascenzi_t('prefer_call')); ?> <?php echo esc_html(ascenzi_phone()); ?> &middot; <?php echo esc_html(ascenzi_office()); ?>.</p>
            <button type="button" class="btn btn--primary" data-contact-close><?php echo esc_html(ascenzi_t('close')); ?></button>
        </div>

        <!-- Footer nav -->
        <div class="contact-modal__nav" data-contact-nav>
            <div class="contact-modal__nav-left">
                <button type="button" class="contact-modal__back" data-contact-back><?php echo '&larr; ' . esc_html(ascenzi_t('back')); ?></button>
                <span class="contact-modal__note" data-contact-note><?php echo esc_html(ascenzi_t('note')); ?></span>
            </div>
            <div class="contact-modal__nav-right">
                <span class="contact-modal__step-count" data-contact-step-count></span>
                <button type="button" class="contact-modal__skip" data-contact-skip><?php echo esc_html(ascenzi_t('skip')); ?></button>
                <button type="button" class="contact-modal__submit" data-contact-submit><?php echo esc_html(ascenzi_t('submit')); ?></button>
            </div>
        </div>
    </div>
</div>
