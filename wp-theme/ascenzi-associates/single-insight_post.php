<?php
/**
 * Single Insight article. No breadcrumb, no caption under the hero image,
 * no second tag list at the bottom (topic tags appear once, under the
 * headline) — matches decisions made against the original prototype.
 * Body is a bilingual WYSIWYG meta field (see inc/metaboxes-insight.php),
 * output raw since it is rich HTML the admin authored in the editor.
 */
if (!defined('ABSPATH')) { exit; }
get_header();
while (have_posts()) : the_post();
    $id = get_the_ID();
    $cats = get_the_terms($id, 'insight_category');
    $cat = ($cats && !is_wp_error($cats)) ? $cats[0] : null;
    $tags = get_the_terms($id, 'insight_tag');
    $hero_url = get_the_post_thumbnail_url($id, 'ascenzi-hero');
    $dek = ascenzi_meta($id, 'dek');
    $author = ascenzi_meta_raw($id, 'author_name');
    $role = ascenzi_meta($id, 'author_role');
    $pull_quote = ascenzi_meta($id, 'pull_quote');
    $body = ascenzi_meta($id, 'body');

    $prev = get_previous_post(false, '', 'insight_category');
    $next = get_next_post(false, '', 'insight_category');
    ?>

    <article>
        <section class="post-hero">
            <div class="container container--article" style="padding-top:clamp(40px,5vw,64px); padding-bottom:clamp(44px,6vw,72px)">
                <div class="post-hero__meta">
                    <?php if ($cat) : ?><a href="<?php echo esc_url(get_term_link($cat)); ?>" class="post-hero__tag"><?php echo esc_html(ascenzi_term_label($cat)); ?></a><span class="post-hero__dot"></span><?php endif; ?>
                    <span class="post-hero__date"><?php echo esc_html(get_the_date()); ?></span>
                </div>
                <h1 class="post-hero__title"><?php the_title(); ?></h1>
                <?php if ($dek) : ?><p class="post-hero__dek"><?php echo esc_html($dek); ?></p><?php endif; ?>
                <?php if ($tags && !is_wp_error($tags)) : ?>
                    <div class="post-hero__tags">
                        <?php foreach ($tags as $tag) : ?>
                            <a href="<?php echo esc_url(get_term_link($tag)); ?>" class="post-hero__topic-tag"><?php echo esc_html(ascenzi_term_label($tag)); ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($author) : ?>
                    <div class="post-hero__byline">
                        <span class="post-hero__by-label"><?php echo esc_html(ascenzi_t('by')); ?></span>
                        <span class="post-hero__by-name"><?php echo esc_html($author); ?></span>
                        <?php if ($role) : ?><span class="post-hero__by-role"><?php echo esc_html($role); ?></span><?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php if ($hero_url) : ?>
            <div class="post-hero-img" style="background-image:url('<?php echo esc_url($hero_url); ?>')" role="img" aria-label="<?php the_title_attribute(); ?>"></div>
        <?php endif; ?>

        <div class="post-body">
            <?php if ($pull_quote) : ?>
                <blockquote><?php echo esc_html($pull_quote); ?></blockquote>
            <?php endif; ?>
            <?php echo wp_kses_post($body); ?>
        </div>

        <section class="back-link-row">
            <div class="container container--article">
                <button type="button" class="btn--bordered" style="margin-bottom:28px" data-history-back>&larr; <?php echo esc_html(ascenzi_t('back')); ?></button>
            </div>
        </section>

        <?php if ($prev || $next) : ?>
            <section class="post-nav">
                <div class="post-nav__grid">
                    <?php if ($prev) : ?>
                        <a href="<?php echo esc_url(get_permalink($prev)); ?>" class="post-nav__link">
                            <span class="post-nav__label">&larr; <?php echo esc_html(ascenzi_t('previous')); ?></span>
                            <span class="post-nav__title"><?php echo esc_html(get_the_title($prev)); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if ($next) : ?>
                        <a href="<?php echo esc_url(get_permalink($next)); ?>" class="post-nav__link post-nav__link--next">
                            <span class="post-nav__label"><?php echo esc_html(ascenzi_t('next')); ?> &rarr;</span>
                            <span class="post-nav__title"><?php echo esc_html(get_the_title($next)); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
    </article>

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
