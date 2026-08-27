<?php

use Egns\Inc\Blog_Helper;

?>
<?php if (has_post_thumbnail()) : ?>
    <a href="<?php the_permalink() ?>" class="blog-img shape-hover-item">
        <div class="shape-hover-img" data-displacement="<?php echo esc_url(get_template_directory_uri() . '/assets/img/home3/hover-img-shape2.png') ?>" data-intensity="0.6" data-speedin="1" data-speedout="1">
            <?php the_post_thumbnail() ?>
        </div>
    </a>
<?php endif; ?>