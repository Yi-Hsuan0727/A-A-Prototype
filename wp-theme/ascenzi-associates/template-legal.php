<?php
/**
 * Template Name: Legal Page
 * Used for Privacy Policy (and can be reused for a future Terms page).
 */
if (!defined('ABSPATH')) { exit; }
get_header();
$id = get_the_ID();
?>

<section class="section" style="background:#FFFFFF">
    <div class="container container--legal">
        <div class="kicker">
            <span class="kicker__rule"></span>
            <span class="kicker__label"><?php echo esc_html(ascenzi_meta($id, 'legal_kicker')); ?></span>
        </div>
        <h1 style="font-size:clamp(32px,4.6vw,48px); line-height:1.1; letter-spacing:-1.2px; font-weight:400; text-wrap:pretty"><?php the_title(); ?></h1>
        <div class="legal-effective"><?php echo esc_html(ascenzi_meta($id, 'legal_effective')); ?></div>
        <p class="legal-intro"><?php echo esc_html(ascenzi_meta($id, 'legal_intro')); ?></p>

        <div class="legal-sections">
            <?php foreach (ascenzi_repeater($id, 'legal_sections') as $i => $section) :
                $items = ascenzi_row_lines($section, 'list');
                ?>
                <div class="legal-section">
                    <h2><?php echo (int) ($i + 1); ?>. <?php echo esc_html(ascenzi_row_field($section, 'title')); ?></h2>
                    <p><?php echo esc_html(ascenzi_row_field($section, 'body')); ?></p>
                    <?php if ($items) : ?>
                        <ul>
                            <?php foreach ($items as $item) : ?><li><?php echo esc_html($item); ?></li><?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="legal-contact">
            <?php echo esc_html(ascenzi_meta($id, 'legal_contact_line')); ?> <a href="<?php echo esc_url(ascenzi_phone_href()); ?>"><?php echo esc_html(ascenzi_phone()); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
