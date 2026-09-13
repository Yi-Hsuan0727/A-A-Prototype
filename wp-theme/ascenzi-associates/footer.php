<?php
/**
 * Site footer: brand blurb, quick links, services, contact + newsletter,
 * bottom bar. Followed by the shared contact modal markup.
 */
if (!defined('ABSPATH')) { exit; }
$services = ascenzi_get_services();
?>
    <footer class="site-footer">
        <div class="site-footer__inner">
            <div class="site-footer__grid">
                <div class="site-footer__brand">
                    <img src="<?php echo esc_url(ASCENZI_URI . '/assets/images/logo-horizon.png'); ?>" alt="<?php bloginfo('name'); ?>">
                    <p><?php echo esc_html(ascenzi_t('footer_blurb')); ?></p>
                </div>

                <div>
                    <div class="site-footer__label"><?php esc_html_e('Quick Links', 'ascenzi'); ?></div>
                    <div class="site-footer__links">
                        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'ascenzi'); ?></a>
                        <a href="<?php echo esc_url(ascenzi_about_url()); ?>"><?php esc_html_e('About Us', 'ascenzi'); ?></a>
                        <a href="<?php echo esc_url(home_url('/#capabilities')); ?>"><?php esc_html_e('Services', 'ascenzi'); ?></a>
                        <a href="<?php echo esc_url(get_post_type_archive_link('insight_post')); ?>"><?php esc_html_e('Insight', 'ascenzi'); ?></a>
                    </div>
                </div>

                <div>
                    <div class="site-footer__label"><?php esc_html_e('Services', 'ascenzi'); ?></div>
                    <div class="site-footer__links">
                        <?php foreach ($services as $service) : ?>
                            <a href="<?php echo esc_url(get_permalink($service)); ?>"><?php echo esc_html(get_the_title($service)); ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div>
                    <div class="site-footer__label"><?php esc_html_e('Contact', 'ascenzi'); ?></div>
                    <div class="site-footer__contact">
                        <a href="<?php echo esc_url(ascenzi_phone_href()); ?>" class="site-footer__contact-item">
                            <span><?php echo esc_html(ascenzi_t('phone')); ?></span>
                            <span><?php echo esc_html(ascenzi_phone()); ?></span>
                        </a>
                        <div class="site-footer__contact-item">
                            <span><?php echo esc_html(ascenzi_t('office')); ?></span>
                            <span><?php echo esc_html(ascenzi_office()); ?></span>
                        </div>
                        <div class="site-footer__newsletter">
                            <div class="site-footer__label" style="margin-bottom:0"><?php echo esc_html(ascenzi_t('newsletter')); ?></div>
                            <p><?php echo esc_html(ascenzi_t('newsletter_blurb')); ?></p>
                            <form class="site-footer__newsletter-form" data-newsletter-form>
                                <input type="email" required placeholder="<?php echo esc_attr(ascenzi_t('email')); ?>" aria-label="<?php echo esc_attr(ascenzi_t('email')); ?>">
                                <button type="submit"><?php echo esc_html(ascenzi_t('subscribe')); ?></button>
                            </form>
                            <div class="site-footer__newsletter-note" data-newsletter-note></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="site-footer__bottom">
                <span>&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?> LLC</span>
                <a href="<?php echo esc_url(ascenzi_privacy_url()); ?>"><?php echo esc_html(ascenzi_t('privacy_terms')); ?></a>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php get_template_part('template-parts/contact-modal'); ?>

<?php wp_footer(); ?>
</body>
</html>
