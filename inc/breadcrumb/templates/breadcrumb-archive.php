<?php

use Egns\Helper\Egns_Helper;

$enable_breadcrumb_by_theme = Egns_Helper::egns_get_theme_option('breadcrumb_enable');
$breadcrumb_enable_by_page  = Egns_Helper::egns_page_option_value('breadcrumb_enable_page');

// Default heading 
$breadcrumb_heading         = Egns_Helper::egns_get_theme_option('breadcrumb_heading') ?? '';
$breadcrumb_page_heading    = Egns_Helper::egns_page_option_value('breadcrumb_page_heading') ?? '';

// Final default values
$breadcrumb_display_heading = $breadcrumb_page_heading !== '' ? $breadcrumb_page_heading : $breadcrumb_heading;

$is_woocommerce_archive  = function_exists('is_shop') && (is_shop() || is_post_type_archive('product'));
$is_woocommerce_taxonomy = function_exists('is_product_taxonomy') && is_product_taxonomy();


/**
 * -------------------------------------------------------
 * Custom Post Type Archive + Taxonomy Override Logic
 * -------------------------------------------------------
 */

if ($is_woocommerce_archive && function_exists('woocommerce_page_title')) {
    $breadcrumb_display_heading = woocommerce_page_title(false);
}

?>

<?php if (Egns\Helper\Egns_Helper::is_enabled($enable_breadcrumb_by_theme, $breadcrumb_enable_by_page)): ?>
    <section class="linkpva-page-hero">
        <div class="container">
            <?php echo egns_breadcrumb(); ?>
            <h1>
                <?php

                $term = get_queried_object();

                if ($is_woocommerce_taxonomy && $term instanceof WP_Term) {
                    if (is_tax('product_cat')) {
                        echo esc_html__('Category: ', 'linkpva') . esc_html($term->name);
                    } elseif (is_tax('product_tag')) {
                        echo esc_html__('Tag: ', 'linkpva') . esc_html($term->name);
                    } else {
                        $taxonomy = get_taxonomy($term->taxonomy);
                        $taxonomy_label = $taxonomy && !empty($taxonomy->labels->singular_name) ? $taxonomy->labels->singular_name : $term->taxonomy;

                        echo esc_html($taxonomy_label . ': ') . esc_html($term->name);
                    }
                } elseif (is_tax() && $term) {
                    if (strpos($term->taxonomy, 'category') !== false) {
                        echo esc_html__('Category: ', 'linkpva') . esc_html($term->name);
                    } elseif (strpos($term->taxonomy, 'tag') !== false) {
                        echo esc_html__('Tag: ', 'linkpva') . esc_html($term->name);
                    }
                } else {

                    // ----- CPT or Page Heading -----
                    if (!empty($breadcrumb_display_heading)) {
                        echo wp_kses_post($breadcrumb_display_heading);
                    } else {
                        // fallback: default WP logic
                        if (is_category()) {
                            echo esc_html__('Category: ', 'linkpva');
                            single_cat_title();
                        } elseif (is_tag()) {
                            echo esc_html__('Tag: ', 'linkpva');
                            single_tag_title();
                        } elseif (is_author()) {
                            echo esc_html__('Author: ', 'linkpva');
                            the_author();
                        } elseif (is_date()) {
                            echo esc_html__('Date: ', 'linkpva');
                            if (is_day()) echo get_the_time('F j, Y');
                            elseif (is_month()) echo get_the_time('F, Y');
                            elseif (is_year()) echo get_the_time('Y');
                        } elseif (is_home()) {
                            Egns\Helper\Egns_Helper::egns_translate('Blog');
                        } else {
                            the_title();
                        }
                    }
                }

                ?>
            </h1>
            <p>Explore buyer guides, account-type explanations, ordering information, and responsible-use resources.</p>
        </div>
    </section>
<?php endif; ?>