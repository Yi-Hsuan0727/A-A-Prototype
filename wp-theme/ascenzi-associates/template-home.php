<?php
/**
 * Template Name: Home
 * Assign this to the Page you set as your site's Front Page
 * (Settings → Reading → Your homepage displays → A static page).
 */
if (!defined('ABSPATH')) { exit; }
get_header();
$id = get_the_ID();
$services = ascenzi_get_services();
?>

<section class="hero">
    <div class="hero__grid">
        <div class="hero__text" data-reveal="1">
            <h1 class="hero__title"><?php echo esc_html(ascenzi_meta($id, 'hero_title')); ?></h1>
            <p class="hero__sub"><?php echo esc_html(ascenzi_meta($id, 'hero_sub')); ?></p>
            <div class="hero__ctas">
                <button type="button" class="btn btn--primary" data-open-contact="contact">
                    <?php ascenzi_icon_button_chat(); ?>
                    <?php echo esc_html(ascenzi_meta($id, 'hero_cta_primary')); ?>
                </button>
                <a href="#capabilities" class="btn btn--outline">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="4" width="7" height="7" rx="0.5" stroke="#0028FF" stroke-width="1.5"/><rect x="13" y="4" width="7" height="7" rx="0.5" stroke="#0028FF" stroke-width="1.5"/><rect x="4" y="13" width="7" height="7" rx="0.5" stroke="#0028FF" stroke-width="1.5"/><rect x="13" y="13" width="7" height="7" rx="0.5" stroke="#0028FF" stroke-width="1.5"/></svg>
                    <?php echo esc_html(ascenzi_meta($id, 'hero_cta_secondary')); ?>
                </a>
            </div>
        </div>
        <div class="hero__carousel">
            <?php
            $slides = ascenzi_repeater($id, 'hero_slides');
            foreach ($slides as $i => $slide) :
                $img_url = !empty($slide['image']) ? wp_get_attachment_image_url($slide['image'], 'ascenzi-hero') : '';
                if (!$img_url) { continue; }
                ?>
                <span class="hero__slide<?php echo $i === 0 ? ' is-active' : ''; ?>" style="background-image:url('<?php echo esc_url($img_url); ?>')"></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="purpose" class="purpose">
    <div class="container">
        <div class="purpose__head" data-reveal="1">
            <div>
                <div class="kicker">
                    <span class="kicker__rule"></span>
                    <span class="kicker__label" style="color:rgba(255,255,255,0.8)"><?php echo esc_html(ascenzi_meta($id, 'purpose_kicker')); ?></span>
                </div>
                <h2 class="purpose__title"><?php echo esc_html(ascenzi_meta($id, 'purpose_title')); ?></h2>
            </div>
            <p class="purpose__body"><?php echo esc_html(ascenzi_meta($id, 'purpose_body')); ?></p>
        </div>
        <div class="purpose__grid" data-reveal="1" data-reveal-delay="120">
            <?php foreach (ascenzi_repeater($id, 'purpose_cards') as $i => $card) :
                $img_url = !empty($card['image']) ? wp_get_attachment_image_url($card['image'], 'ascenzi-card') : '';
                $items = ascenzi_row_lines($card, 'items');
                ?>
                <div class="purpose-card">
                    <span class="purpose-card__img" style="<?php echo $img_url ? 'background-image:url(' . esc_url($img_url) . ')' : ''; ?>"></span>
                    <span class="purpose-card__gradient"></span>
                    <span class="purpose-card__spacer"></span>
                    <div class="purpose-card__head">
                        <span class="purpose-card__num"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
                        <span class="purpose-card__title"><?php echo esc_html(ascenzi_row_field($card, 'title')); ?></span>
                    </div>
                    <p class="purpose-card__blurb"><?php echo esc_html(ascenzi_row_field($card, 'blurb')); ?></p>
                    <div class="purpose-card__list">
                        <div class="purpose-card__list-inner">
                            <?php foreach ($items as $item) : ?>
                                <span class="bullet-item"><span class="bullet-item__dot"></span><span><?php echo esc_html($item); ?></span></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="road" class="road">
    <div class="container">
        <div class="road__intro" data-reveal="1">
            <h2 class="road__h2"><?php echo wp_kses_post(nl2br(esc_html(ascenzi_meta($id, 'road_title')))); ?></h2>
            <p class="road__body"><?php echo esc_html(ascenzi_meta($id, 'road_body')); ?></p>
        </div>

        <?php $road_steps = ascenzi_repeater($id, 'road_steps'); $n = count($road_steps); ?>
        <div class="road__canvas desktop-only" data-road-canvas data-reveal="1" data-reveal-delay="120">
            <svg viewBox="0 0 1000 600" preserveAspectRatio="none" aria-hidden="true">
                <path d="M-40 432 Q340 252 1060 72" fill="none" stroke="rgba(0,40,255,0.45)" stroke-width="1" vector-effect="non-scaling-stroke"/>
            </svg>
            <?php foreach ($road_steps as $i => $step) : ?>
                <div class="road__step" data-step="<?php echo (int) $i; ?>">
                    <span class="road__step-dot" aria-hidden="true"></span>
                    <div class="road__step-box">
                        <span style="display:flex; align-items:center; gap:10px">
                            <?php ascenzi_icon($step['icon'] ?? '', 26, '#0028FF', 'road__step-icon'); ?>
                            <span class="road__step-num"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
                        </span>
                        <span class="road__step-title"><?php echo esc_html(ascenzi_row_field($step, 'title')); ?></span>
                        <span class="road__step-desc"><?php echo esc_html(ascenzi_row_field($step, 'desc')); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="road__list mobile-only" data-reveal="1" data-reveal-delay="120">
            <?php foreach ($road_steps as $i => $step) : ?>
                <div class="road__list-item">
                    <span style="display:flex; align-items:center; gap:10px">
                        <?php ascenzi_icon($step['icon'] ?? '', 26, '#0028FF'); ?>
                        <span class="road__step-num"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
                    </span>
                    <span class="road__step-title" style="margin-top:0"><?php echo esc_html(ascenzi_row_field($step, 'title')); ?></span>
                    <span class="road__step-desc"><?php echo esc_html(ascenzi_row_field($step, 'desc')); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="coverage" class="coverage">
    <div class="container container--narrow coverage__intro">
        <p class="coverage__headline" data-reveal="1">
            <?php
            $headline = ascenzi_meta($id, 'coverage_headline');
            $highlight = ascenzi_meta($id, 'coverage_highlight');
            if ($highlight && strpos($headline, $highlight) !== false) {
                echo wp_kses_post(str_replace($highlight, '<span style="color:#5B85FF">' . esc_html($highlight) . '</span>', esc_html($headline)));
            } else {
                echo esc_html($headline);
            }
            ?>
        </p>
        <div data-reveal="1" data-reveal-delay="120">
            <p class="coverage__body"><?php echo esc_html(ascenzi_meta($id, 'coverage_body')); ?></p>
        </div>
    </div>
    <div class="container" style="max-width:1100px" data-reveal="1" data-reveal-delay="200">
        <div class="coverage__map" data-road-map></div>
    </div>
