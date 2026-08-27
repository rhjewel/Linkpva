<?php

$people_query = $GLOBALS['wp_query'];
$current_page = max(1, get_query_var('paged'));
$shown_count  = $people_query->post_count;
$total_count  = $people_query->found_posts;
?>

<div class="people-page mt-140 mb-140" data-people-archive data-page="<?php echo esc_attr($current_page); ?>" data-max-pages="<?php echo esc_attr($people_query->max_num_pages); ?>">
    <div class="container one">
        <div class="filter-top-bar fade_anim" data-delay=".2" data-fade-from="top">
            <div class="team-result-count">
                <span><?php echo esc_html(sprintf(__('Showing %1$s items of %2$s', 'linkpva'), $shown_count, $total_count)); ?></span>
            </div>
            <div class="team-search-area">
                <input type="text" placeholder="<?php echo esc_attr__('Search by name', 'linkpva'); ?>" class="team-search-input">
                <button class="team-reset-btn"><?php echo esc_html__('Reset', 'linkpva'); ?></button>
            </div>
        </div>
        <div class="no-results" <?php echo have_posts() ? 'style="display:none;"' : ''; ?>><?php echo esc_html__('No results found', 'linkpva'); ?></div>
        <div class="row gx-xl-4 gx-md-3 gx-4 gy-5 mb-70 fade_anim people-team-row" data-delay=".2">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    aventis_render_people_archive_card();
                endwhile;
            endif;
            ?>
        </div>
        <div class="load-more-btn fade_anim" data-delay=".2" data-ease="bounce" <?php echo esc_attr($people_query->max_num_pages > $current_page ? '' : 'style="display:none;"'); ?>>
            <a href="#" class="primary-btn1 transparent animate-btn" data-text="<?php echo esc_attr__('Load more', 'linkpva'); ?>">
                <?php echo esc_html__('Load more', 'linkpva'); ?>
            </a>
        </div>
    </div>
</div>