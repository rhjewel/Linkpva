<div class="post-image">
    <?php
    if (Egns\Inc\Blog_Helper::has_egns_image()) {
        echo Egns\Inc\Blog_Helper::egns_thumb_image();
    } else {
    ?>
        <a class="blog-img shape-hover-item" href="<?php the_permalink() ?>">
            <div class="shape-hover-img" data-displacement="<?php echo esc_url(get_template_directory_uri() . '/assets/img/home3/hover-img-shape2.png') ?>" data-intensity="0.6" data-speedin="1" data-speedout="1">
                <?php the_post_thumbnail('blog-grid') ?>
            </div>
        </a>
    <?php
    }
    ?>
</div>