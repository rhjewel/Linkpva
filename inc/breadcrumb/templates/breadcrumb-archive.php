<?php

use Egns\Helper\Egns_Helper;

$enable_breadcrumb_by_theme = Egns_Helper::egns_get_theme_option('breadcrumb_enable');
$breadcrumb_enable_by_page  = Egns_Helper::egns_page_option_value('breadcrumb_enable_page');
$breadcrumb_heading         = trim((string) Egns_Helper::egns_get_theme_option('breadcrumb_heading'));
$breadcrumb_short_desc      = trim((string) Egns_Helper::egns_get_theme_option('breadcrumb_short_desc'));
$archive_heading            = '';
$archive_short_desc         = '';
$default_heading            = '';
$default_short_desc         = '';
$archive_post_type          = '';
$queried_object             = get_queried_object();

if (is_home() || is_category() || is_tag() || is_author() || is_date() || is_page_template('page-blog-grid.php')) {
    $archive_post_type = 'post';
} elseif (is_post_type_archive()) {
    $archive_post_type = get_query_var('post_type');
    $archive_post_type = is_array($archive_post_type) ? reset($archive_post_type) : $archive_post_type;

    if (!$archive_post_type && $queried_object instanceof WP_Post_Type) {
        $archive_post_type = $queried_object->name;
    }
} elseif ($queried_object instanceof WP_Term) {
    $taxonomy = get_taxonomy($queried_object->taxonomy);

    if ($taxonomy && !empty($taxonomy->object_type)) {
        $archive_post_type = reset($taxonomy->object_type);
    }
}

$archive_option_keys = array(
    'post'       => array(
        'heading'     => 'breadcrumb_post_heading',
        'description' => 'breadcrumb_post_short_desc',
    ),
    'career'     => array(
        'heading'     => 'breadcrumb_cpt_career_heading',
        'description' => 'breadcrumb_cpt_career_short_desc',
    ),
    'product'     => array(
        'heading'     => 'breadcrumb_cpt_product_heading',
        'description' => 'breadcrumb_cpt_product_short_desc',
    ),
    'case-study' => array(
        'heading'     => 'breadcrumb_cpt_case_heading',
        'description' => 'breadcrumb_cpt_case_short_desc',
    ),
);

if (isset($archive_option_keys[$archive_post_type])) {
    $archive_heading    = trim((string) Egns_Helper::egns_get_theme_option($archive_option_keys[$archive_post_type]['heading']));
    $archive_short_desc = trim((string) Egns_Helper::egns_get_theme_option($archive_option_keys[$archive_post_type]['description']));
}

if ($queried_object instanceof WP_Term) {
    $default_heading    = $queried_object->name;
    $default_short_desc = term_description($queried_object->term_id, $queried_object->taxonomy);
} elseif (is_home()) {
    $blog_page_id       = absint(get_option('page_for_posts'));
    $default_heading    = $blog_page_id ? get_the_title($blog_page_id) : esc_html__('Blog', 'linkpva');
    $default_short_desc = $blog_page_id ? get_post_field('post_excerpt', $blog_page_id) : '';
} elseif (is_page_template('page-blog-grid.php')) {
    $default_heading    = get_the_title(get_queried_object_id());
    $default_short_desc = get_post_field('post_excerpt', get_queried_object_id());
} elseif (function_exists('is_shop') && is_shop() && function_exists('woocommerce_page_title')) {
    $shop_page_id       = function_exists('wc_get_page_id') ? wc_get_page_id('shop') : 0;
    $default_heading    = woocommerce_page_title(false);
    $default_short_desc = $shop_page_id > 0 ? get_post_field('post_excerpt', $shop_page_id) : '';
} elseif (is_post_type_archive()) {
    $post_type_object   = get_post_type_object($archive_post_type);
    $default_heading    = post_type_archive_title('', false);
    $default_short_desc = $post_type_object ? $post_type_object->description : '';

    if ('' === trim((string) $default_heading) && $post_type_object) {
        $default_heading = $post_type_object->labels->name;
    }
} elseif (is_author() && $queried_object instanceof WP_User) {
    $default_heading    = $queried_object->display_name;
    $default_short_desc = get_the_author_meta('description', $queried_object->ID);
} else {
    $default_heading    = get_the_archive_title();
    $default_short_desc = get_the_archive_description();
}

$breadcrumb_display_heading = '' !== $archive_heading ? $archive_heading : $breadcrumb_heading;
$breadcrumb_display_heading = '' !== $breadcrumb_display_heading ? $breadcrumb_display_heading : $default_heading;
$breadcrumb_display_desc    = '' !== $archive_short_desc ? $archive_short_desc : $breadcrumb_short_desc;
$breadcrumb_display_desc    = '' !== $breadcrumb_display_desc ? $breadcrumb_display_desc : $default_short_desc;
$breadcrumb_display_desc    = trim(wp_strip_all_tags((string) $breadcrumb_display_desc));

?>

<?php if (Egns\Helper\Egns_Helper::is_enabled($enable_breadcrumb_by_theme, $breadcrumb_enable_by_page)): ?>
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