</section>

<section class="tagline-section">
    <div class="tagline-wrap">
        <div class="tagline">
            <?php $ta = ascenzi_meta($id, 'tagline_a'); $tb = ascenzi_meta($id, 'tagline_b'); ?>
            <p class="tagline__ghost" aria-hidden="true"><?php echo esc_html($ta . ' ' . $tb); ?></p>
            <p class="tagline__live" data-tagline-live data-a="<?php echo esc_attr($ta); ?>" data-b="<?php echo esc_attr($tb); ?>" data-reveal="1">
                <span data-tagline-a></span> <span style="color:#0028FF" data-tagline-b></span><span class="tagline__cursor" data-tagline-cursor aria-hidden="true">|</span>
            </p>
        </div>
    </div>
</section>

<section id="capabilities" class="capabilities">
    <div class="container">
        <div class="capabilities__head" data-reveal="1">
            <div style="max-width:640px">
                <div class="kicker">
                    <span class="kicker__rule"></span>
                    <span class="kicker__label kicker__label--on-dark"><?php echo esc_html(ascenzi_meta($id, 'svc_kicker')); ?></span>
                </div>
                <h2 class="capabilities__title"><?php echo esc_html(ascenzi_meta($id, 'svc_title')); ?></h2>
            </div>
            <p style="margin:0; max-width:320px; font-size:14.5px; line-height:1.65; color:#A8B0BA"><?php echo esc_html(ascenzi_meta($id, 'svc_body')); ?></p>
        </div>

        <div class="capabilities__row" data-reveal="1" data-reveal-delay="120">
            <div class="cta-card">
                <div class="cta-card__inner">
                    <span class="cta-card__icon" style="mask-image:url('<?php echo esc_url(ASCENZI_URI . '/assets/images/icons/all-services.svg'); ?>'); -webkit-mask-image:url('<?php echo esc_url(ASCENZI_URI . '/assets/images/icons/all-services.svg'); ?>')"></span>
                    <span style="flex:1"></span>
                    <span class="cta-card__title"><?php echo esc_html(ascenzi_meta($id, 'svc_cta_title')); ?></span>
                    <span class="cta-card__body"><?php echo esc_html(ascenzi_meta($id, 'svc_cta_body')); ?></span>
                    <button type="button" class="cta-card__btn" data-open-contact="contact"><?php echo esc_html(ascenzi_meta($id, 'svc_cta_button')); ?> &rarr;</button>
                </div>
            </div>

            <div class="pkg-cards">
                <?php foreach ($services as $service) :
                    $sid = $service->ID;
                    $img_id = get_post_thumbnail_id($sid);
                    $img_url = $img_id ? wp_get_attachment_image_url($img_id, 'ascenzi-card') : '';
                    $items = ascenzi_repeater($sid, 'items');
                    ?>
                    <a href="<?php echo esc_url(get_permalink($sid)); ?>" class="pkg-card">
                        <span class="pkg-card__img">
                            <span class="pkg-card__img-inner" style="<?php echo $img_url ? 'background-image:url(' . esc_url($img_url) . ')' : ''; ?>"></span>
                        </span>
                        <span class="pkg-card__icon-slot">
                            <?php ascenzi_icon(ascenzi_meta_raw($sid, 'icon'), 76, '#FFFFFF', 'pkg-card__icon'); ?>
                        </span>
                        <span class="pkg-card__title"><?php echo esc_html(get_the_title($sid)); ?></span>
                        <span class="pkg-card__desc"><?php echo esc_html(ascenzi_meta($sid, 'summary')); ?></span>
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

