<?php

if (have_posts()) {
    while (have_posts()) {
        the_post();
        if (Egns\Helper\Egns_Helper::egns_check_template_part('blog', 'templates/single/post/post', get_post_format())) {

            echo apply_filters('egns_filter_blog_single_template', Egns\Helper\Egns_Helper::egns_get_template_part('blog', 'templates/single/post/post', get_post_format() ? get_post_format() : 'default'));
        } else {
            echo apply_filters('egns_filter_blog_single_template', Egns\Helper\Egns_Helper::egns_get_template_part('blog', 'templates/single/post/post', 'default'));
        }
    }; // End of the loop.
} else {
    // Include global posts not found
    Egns\Helper\Egns_Helper::egns_template_part('content', 'templates/posts-not-found');
}

wp_reset_postdata();
