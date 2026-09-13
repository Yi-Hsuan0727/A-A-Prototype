<?php
/**
 * Insight article list — category pills + keyword search + client-side
 * pagination (see assets/js/main.js). All published articles are queried
 * up-front so the filters can run entirely in the browser, same as the
 * original prototype.
 */
if (!defined('ABSPATH')) { exit; }
get_header();
$categories = get_terms(['taxonomy' => 'insight_category', 'hide_empty' => false]);
?>

<section id="all" class="section" style="background:#FFFFFF">
    <div class="container">
        <div class="kicker">
            <span class="kicker__rule"></span>
            <span class="kicker__label"><?php echo esc_html(ascenzi_t('insight_kicker_archive')); ?></span>
        </div>
        <div style="display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:22px 48px; margin-bottom:clamp(30px,4vw,44px)">
            <h1 style="margin:0; font-size:clamp(32px,4.8vw,50px); line-height:1.1; letter-spacing:-1.2px; font-weight:400; max-width:720px; text-wrap:pretty"><?php echo esc_html(ascenzi_t('insight_archive_title')); ?></h1>
            <p style="margin:0; font-size:15px; line-height:1.7; color:#4A5057; max-width:340px"><?php echo esc_html(ascenzi_t('insight_archive_blurb')); ?></p>
        </div>

        <div class="insight-filterbar">
            <div class="insight-pills" role="group">
                <button type="button" class="insight-pill is-active" data-category="all"><?php echo esc_html(ascenzi_t('all')); ?></button>
                <?php foreach ($categories as $cat) : ?>
                    <button type="button" class="insight-pill" data-category="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html(ascenzi_term_label($cat)); ?></button>
                <?php endforeach; ?>
            </div>
            <label class="insight-search">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="7" cy="7" r="5" stroke="#6E7373" stroke-width="1.4"/><path d="M11 11l3.5 3.5" stroke="#6E7373" stroke-width="1.4" stroke-linecap="round"/></svg>
                <input type="search" data-insight-search placeholder="<?php echo esc_attr(ascenzi_t('search_placeholder')); ?>" aria-label="<?php echo esc_attr(ascenzi_t('search_placeholder')); ?>">
            </label>
        </div>

        <div class="insight-empty" data-insight-empty style="display:none">
            <p><?php echo esc_html(ascenzi_t('no_results')); ?></p>
            <button type="button" class="btn btn--outline" data-insight-clear><?php echo esc_html(ascenzi_t('clear_filters')); ?></button>
        </div>

        <div class="insight-grid insight-grid--list" data-insight-grid>
            <?php
            $query = new WP_Query(['post_type' => 'insight_post', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC']);
            while ($query->have_posts()) : $query->the_post();
                get_template_part('template-parts/insight-card');
            endwhile;
            wp_reset_postdata();
            ?>
        </div>

        <div class="insight-pagination" data-insight-pager></div>
    </div>
</section>

<section class="contact-cta">
    <div class="container contact-cta__grid">
        <div>
            <h2 class="contact-cta__title"><?php echo esc_html(ascenzi_t('newsletter_cta_title')); ?></h2>
            <p class="contact-cta__body"><?php echo esc_html(ascenzi_t('newsletter_blurb')); ?></p>
        </div>
        <div class="contact-cta__tiles" style="background:none; border:none">
            <form class="site-footer__newsletter-form" style="border:1px solid rgba(255,255,255,0.4); max-width:420px" data-newsletter-form>
                <input type="email" required placeholder="<?php echo esc_attr(ascenzi_t('email')); ?>" aria-label="<?php echo esc_attr(ascenzi_t('email')); ?>" style="color:#FFFFFF">
                <button type="submit" style="background:#FFFFFF; color:#0028FF"><?php echo esc_html(ascenzi_t('subscribe')); ?></button>
            </form>
            <div class="site-footer__newsletter-note" data-newsletter-note style="color:#FFFFFF; margin-top:10px"></div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
