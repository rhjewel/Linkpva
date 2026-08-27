<?php
/**
 * The main template file
 *
 * Template Name: Blog Grid Template
 *
 * @link https:   //developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package linkpva
 * @since 1.0.0
 * 
 */

get_header();

if (!is_front_page()):
    // Include breadcrumb template
    Egns\Helper\Egns_Helper::egns_template_part('breadcrumb', 'templates/breadcrumb-archive');
endif;

?>

<div class="home1-blog-section mb-120">
    <div class="container">
        <div class="row gy-5 justify-content-center">
            <div class="<?php echo esc_attr(is_active_sidebar('blog_sidebar') ? 'col-lg-8' : 'col-lg-10'); ?>">
                <div class="row gy-5 gx-xl-4 gx-3 mb-100">
                    <?php
                    $args = array(
                        'post_type'      => 'post',
                        'order'          => 'DESC',
                        'post_status'    => 'publish',
                        'posts_per_page' => 6,
                        'paged'          => (get_query_var('paged')) ? get_query_var('paged') : 1
                    );
                    $blog_query = new WP_Query($args);

                    if ($blog_query->have_posts()) {
                        while ($blog_query->have_posts()) {
                            $blog_query->the_post();
                            echo apply_filters('egns_filter_blog_single_template', Egns\Helper\Egns_Helper::egns_get_template_part('blog', 'templates/grid/post/post', get_post_format() ? get_post_format() : 'default'));
                        }; // End of the loop.
                        wp_reset_postdata();
                    } else {
                        // Include global posts not found
                        Egns\Helper\Egns_Helper::egns_template_part('content', 'templates/posts-not-found');
                    }
                    ?>
                </div>
                <?php
                // Get the total number of pages.
                $total_pages = Egns\Inc\Blog_Helper::get_total_pages($blog_query);
                // Only paginate if there are multiple pages.
                if ($total_pages > 1) {
                    $current_page = max(1, get_query_var('paged'));
                ?>
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="pagination-area">
                                <?php if ($current_page > 1): ?>
                                    <div class="paginations-button">
                                        <a href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>">
                                            <svg width="7" height="14" viewBox="0 0 7 14"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0 7.00008L7 0L2.54545 7.00008L7 14L0 7.00008Z" />
                                            </svg>
                                            <?php echo esc_html__('Prev', 'linkpva') ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <?php
                                // Pagination
                                 Egns\Inc\Blog_Helper::egns_pagination($blog_query);
                                ?>
                                <?php if ($current_page < $total_pages): ?>
                                    <div class="paginations-button">
                                        <a href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>">
                                            <?php echo esc_html__('Next', 'linkpva') ?>
                                            <svg width="7" height="14" viewBox="0 0 7 14"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7 7.00008L0 0L4.45455 7.00008L0 14L7 7.00008Z" />
                                            </svg>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
            <?php if (is_active_sidebar('blog_sidebar')): ?>
                <div class="col-lg-4">
                    <div class="news-article-sidebar-area">
                        <?php
                        // Include page content sidebar
                        Egns\Helper\Egns_Helper::egns_template_part('sidebar', 'templates/sidebar');
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php get_footer(); ?>
