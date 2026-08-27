<div class="blog-meta">
    <ul>
        <li><a class="blog-date" href="<?php echo esc_url(home_url(get_the_date('Y/m/d'))) ?>"><?php echo get_the_date('d F, Y'); ?></a></li>
        <?php $comment_count = get_comments_number(); ?>
        <li><span><?php echo esc_html($comment_count . ' ' . _n('comment', 'comments', $comment_count, 'linkpva')); ?></span></li>

    </ul>
</div>