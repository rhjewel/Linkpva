<?php

use Egns\Helper\Egns_Helper;

$queried_object_id          = get_queried_object_id();
$page_options               = get_post_meta($queried_object_id, 'egns_page_options', true);
$page_options               = is_array($page_options) ? $page_options : array();
$enable_breadcrumb_by_theme = Egns_Helper::egns_get_theme_option('breadcrumb_enable');
$breadcrumb_enable_by_page  = $page_options['breadcrumb_enable_page'] ?? '';
$breadcrumb_page_heading    = trim((string) ($page_options['breadcrumb_page_heading'] ?? ''));
$breadcrumb_page_short_desc = trim((string) ($page_options['breadcrumb_page_short_desc'] ?? ''));
$breadcrumb_heading         = trim((string) Egns_Helper::egns_get_theme_option('breadcrumb_heading'));
$breadcrumb_short_desc      = trim((string) Egns_Helper::egns_get_theme_option('breadcrumb_short_desc'));
$default_heading            = trim((string) get_the_title($queried_object_id));
$default_short_desc         = trim((string) get_post_field('post_excerpt', $queried_object_id));
$breadcrumb_display_heading = '' !== $breadcrumb_page_heading ? $breadcrumb_page_heading : $breadcrumb_heading;
$breadcrumb_display_heading = '' !== $breadcrumb_display_heading ? $breadcrumb_display_heading : $default_heading;
$breadcrumb_display_desc    = '' !== $breadcrumb_page_short_desc ? $breadcrumb_page_short_desc : $breadcrumb_short_desc;
$breadcrumb_display_desc    = '' !== $breadcrumb_display_desc ? $breadcrumb_display_desc : $default_short_desc;
$breadcrumb_display_desc    = trim(wp_strip_all_tags($breadcrumb_display_desc));

?>
<?php if (Egns_Helper::is_enabled($enable_breadcrumb_by_theme, $breadcrumb_enable_by_page)): ?>
    <section class="linkpva-page-hero">
        <div class="container">
            <?php echo egns_breadcrumb(); ?>
            <h1><?php echo esc_html($breadcrumb_display_heading); ?></h1>
            <?php if ($breadcrumb_display_desc): ?>
                <p><?php echo esc_html($breadcrumb_display_desc); ?></p>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>