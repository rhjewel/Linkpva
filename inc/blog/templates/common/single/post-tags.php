<?php
$post_tags = get_the_tags(); // Retrieve the tags for the current post
?>
<?php if (!empty($post_tags)) : ?>
    <div class="tag-and-social-area">
        <ul class="tag-list">
            <?php
            $tags = get_the_tags();
            if ($tags) {
                foreach ($tags as $tag) { ?>
                    <li><a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"><span># </span><?php echo esc_html($tag->name); ?></a></li>

            <?php
                }
            }
            ?>
        </ul>
        <?php if (class_exists('Egns_Core')) : ?>
            <div class="social-area">
                <h6><?php echo esc_html__('Share On:', 'linkpva'); ?></h6>
                <ul class="social-link">
                    <li><a href="<?php echo esc_url('http://www.facebook.com/sharer/sharer.php?u=' . get_permalink()); ?>"><i class="bx bxl-facebook"></i></a></li>
                    <li><a href="<?php echo esc_url('http://www.twitter.com/share?url=' . get_permalink()); ?>"><i class="bi bi-twitter-x"></i></a></li>
                    <li><a href="<?php echo esc_url('http://www.pinterest.com/share?url=' . get_permalink()); ?>"><i class="bx bxl-pinterest-alt"></i></a></li>
                    <li><a href="<?php echo esc_url('http://www.instagram.com/share?url=' . get_permalink()); ?>"><i class="bx bxl-instagram"></i></a></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>