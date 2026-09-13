<?php
/**
 * "Service" custom post type — the four package/service pages
 * (Talent Acquisition, Licensing, U.S. Market Entry, Supporting & Growth).
 * No archive/listing page by design (matches the original site, which
 * deliberately has no Services overview page — only the header's mega
 * dropdown and Home's package grid link to these).
 */

if (!defined('ABSPATH')) { exit; }

function ascenzi_register_service_cpt() {
    register_post_type('service', [
        'labels' => [
            'name'               => __('Services', 'ascenzi'),
            'singular_name'      => __('Service', 'ascenzi'),
            'add_new_item'       => __('Add New Service', 'ascenzi'),
            'edit_item'          => __('Edit Service', 'ascenzi'),
            'all_items'          => __('Services', 'ascenzi'),
            'menu_name'          => __('Services', 'ascenzi'),
        ],
        'public'             => true,
        'has_archive'        => false,
        'show_in_rest'       => false,
        'menu_icon'          => 'dashicons-hammer',
        'supports'           => ['title', 'thumbnail', 'page-attributes'],
        'rewrite'            => ['slug' => 'services', 'with_front' => false],
    ]);
}
add_action('init', 'ascenzi_register_service_cpt');

/** All Service posts, ordered by the admin-set menu order (drag/drop in wp-admin). */
function ascenzi_get_services($exclude_id = 0) {
    $query = new WP_Query([
        'post_type'      => 'service',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish',
        'post__not_in'   => $exclude_id ? [$exclude_id] : [],
    ]);
    return $query->posts;
}
