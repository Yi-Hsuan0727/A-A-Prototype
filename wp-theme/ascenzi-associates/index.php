<?php
/**
 * Ultimate fallback template (required by WordPress). In normal use the
 * site's Front Page uses template-home.php and everything else has a more
 * specific template, so this mainly covers the core "post" type if it's
 * ever used, or a raw search-results / 404-ish situation.
 */
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<section class="section" style="background:#FFFFFF">
    <div class="container container--article">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article style="margin-bottom:56px; padding-bottom:56px; border-bottom:1px solid var(--hairline-light)">
                    <h2 style="font-size:clamp(24px,3.2vw,34px); line-height:1.2; letter-spacing:-0.5px; font-weight:400"><a href="<?php the_permalink(); ?>" style="color:inherit"><?php the_title(); ?></a></h2>
                    <div style="font-size:13.5px; color:#6E7373; margin-top:8px"><?php echo esc_html(get_the_date()); ?></div>
                    <div class="post-body" style="max-width:none; padding:20px 0 0"><?php the_excerpt(); ?></div>
                </article>
            <?php endwhile; ?>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <h1 style="font-size:clamp(28px,4vw,40px); font-weight:400"><?php esc_html_e('Nothing found', 'ascenzi'); ?></h1>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
