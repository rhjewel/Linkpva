<?php

use Egns\Helper\Egns_Helper;
?>
<div class="career-page-position-section mt-140 mb-140" id="position-section">
    <div class="container one">
        <div class="row g-4">
            <?php
            if (have_posts()) :
                while (have_posts()) {
                    the_post();
                    $job_location   = Egns_Helper::egns_career_option_value('job_location');
                    $job_type       = Egns_Helper::egns_career_option_value('job_type', '', array());
                    $job_experience = Egns_Helper::egns_career_option_value('job_experience');
                    $job_salary     = Egns_Helper::egns_career_option_value('job_salary');
                    $badges         = array();

                    if (!empty($job_location)) {
                        $badges[] = $job_location;
                    }

                    if (!empty($job_type)) {
                        $badges = array_merge($badges, is_array($job_type) ? $job_type : array($job_type));
                    }

                    $badges = array_filter(array_unique($badges));
            ?>
                    <div class="col-lg-4 col-md-6 fade_anim" data-delay=".2">
                        <div class="single-position">
                            <div class="title-area">
                                <h3><?php the_title(); ?></h3>
                                <?php if (!empty($badges)) : ?>
                                    <ul class="batch">
                                        <?php foreach ($badges as $badge) : ?>
                                            <li><?php echo esc_html($badge); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                            <ul class="info-area">
                                <?php if (!empty($job_experience)) : ?>
                                    <li>
                                        <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M4.5236 9.35783L6.0516 11.5708C6.99992 10.2009 11.2151 3.50919 13.6388 0.400391C11.1287 5.14183 8.94664 10.0882 6.98408 15.0588C6.70248 15.7717 5.69832 15.7839 5.40264 15.0767C4.46776 12.8415 3.46264 10.6202 2.36328 8.46199C3.15368 8.30391 3.99656 8.56743 4.52344 9.35783H4.5236Z">
                                            </path>
                                        </svg>
                                        <div class="content">
                                            <strong class="title"><span><?php echo esc_html__('Experience', 'linkpva'); ?></span> <span>:</span></strong>
                                            <strong><?php echo wp_kses_post($job_experience); ?></strong>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                <?php if (!empty($job_salary)) : ?>
                                    <li>
                                        <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M4.5236 9.35783L6.0516 11.5708C6.99992 10.2009 11.2151 3.50919 13.6388 0.400391C11.1287 5.14183 8.94664 10.0882 6.98408 15.0588C6.70248 15.7717 5.69832 15.7839 5.40264 15.0767C4.46776 12.8415 3.46264 10.6202 2.36328 8.46199C3.15368 8.30391 3.99656 8.56743 4.52344 9.35783H4.5236Z">
                                            </path>
                                        </svg>
                                        <div class="content">
                                            <strong class="title"><span><?php echo esc_html__('Salary', 'linkpva'); ?></span> <span>:</span></strong>
                                            <strong><?php echo wp_kses_post($job_salary); ?></strong>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            </ul>
                            <a href="<?php the_permalink(); ?>" class="primary-btn3 white-bg">
                                <span data-text="<?php echo esc_attr__('Open job position', 'linkpva'); ?>"><?php echo esc_html__('Open job position', 'linkpva'); ?></span>
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path d="M8.88883 5L2.22217 10L2.22217 0L8.88883 5Z"></path>
                                    </g>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php
                }
            else :
                ?>
                <div class="col-12">
                    <p><?php echo esc_html__('No job positions found.', 'linkpva'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        <?php
        // Get the total number of pages.
        $total_pages = Egns\Inc\Blog_Helper::get_total_pages();
        // Only paginate if there are multiple pages.
        if ($total_pages > 1) {
            $current_page = max(1, get_query_var('paged'));
        ?>
            <div class="row justify-content-center mt-70">
                <div class="inner-pagination-area fade_anim" data-delay=".2" data-ease="bounce">
                    <?php if ($current_page > 1): ?>
                        <div class="paginations-button">
                            <a href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>">
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path d="M2.22201 5L8.88867 10V0L2.22201 5Z" />
                                    </g>
                                </svg>
                                <?php echo esc_html__('Prev', 'linkpva') ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php
                    // Pagination
                    Egns\Inc\Blog_Helper::egns_pagination();
                    ?>
                    <?php if ($current_page < $total_pages): ?>
                        <div class="paginations-button">
                            <a href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>">
                                <?php echo esc_html__('Next', 'linkpva') ?>
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path d="M8.88932 5L2.22266 10L2.22266 0L8.88932 5Z" />
                                    </g>
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>