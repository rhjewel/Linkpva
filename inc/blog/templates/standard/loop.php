<section class="linkpva-inner-section" id="guides" data-blog-listing>
    <div class="container">
        <div class="row gy-5 justify-content-between">
            <div class="<?php echo esc_attr(is_active_sidebar('blog_sidebar') ? 'col-xl-7 col-lg-8' : 'col-lg-10'); ?>">
                <?php
                if (have_posts()) {
                    while (have_posts()) : the_post();
                        // Include blog standard
                        if (is_single()) {
                            if (Egns\Helper\Egns_Helper::egns_check_template_part('blog', 'templates/single/post/post', get_post_format())) {
                                echo apply_filters('egns_filter_blog_single_template', Egns\Helper\Egns_Helper::egns_get_template_part('blog', 'templates/single/post/post', get_post_format() ? get_post_format() : 'default'));
                            } else {
                                echo apply_filters('egns_filter_blog_single_template', Egns\Helper\Egns_Helper::egns_get_template_part('blog', 'templates/single/post/post', 'default'));
                            }
                        } else {
                            if (Egns\Helper\Egns_Helper::egns_check_template_part('blog', 'templates/single/post/post', get_post_format())) {
                                echo apply_filters('egns_filter_blog_single_template', Egns\Helper\Egns_Helper::egns_get_template_part('blog', 'templates/standard/post/post', get_post_format() ? get_post_format() : 'default'));
                            } else {
                                echo apply_filters('egns_filter_blog_single_template', Egns\Helper\Egns_Helper::egns_get_template_part('blog', 'templates/standard/post/post', 'default'));
                            }
                        }
                    endwhile; // End of the loop.
                } else {
                    // Include global posts not found
                    Egns\Helper\Egns_Helper::egns_template_part('content', 'templates/posts-not-found');
                }
                wp_reset_postdata();
                ?>

                <?php
                global $wp_query;

                $total_pages  = (int) $wp_query->max_num_pages;
                $current_page = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));

                if ($total_pages > 1) {
                    $pagination_links = paginate_links(array(
                        'current'   => $current_page,
                        'total'     => $total_pages,
                        'type'      => 'array',
                        'mid_size'  => 1,
                        'end_size'  => 1,
                        'prev_text' => '<i class="bi bi-arrow-left" role="img" aria-label="' . esc_attr__('Previous page', 'linkpva') . '"></i>',
                        'next_text' => '<i class="bi bi-arrow-right" role="img" aria-label="' . esc_attr__('Next page', 'linkpva') . '"></i>',
                    ));

                    if (!empty($pagination_links)) {
                ?>
                        <nav class="linkpva-pagination" aria-label="<?php echo esc_attr__('Blog pagination', 'linkpva'); ?>">
                            <?php
                            foreach ($pagination_links as $pagination_link) {
                                echo wp_kses_post($pagination_link);
                            }
                            ?>
                        </nav>
                <?php
                    }
                }
                ?>

            </div>
            <?php if (is_active_sidebar('blog_sidebar')): ?>
                <div class="col-lg-4 fade_anim" data-delay=".3" data-fade-from="right">
                    <div class="blog-sidebar-area">
                        <?php
                        // Include page content sidebar
                        Egns\Helper\Egns_Helper::egns_template_part('sidebar', 'templates/sidebar');
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>