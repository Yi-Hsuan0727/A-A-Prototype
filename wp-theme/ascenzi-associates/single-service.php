<?php
/**
 * Single "Service" page (Talent Acquisition, Licensing, U.S. Market Entry,
 * Supporting & Growth Services).
 */
if (!defined('ABSPATH')) { exit; }
get_header();
while (have_posts()) : the_post();
    $id = get_the_ID();
    $img_url = get_the_post_thumbnail_url($id, 'ascenzi-hero');
    $icon = ascenzi_meta_raw($id, 'icon');
    ?>

    <section class="dark-hero">
        <div class="hero-grid">
            <div class="dark-hero__col">
                <div class="kicker">
                    <span class="kicker__rule"></span>
                    <span class="kicker__label kicker__label--on-dark"><?php echo esc_html(ascenzi_meta($id, 'hero_kicker')); ?></span>
                </div>
                <h1 class="dark-hero__title"><?php echo esc_html(ascenzi_meta($id, 'hero_title')); ?></h1>
                <div class="dark-hero__tags">
                    <?php foreach (ascenzi_repeater($id, 'client_types') as $tag) : ?>
                        <span class="dark-hero__tag"><?php echo esc_html(ascenzi_row_field($tag, 'label')); ?></span>
                    <?php endforeach; ?>
                </div>
                <p class="dark-hero__sub"><?php echo esc_html(ascenzi_meta($id, 'hero_sub')); ?></p>
                <p class="dark-hero__p"><?php echo esc_html(ascenzi_meta($id, 'hero_p1')); ?></p>
                <p class="dark-hero__p"><?php echo esc_html(ascenzi_meta($id, 'hero_p2')); ?></p>
            </div>
            <div class="hero-photo" style="<?php echo $img_url ? 'background-image:url(' . esc_url($img_url) . ')' : ''; ?>"></div>
        </div>
    </section>

    <section class="section" style="background:#FFFFFF">
        <div class="container">
            <h2 class="page-h2"><?php echo esc_html(ascenzi_meta($id, 'handle_head')); ?></h2>
            <div class="checklist">
                <?php foreach (ascenzi_repeater($id, 'handle_items') as $row) : ?>
                    <div class="checklist__row">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex:none; margin-top:2px"><circle cx="10" cy="10" r="9.25" stroke="#0028FF" stroke-width="1.5"/><path d="M6 10.3l2.6 2.6L14.2 7" stroke="#0028FF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <div>
                            <div class="checklist__title"><?php echo esc_html(ascenzi_row_field($row, 'title')); ?></div>
                            <div class="checklist__desc"><?php echo esc_html(ascenzi_row_field($row, 'desc')); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php $how_head = ascenzi_meta($id, 'how_head'); if ($how_head) : ?>
                <h2 class="page-h2"><?php echo esc_html($how_head); ?></h2>
                <div class="steps-list">
                    <?php foreach (ascenzi_repeater($id, 'how_steps') as $i => $row) : ?>
                        <div class="steps-list__row">
                            <span class="steps-list__num"><?php echo esc_html(sprintf('STEP 0%d', $i + 1)); ?></span>
                            <span class="steps-list__title"><?php echo esc_html(ascenzi_row_field($row, 'title')); ?></span>
                            <span class="steps-list__desc"><?php echo esc_html(ascenzi_row_field($row, 'desc')); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="callout">
                <span class="callout__icon" style="mask-image:url('<?php echo esc_url(ASCENZI_URI . '/assets/images/icons/' . sanitize_file_name($icon) . '.svg'); ?>'); -webkit-mask-image:url('<?php echo esc_url(ASCENZI_URI . '/assets/images/icons/' . sanitize_file_name($icon) . '.svg'); ?>')"></span>
                <div class="callout__label"><?php echo esc_html(ascenzi_meta($id, 'callout_label')); ?></div>
                <p class="callout__body"><?php echo esc_html(ascenzi_meta($id, 'callout_body')); ?></p>
            </div>

            <h2 class="page-h2"><?php echo esc_html(ascenzi_meta($id, 'why_head')); ?></h2>
            <div class="why-grid">
                <?php foreach (ascenzi_repeater($id, 'why_cards') as $row) : ?>
                    <div class="why-card">
                        <?php ascenzi_icon($row['icon'] ?? '', 26, '#0028FF'); ?>
                        <div class="why-card__title"><?php echo esc_html(ascenzi_row_field($row, 'title')); ?></div>
                        <div class="why-card__desc"><?php echo esc_html(ascenzi_row_field($row, 'desc')); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <a href="<?php echo esc_url(home_url('/#capabilities')); ?>" class="btn--bordered" style="margin:clamp(56px,7vw,96px) 0 20px; text-decoration:none">&larr; <?php echo esc_html(ascenzi_t('back')); ?></a>

            <div id="other">
                <h2 class="page-h2" style="margin-top:0"><?php esc_html_e('Other services', 'ascenzi'); ?></h2>
                <div class="other-grid">
                    <?php foreach (ascenzi_get_services($id) as $other) :
                        $oid = $other->ID;
                        $img_id = get_post_thumbnail_id($oid);
                        $o_img_url = $img_id ? wp_get_attachment_image_url($img_id, 'ascenzi-card') : '';
                        $items = ascenzi_repeater($oid, 'items');
                        ?>
                        <a href="<?php echo esc_url(get_permalink($oid)); ?>" class="pkg-card">
                            <span class="pkg-card__img">
                                <span class="pkg-card__img-inner" style="<?php echo $o_img_url ? 'background-image:url(' . esc_url($o_img_url) . ')' : ''; ?>"></span>
                            </span>
                            <span class="pkg-card__icon-slot">
                                <?php ascenzi_icon(ascenzi_meta_raw($oid, 'icon'), 76, '#FFFFFF', 'pkg-card__icon'); ?>
                            </span>
                            <span class="pkg-card__title"><?php echo esc_html(get_the_title($oid)); ?></span>
                            <span class="pkg-card__desc"><?php echo esc_html(ascenzi_meta($oid, 'summary')); ?></span>
                            <span class="pkg-card__explore" aria-hidden="true">&rarr;</span>
                            <span class="pkg-card__divider"></span>
                            <span class="pkg-card__list">
                                <?php foreach ($items as $item) : ?>
                                    <span class="bullet-item"><span class="bullet-item__dot"></span><span><?php echo esc_html(ascenzi_row_field($item, 'text')); ?></span></span>
                                <?php endforeach; ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-cta">
        <div class="container contact-cta__grid">
            <div>
                <h2 class="contact-cta__title"><?php echo esc_html(ascenzi_t('service_cta_title')); ?></h2>
                <p class="contact-cta__body"><?php echo esc_html(ascenzi_t('service_cta_body')); ?></p>
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

<?php endwhile;
get_footer();
