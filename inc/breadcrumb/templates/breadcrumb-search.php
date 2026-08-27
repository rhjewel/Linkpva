<?php

use Egns\Inc\Header_Helper;
use Egns\Helper\Egns_Helper;

$enable_breadcrumb_by_theme = Egns_Helper::egns_get_theme_option('breadcrumb_enable');
$breadcrumb_enable_by_page  = Egns_Helper::egns_page_option_value('breadcrumb_enable_page');

// Heading and short description: page value has priority over theme option
$breadcrumb_heading         = Egns_Helper::egns_get_theme_option('breadcrumb_heading') ?? '';
$breadcrumb_page_heading    = Egns_Helper::egns_page_option_value('breadcrumb_page_heading') ?? '';

// Determine final values: page option first, then theme option
$breadcrumb_display_heading = $breadcrumb_page_heading !== '' ? $breadcrumb_page_heading : $breadcrumb_heading;

$term = get_queried_object();

?>
<?php if (Egns\Helper\Egns_Helper::is_enabled($enable_breadcrumb_by_theme, $breadcrumb_enable_by_page)): ?>
    <section class="linkpva-page-hero">
        <div class="container">
            <?php echo egns_breadcrumb(); ?>
            <h1>
                <?php printf(esc_html__('Exploring For: %s', 'linkpva'), esc_html(get_search_query())); ?>
            </h1>
            <p>Explore buyer guides, account-type explanations, ordering information, and responsible-use resources.
            </p>
        </div>
    </section>
<?php endif; ?>