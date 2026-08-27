<section class="linkpva-inner-section" id="guides" data-blog-listing>
    <div class="container">
        <div class="row g-4" data-blog-grid>
            <?php
            if (have_posts()) {
                while (have_posts()) {
                    the_post();

                    $post_format = get_post_format() ?: 'default';

                    if (Egns\Helper\Egns_Helper::egns_check_template_part('blog', 'templates/grid/post/post', $post_format)) {
                        echo apply_filters('egns_filter_blog_single_template', Egns\Helper\Egns_Helper::egns_get_template_part('blog', 'templates/grid/post/post', $post_format));
                    } else {
                        echo apply_filters('egns_filter_blog_single_template', Egns\Helper\Egns_Helper::egns_get_template_part('blog', 'templates/grid/post/post', 'default'));
                    }
                }
            } else {
                Egns\Helper\Egns_Helper::egns_template_part('content', 'templates/posts-not-found');
            }
            ?>
        </div>

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
</section>