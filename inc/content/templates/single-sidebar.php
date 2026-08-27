<div class="blog-details-page">
    <?php
    // Include blog template
    echo apply_filters('egns_filter_blog_template', Egns\Helper\Egns_Helper::egns_get_template_part('blog', 'templates/single/loop', '', ''));
    ?>
</div>