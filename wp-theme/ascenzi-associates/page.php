<?php
/**
 * Fallback template for any Page not assigned one of the custom page
 * templates (Home / About / Legal Page). Renders the native editor content
 * in the site's typography, wrapped in the standard content container.
 */
if (!defined('ABSPATH')) { exit; }
get_header();
while (have_posts()) : the_post();
    ?>
    <section class="section" style="background:#FFFFFF">
        <div class="container container--article">
            <h1 style="font-size:clamp(32px,4.6vw,48px); line-height:1.1; letter-spacing:-1.2px; font-weight:400; text-wrap:pretty; margin-bottom:clamp(28px,4vw,40px)"><?php the_title(); ?></h1>
            <div class="post-body" style="max-width:none; padding:0">
                <?php the_content(); ?>
            </div>
        </div>
    </section>
    <?php
endwhile;
get_footer();
