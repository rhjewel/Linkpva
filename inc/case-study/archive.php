<?php

use Egns\Helper\Egns_Helper;

/**
 * The template for displaying case study archive pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package linkpva
 */

$show_top_filter = Egns_Helper::egns_get_theme_option('top_filter_casestudy_archive', true);
$show_top_filter = !in_array($show_top_filter, array(false, 'false', '0', 0, 'off', 'no'), true);
$archive_title   = Egns_Helper::egns_get_theme_option('top_filter_casestudy_archive_title');
$terms = get_terms(array(
    'taxonomy'   => 'case-study-category',
    'hide_empty' => false,
));
$queried_object = get_queried_object();
$active_term_id = $queried_object instanceof WP_Term && 'case-study-category' === $queried_object->taxonomy ? absint($queried_object->term_id) : 0;

if (!function_exists('aventis_case_study_archive_metric')) {
    function aventis_case_study_archive_metric($post_id)
    {
        $meta    = get_post_meta($post_id, 'EGNS_CASESTUDY_META_ID', true);
        $metrics = !empty($meta['case_study_metrics_list']) && is_array($meta['case_study_metrics_list']) ? $meta['case_study_metrics_list'] : array();
        $metric  = !empty($metrics[0]) && is_array($metrics[0]) ? $metrics[0] : array();
        $value   = trim(($metric['case_study_metrics_list_value'] ?? '') . ($metric['case_study_metrics_list_suffix'] ?? ''));

        return array(
            'value' => $value,
            'label' => $metric['case_study_metrics_list_label'] ?? '',
        );
    }
}

if (!function_exists('aventis_case_study_archive_arrow_icon')) {
    function aventis_case_study_archive_arrow_icon()
    {
?>
        <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
            <g>
                <path d="M8.88883 5L2.22217 10L2.22217 0L8.88883 5Z"></path>
            </g>
        </svg>
<?php
    }
}
?>

<div class="case-study-page mt-140 mb-140">
    <div class="container one">
        <?php if ($show_top_filter) : ?>
            <div class="row justify-content-center mb-70">
                <div class="col-lg-8">
                    <?php if (!empty($archive_title)) : ?>
                        <div class="section-title text-center fade_anim" data-delay=".2" data-fade-from="top">
                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 0H9.23077L0 20H10.7692L20 0Z" />
                            </svg>
                            <h2><?php echo wp_kses_post($archive_title); ?></h2>
                        </div>
                    <?php endif; ?>

                    <div class="filter-area">
                        <div class="filter-wrapper">
                            <span><?php echo esc_html__('filter by-', 'linkpva'); ?></span>
                            <select class="case-study-category-filter" onchange="if(this.value){window.location.href=this.value;}">
                                <option value="<?php echo esc_url(get_post_type_archive_link('case-study')); ?>" <?php selected(0, $active_term_id); ?>>
                                    <?php echo esc_html__('All Case Studies', 'linkpva'); ?>
                                </option>
                                <?php if (!empty($terms) && !is_wp_error($terms)) : ?>
                                    <?php foreach ($terms as $term) : ?>
                                        <option value="<?php echo esc_url(get_term_link($term)); ?>" <?php selected($active_term_id, absint($term->term_id)); ?>>
                                            <?php echo esc_html($term->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (have_posts()) : ?>
            <div class="row gy-70">
                <?php
                $delay = 2;
                while (have_posts()) :
                    the_post();
                    $post_id   = get_the_ID();
                    $permalink = get_permalink($post_id);
                    $title     = get_the_title($post_id);
                    $image     = has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'full') : includes_url('images/media/default.png');
                    $excerpt   = has_excerpt($post_id) ? get_the_excerpt($post_id) : wp_strip_all_tags(get_the_content(null, false, $post_id));
                    $metric    = aventis_case_study_archive_metric($post_id);
                ?>
                    <div class="col-lg-4 col-md-6 fade_anim" data-delay=".<?php echo esc_attr($delay); ?>">
                        <div class="case-study-card">
                            <a href="<?php echo esc_url($permalink); ?>" class="case-study-img">
                                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
                            </a>
                            <div class="case-study-content">
                                <h3><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h3>
                                <?php if (!empty($excerpt)) : ?>
                                    <p><?php echo esc_html(wp_trim_words($excerpt, 18, '...')); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($metric['value']) || !empty($metric['label'])) : ?>
                                    <div class="revenue-area">
                                        <?php if (!empty($metric['value'])) : ?>
                                            <h2><?php echo esc_html($metric['value']); ?></h2>
                                        <?php endif; ?>
                                        <?php if (!empty($metric['label'])) : ?>
                                            <span><?php echo esc_html($metric['label']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <a href="<?php echo esc_url($permalink); ?>" class="primary-btn3">
                                    <span data-text="<?php echo esc_attr__('Read more', 'linkpva'); ?>"><?php echo esc_html__('Read more', 'linkpva'); ?></span>
                                    <?php aventis_case_study_archive_arrow_icon(); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php
                    $delay = $delay >= 4 ? 2 : $delay + 1;
                endwhile;
                ?>
            </div>

            <?php
            $total_pages = Egns\Inc\Blog_Helper::get_total_pages();

            if ($total_pages > 1) :
                $current_page = max(1, get_query_var('paged'));
            ?>
                <div class="row justify-content-center mt-70">
                    <div class="inner-pagination-area fade_anim" data-delay=".2" data-ease="bounce">
                        <?php if ($current_page > 1) : ?>
                            <div class="paginations-button">
                                <a href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>">
                                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M2.22201 5L8.88867 10V0L2.22201 5Z" />
                                        </g>
                                    </svg>
                                    <?php echo esc_html__('Prev', 'linkpva'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php Egns\Inc\Blog_Helper::egns_pagination(); ?>

                        <?php if ($current_page < $total_pages) : ?>
                            <div class="paginations-button">
                                <a href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>">
                                    <?php echo esc_html__('Next', 'linkpva'); ?>
                                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M8.88932 5L2.22266 10L2.22266 0L8.88932 5Z" />
                                        </g>
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="row">
                <div class="col-12">
                    <div class="post-not-found text-center">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/not-found.png'); ?>" alt="<?php echo esc_attr__('Image', 'linkpva'); ?>">
                        <div class="inner-cnt mb-30">
                            <h3><?php echo esc_html__('Sorry Nothing Found!', 'linkpva'); ?></h3>
                            <p><?php echo esc_html__('Nothing Match your search terms. Please try again with some different keywords.', 'linkpva'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
