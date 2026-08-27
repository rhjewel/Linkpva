<div class="blog-content">
    <div class="blog-meta">
        <ul>
            <li><a class="blog-date" href="<?php echo esc_url(home_url(get_the_date('Y/m/d'))) ?>"><?php echo get_the_date('d F, Y'); ?></a></li>
            <?php $comment_count = get_comments_number(); ?>
            <li><span><?php echo esc_html($comment_count . ' ' . _n('comment', 'comments', $comment_count, 'linkpva')); ?></span></li>
        </ul>
    </div>
    <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
    <a href="<?php the_permalink(); ?>" class="read-btn">
        <span><?php echo esc_html__('Read More', 'linkpva') ?></span>
        <svg width="15" height="15" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg">
            <g>
                <path
                    d="M7.23241 0.232893L14.3936 7.39408L13.053 8.73466L1.35388 8.74787L1.3406 6.05345L9.28207 6.0926L5.2921 2.17319L7.23241 0.232893Z" />
                <path d="M7.19784 14.5909L11.7135 10.0753L7.88453 10.0564L5.27394 12.667L7.19784 14.5909Z" />
            </g>
        </svg>
    </a>
</div>