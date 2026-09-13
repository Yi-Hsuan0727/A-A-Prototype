<?php
/**
 * Renders the header's "Services" mega-dropdown from the Service CPT
 * (ordered by admin drag/drop via page-attributes), instead of a manual
 * WordPress menu — so adding/reordering a Service post is all it takes to
 * update the dropdown everywhere it appears.
 */

if (!defined('ABSPATH')) { exit; }

function ascenzi_render_services_dropdown() {
    $services = ascenzi_get_services();
    if (!$services) { return; }
    $current_id = is_singular('service') ? get_the_ID() : 0;
    ?>
    <div class="nav-services" data-nav-services>
        <button type="button" class="nav-services__trigger" aria-haspopup="true" aria-expanded="false" data-services-trigger>
            <span><?php esc_html_e('Services', 'ascenzi'); ?></span>
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3.5 6l4.5 4.5L12.5 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <div class="nav-services__panel" data-services-panel>
            <div class="nav-services__grid">
                <div class="nav-services__list">
                    <?php foreach ($services as $i => $service) :
                        $is_active = $service->ID === $current_id;
                        $img_id = get_post_thumbnail_id($service->ID);
                        $img_url = $img_id ? wp_get_attachment_image_url($img_id, 'ascenzi-card') : '';
                        ?>
                        <a href="<?php echo esc_url(get_permalink($service)); ?>"
                           class="nav-services__row<?php echo $is_active ? ' is-active' : ''; ?>"
                           data-services-row
                           data-preview-index="<?php echo (int) $i; ?>">
                            <span class="nav-services__num"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
                            <span>
                                <span class="nav-services__row-title"><?php echo esc_html(get_the_title($service)); ?></span>
                                <span class="nav-services__row-desc"><?php echo esc_html(ascenzi_meta($service->ID, 'summary')); ?></span>
                            </span>
                            <span class="nav-services__row-arrow" aria-hidden="true">&rarr;</span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="nav-services__preview" data-services-preview>
                    <?php foreach ($services as $i => $service) :
                        $img_id = get_post_thumbnail_id($service->ID);
                        $img_url = $img_id ? wp_get_attachment_image_url($img_id, 'ascenzi-card') : '';
                        $is_shown = $current_id ? ($service->ID === $current_id) : ($i === 0);
                        ?>
                        <div class="nav-services__preview-img<?php echo $is_shown ? ' is-shown' : ''; ?>"
                             data-preview-index="<?php echo (int) $i; ?>"
                             style="<?php echo $img_url ? 'background-image:url(' . esc_url($img_url) . ')' : ''; ?>"></div>
                    <?php endforeach; ?>
                    <div class="nav-services__preview-caption">
                        <span data-services-preview-title>
                            <?php echo esc_html(get_the_title($current_id ? $current_id : $services[0])); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
