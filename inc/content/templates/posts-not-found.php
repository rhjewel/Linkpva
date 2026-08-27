<div class="post-not-found text-center">
    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/not-found.png') ?>" alt="<?php echo esc_attr__('Image', 'linkpva') ?>">
    <div class="inner-cnt mb-30">
        <h3> <?php echo esc_html__('No results found!', 'linkpva'); ?> </h3>
        <p><?php echo esc_html__('We couldn’t find that search terms. Please try again with some different keywords.', 'linkpva'); ?></p>
    </div>
    <?php get_search_form(); ?>
</div>