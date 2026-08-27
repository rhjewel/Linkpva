<div class="blog-thumb-img mb-70">
    <?php
    if (Egns\Inc\Blog_Helper::has_egns_image()) {
        echo Egns\Inc\Blog_Helper::egns_thumb_image();
    } else {
    ?>
        <a href="<?php the_permalink() ?>">
            <?php the_post_thumbnail() ?>
        </a>
    <?php
    }
    ?>
</div>