<div class="blog-content">
    <?php Egns\Helper\Egns_Helper::egns_template_part('blog', 'templates/common/standard/meta'); ?>
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