<section id="insights" class="section" style="background:#FFFFFF">
    <div class="container">
        <div class="insights-head" data-reveal="1">
            <div style="max-width:620px">
                <div class="kicker">
                    <span class="kicker__rule"></span>
                    <span class="kicker__label"><?php echo esc_html(ascenzi_meta($id, 'insight_kicker')); ?></span>
                </div>
                <h2 class="capabilities__title" style="color:#0B0B0B"><?php echo esc_html(ascenzi_meta($id, 'insight_title')); ?></h2>
            </div>
            <a href="<?php echo esc_url(get_post_type_archive_link('insight_post')); ?>" class="btn btn--outline"><?php echo esc_html(ascenzi_meta($id, 'insight_more')); ?> <span>&rarr;</span></a>
        </div>
        <div class="insight-grid" data-reveal="1" data-reveal-delay="120">
            <?php
            $recent = new WP_Query(['post_type' => 'insight_post', 'posts_per_page' => 3]);
            while ($recent->have_posts()) : $recent->the_post();
                get_template_part('template-parts/insight-card');
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>

<section id="partner" class="partnership">
    <div class="partnership__bg" style="<?php
        $bg_id = ascenzi_meta_raw($id, 'partner_bg');
        $bg_url = $bg_id ? wp_get_attachment_image_url($bg_id, 'ascenzi-hero') : '';
        echo $bg_url ? 'background-image:url(' . esc_url($bg_url) . ')' : '';
    ?>"></div>
    <div class="partnership__scrim"></div>
    <div class="container">
        <div class="partnership__grid" data-reveal="1">
            <div>
                <div class="kicker">
                    <span class="kicker__rule"></span>
                    <span class="kicker__label kicker__label--on-dark"><?php echo esc_html(ascenzi_meta($id, 'partner_kicker')); ?></span>
                </div>
                <h2 class="partnership__title"><?php echo esc_html(ascenzi_meta($id, 'partner_title')); ?></h2>
                <p class="partnership__body"><?php echo esc_html(ascenzi_meta($id, 'partner_body')); ?></p>
                <button type="button" class="btn btn--primary partnership__cta" data-open-contact="partner"><?php echo esc_html(ascenzi_meta($id, 'partner_cta')); ?> <span aria-hidden="true">&rarr;</span></button>
            </div>
            <div class="partner-cards">
                <?php foreach (ascenzi_repeater($id, 'partner_cards') as $card) : ?>
                    <div class="partner-card">
                        <?php ascenzi_icon($card['icon'] ?? '', 24, '#0028FF'); ?>
                        <span class="partner-card__spacer"></span>
                        <span class="partner-card__title"><?php echo esc_html(ascenzi_row_field($card, 'title')); ?></span>
                        <span class="partner-card__desc"><?php echo esc_html(ascenzi_row_field($card, 'desc')); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section id="faq" class="faq">
    <div class="container faq__grid">
        <div>
            <div class="kicker">
                <span class="kicker__rule"></span>
                <span class="kicker__label"><?php echo esc_html(ascenzi_meta($id, 'faq_kicker')); ?></span>
            </div>
            <h2 class="faq__title"><?php echo esc_html(ascenzi_meta($id, 'faq_title')); ?></h2>
            <p class="faq__body"><?php echo esc_html(ascenzi_meta($id, 'faq_body')); ?></p>
        </div>
        <div class="faq-list">
            <?php foreach (ascenzi_repeater($id, 'faq_items') as $i => $faq) : ?>
                <div class="faq-item">
                    <button type="button" class="faq-item__q" aria-expanded="false">
                        <span><?php echo esc_html(ascenzi_row_field($faq, 'q')); ?></span>
                        <span class="faq-item__sign">+</span>
                    </button>
                    <div class="faq-item__a"><?php echo esc_html(ascenzi_row_field($faq, 'a')); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="contact" class="contact-cta">
    <div class="container contact-cta__grid">
        <div>
            <h2 class="contact-cta__title"><?php echo esc_html(ascenzi_meta($id, 'contact_title')); ?></h2>
            <p class="contact-cta__body"><?php echo esc_html(ascenzi_meta($id, 'contact_body')); ?></p>
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
