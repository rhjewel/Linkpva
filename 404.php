<?php

/**
 * The template for displaying 404 pages (not found)
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 * @package linkpva
 */

get_header();

?>

<div class="error-page-wrapper">
    <?php if (class_exists('Egns_Core')) : ?>
        <?php $button_text = Egns\Helper\Egns_Helper::egns_get_theme_option('404_button_text'); ?>
        <div class="error-page">
            <?php if (!empty(Egns\Helper\Egns_Helper::egns_get_theme_option('404_bg_video'))) : ?>
                <div class="video-area">
                    <video autoplay loop muted playsinline src="<?php echo esc_url(Egns\Helper\Egns_Helper::egns_get_theme_option('404_bg_video')) ?>"></video>
                </div>
            <?php endif; ?>
            <div class="error-content-wrap">
                <div class="container one">
                    <div class="error-content">
                        <?php if (!empty(Egns\Helper\Egns_Helper::egns_get_theme_option('404_image', 'url'))) : ?>
                            <img src="<?php echo esc_url(Egns\Helper\Egns_Helper::egns_get_theme_option('404_image', 'url')) ?>" alt="<?php echo esc_attr__('error image', 'linkpva') ?>">
                        <?php endif; ?>
                        <?php if (!empty(Egns\Helper\Egns_Helper::egns_get_theme_option('404_title'))) : ?>
                            <h2><?php echo wp_kses(Egns\Helper\Egns_Helper::egns_get_theme_option('404_title'), wp_kses_allowed_html('post')) ?></h2>
                        <?php endif; ?>
                        <?php if (!empty(Egns\Helper\Egns_Helper::egns_get_theme_option('404_content'))) : ?>
                            <p><?php echo wp_kses(Egns\Helper\Egns_Helper::egns_get_theme_option('404_content'), wp_kses_allowed_html('post')) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($button_text)) : ?>
                            <div class="btn-area">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="primary-btn3 two">
                                    <span data-text="<?php echo esc_attr($button_text); ?>"><?php echo esc_html($button_text); ?></span>
                                    <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M8.88883 5L2.22217 10L2.22217 0L8.88883 5Z"></path>
                                        </g>
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="error-page">
            <div class="video-area">
                <video autoplay loop muted playsinline src="<?php echo esc_url(get_template_directory_uri() . '/assets/video/error-page-video.mp4'); ?>"></video>
            </div>
            <div class="error-content-wrap">
                <div class="container one">
                    <div class="error-content">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/innerpages/error-img.png'); ?>" alt="">
                        <h2><?php echo esc_html__('Something Went Missing', 'linkpva') ?></h2>
                        <p><?php echo esc_html__('Oops! The page you’re trying to reach can’t be found or may have been moved. Don’t worry — you can continue exploring.', 'linkpva') ?></p>
                        <div class="btn-area">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="primary-btn3 two">
                                <span data-text="<?php echo esc_attr__('Go to homepage', 'linkpva') ?>"><?php echo esc_html__('Go to homepage', 'linkpva') ?></span>
                                <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path d="M8.88883 5L2.22217 10L2.22217 0L8.88883 5Z"></path>
                                    </g>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<?php
get_footer();
