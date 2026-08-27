<?php

use Egns\Helper\Egns_Helper;

$tags = get_the_tags();

?>

<div class="container one">
    <div class="row gy-5 <?php echo esc_attr(is_active_sidebar('blog_dt_sidebar') ? 'justify-content-between' : 'justify-content-center'); ?>">
        <div class="<?php echo esc_attr(is_active_sidebar('blog_dt_sidebar') ? 'col-xl-7 col-lg-8 order-lg-1 order-2' : 'col-xl-10'); ?>">
            <div class="blog-details-content-wrap">
                <div class="blog-author-meta">
                    <div class="author-img">
                        <?php echo get_avatar(get_the_author_meta('ID')); ?>
                    </div>
                    <div class="author-info">
                        <strong class="author-name"><?php echo get_the_author_meta('display_name') ?></strong>
                        <div class="meta">
                            <span class="date"><?php echo esc_html__('Last Update : ', 'linkpva') . get_the_date('d F, Y') ?></span>
                            <span>
                                <?php
                                /**
                                 * @param mixed $content
                                 */
                                $content = get_the_content();
                                Egns_Helper::calculate_reading_time($content);
                                echo esc_html__(' minutes read', 'linkpva');
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <?php the_content() ?>

                <?php if (class_exists('Egns_Core') || !empty($tags)) : ?>
                    <div class="tag-and-social-area">
                        <?php if (!empty($tags)) : ?>
                            <h3 class="tag-title"><?php echo esc_html__('Tags:', 'linkpva') ?></h3>
                            <span class="line-break"></span>
                            <span class="line-break"></span>
                            <ul class="tag-list">
                                <?php foreach ($tags as $index => $tag) { ?>
                                    <li><a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"><?php echo esc_html($tag->name); ?></a></li>
                                <?php } ?>
                            </ul>
                        <?php endif; ?>
                        <ul class="social-list d-lg-none d-flex">
                            <li>
                                <a href="<?php echo esc_url('http://www.facebook.com/sharer/sharer.php?u=' . get_permalink()); ?>">
                                    <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.19709 15V8.62433H11.5664L11.9186 6.12805H9.19709V4.53802C9.19709 3.81769 9.41817 3.3245 10.557 3.3245H12V1.09892C11.2979 1.03062 10.5922 0.997637 9.88603 1.00013C7.79177 1.00013 6.3539 2.16076 6.3539 4.29143V6.12338H4V8.61966H6.35904V15H9.19709Z" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url('http://www.x.com/share?url=' . get_permalink()); ?>">
                                    <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12.025 2H14.1722L9.48225 7.08303L15 14H10.6801L7.2965 9.80414L3.42462 14H1.2765L6.29287 8.56276L1 2H5.43013L8.48825 5.83421L12.025 2ZM11.2725 12.7818H12.4625L4.78262 3.15448H3.50688L11.2725 12.7818Z" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url('http://www.instagram.com/share?url=' . get_permalink()); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                        <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url('https://www.linkedin.com/sharing/share-offsite/?url=' . get_permalink()); ?>">
                                    <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3.32195 4.79697C4.12755 4.79697 4.78061 4.1439 4.78061 3.33831C4.78061 2.53271 4.12755 1.87964 3.32195 1.87964C2.51635 1.87964 1.86328 2.53271 1.86328 3.33831C1.86328 4.1439 2.51635 4.79697 3.32195 4.79697Z" />
                                        <path
                                            d="M6.15717 5.90312V13.9958H8.66983V9.99379C8.66983 8.93779 8.8685 7.91512 10.1778 7.91512C11.4692 7.91512 11.4852 9.12246 11.4852 10.0605V13.9965H13.9992V9.55846C13.9992 7.37846 13.5298 5.70312 10.9818 5.70312C9.7585 5.70312 8.9385 6.37446 8.60317 7.00979H8.56917V5.90312H6.15717ZM2.0625 5.90312H4.57917V13.9958H2.0625V5.90312Z" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="copy-link-btn">
                                    <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path
                                                d="M4.96206 15.9944C6.24478 15.9944 7.50497 15.5218 8.47261 14.5541L9.8003 13.2265C10.0703 12.9564 10.0703 12.5288 9.8003 12.2813C9.53027 12.0113 9.10268 12.0113 8.85514 12.2813L7.52741 13.609C6.1097 15.0042 3.81433 15.0042 2.39662 13.609C1.0014 12.1913 1.0014 9.89591 2.39662 8.4782L3.72431 7.15052C3.99434 6.88049 3.99434 6.4529 3.72431 6.20536C3.45429 5.93534 3.0267 5.93534 2.77916 6.20536L1.45147 7.53305C-0.48385 9.46837 -0.48385 12.6188 1.45147 14.5541C2.41915 15.5218 3.67934 15.9944 4.96206 15.9944ZM12.7483 10.0084C12.9283 10.0084 13.0858 9.94092 13.2208 9.80589L14.5485 8.4782C16.4838 6.54288 16.4838 3.39243 14.5485 1.45711C12.6132 -0.478215 9.46273 -0.478215 7.52741 1.45711L6.19968 2.78479C5.92966 3.05482 5.92966 3.48241 6.19968 3.72995C6.46971 3.99997 6.89729 3.99997 7.14484 3.72995L8.47252 2.40226C9.89023 1.00703 12.1856 1.00703 13.6033 2.40226C14.9985 3.81997 14.9985 6.11534 13.6033 7.53305L12.2756 8.86078C12.0056 9.1308 12.0056 9.55839 12.2756 9.80593C12.4107 9.94092 12.5683 10.0084 12.7483 10.0084Z" />
                                            <path
                                                d="M6.28916 10.391C6.46921 10.391 6.62673 10.3235 6.76172 10.1884L10.1822 6.76791C10.4523 6.49789 10.4523 6.0703 10.1822 5.82276C9.91222 5.55273 9.48463 5.55273 9.23709 5.82276L5.81657 9.24328C5.54654 9.51331 5.54654 9.9409 5.81657 10.1884C5.95164 10.3234 6.10916 10.391 6.28916 10.391Z" />
                                        </g>
                                    </svg>
                                    <span class="copy-alert"><?php echo esc_html__('Copied!', 'linkpva') ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if (is_active_sidebar('blog_dt_sidebar')): ?>
            <div class="col-lg-4 order-lg-2 order-1">
                <div class="blog-details-sidebar">
                    <?php dynamic_sidebar('blog_dt_sidebar')  ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="row <?php echo esc_attr(is_active_sidebar('blog_dt_sidebar') ? 'justify-content-between' : 'justify-content-center'); ?>">
        <div class="<?php echo esc_attr(is_active_sidebar('blog_dt_sidebar') ? 'col-xl-7 col-lg-8' : 'col-xl-10'); ?>">
            <?php if (comments_open() || get_comments_number()) : ?>
                <div class="comment-and-form-area mt-140">
                    <?php
                    //If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php Egns_Helper::display_related_posts_by_category(get_the_ID()) ?>