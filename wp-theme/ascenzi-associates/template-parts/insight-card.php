<?php
/**
 * One Insight article card. Used on Home's preview grid and the Insight
 * archive. Must be called inside the loop (the_post() already run).
 */
if (!defined('ABSPATH')) { exit; }

$post_id = get_the_ID();
$cats = get_the_terms($post_id, 'insight_category');
$cat = ($cats && !is_wp_error($cats)) ? $cats[0] : null;
$img_url = get_the_post_thumbnail_url($post_id, 'ascenzi-card');
$dek = ascenzi_meta($post_id, 'dek');
$author = ascenzi_meta_raw($post_id, 'author_name');

$search_text = strtolower(get_the_title() . ' ' . $dek . ' ' . $author);
?>
<a href="<?php the_permalink(); ?>"
   class="insight-card"
   data-category="<?php echo $cat ? esc_attr($cat->slug) : ''; ?>"
   data-search-text="<?php echo esc_attr($search_text); ?>">
    <div class="insight-card__img" style="<?php echo $img_url ? 'background-image:url(' . esc_url($img_url) . ')' : ''; ?>" role="img" aria-label="<?php the_title_attribute(); ?>"></div>
    <div class="insight-card__meta">
        <?php if ($cat) : ?><span class="insight-card__tag"><?php echo esc_html(ascenzi_term_label($cat)); ?></span><?php endif; ?>
        <span><?php echo esc_html(get_the_date()); ?></span>
    </div>
    <div class="insight-card__title"><?php the_title(); ?></div>
    <?php if ($author) : ?>
        <div class="insight-card__author"><?php echo esc_html(ascenzi_t('by')); ?> <?php echo esc_html($author); ?></div>
    <?php endif; ?>
</a>
