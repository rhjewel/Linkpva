<?php

use Egns\Helper\Egns_Helper;

$enable_breadcrumb_by_theme = Egns_Helper::egns_get_theme_option('breadcrumb_enable');
$breadcrumb_enable_by_page  = Egns_Helper::egns_page_option_value('breadcrumb_enable_page');

// Heading and short description: page value has priority over theme option
$breadcrumb_heading         = Egns_Helper::egns_get_theme_option('breadcrumb_heading') ?? '';
$breadcrumb_page_heading    = Egns_Helper::egns_page_option_value('breadcrumb_page_heading') ?? '';

// Determine final values: page option first, then theme option
$breadcrumb_display_heading = $breadcrumb_page_heading !== '' ? $breadcrumb_page_heading : $breadcrumb_heading;

$user_id   = get_current_user_id();
$user_meta = get_user_meta($user_id, 'egns_profile_options', true);
?>

<?php if (Egns\Helper\Egns_Helper::is_enabled($enable_breadcrumb_by_theme, $breadcrumb_enable_by_page)): ?>
    <section class="linkpva-page-hero">
        <div class="container">
            <?php echo egns_breadcrumb(); ?>
            <h1>
                <?php
                if (is_author()) {
                    echo esc_html__('Author: ', 'linkpva');
                    echo esc_html(get_the_author());
                } elseif (is_home()) {
                    echo esc_html__('Blog', 'linkpva');
                } else {
                    echo esc_html(get_the_title());
                }
                ?>
            </h1>
            <p><?php echo wp_kses_post(get_the_author_meta('description')); ?></p>
            <ul class="social-list-author">
                <?php if (!empty($user_meta['user_facebook_url'])) : ?>
                    <li><a href="<?php echo esc_url($user_meta['user_facebook_url']); ?>"><i class="bx bxl-facebook"></i></a></li>
                <?php endif ?>
                <?php if (!empty($user_meta['user_twitter_url'])) : ?>
                    <li><a href="<?php echo esc_url($user_meta['user_twitter_url']); ?>"><i class="bi bi-twitter-x"></i></a></li>
                <?php endif ?>
                <?php if (!empty($user_meta['user_linkedin_url'])) : ?>
                    <li><a href="<?php echo esc_url($user_meta['user_linkedin_url']); ?>"><i class="bx bxl-linkedin"></i></a></li>
                <?php endif ?>
                <?php if (!empty($user_meta['user_instagram_url'])) : ?>
                    <li><a href="<?php echo esc_url($user_meta['user_instagram_url']); ?>"><i class="bi bi-instagram"></i></a></li>
                <?php endif ?>
                <?php if (!empty($user_meta['user_pinterest_url'])) : ?>
                    <li><a href="<?php echo esc_url($user_meta['user_pinterest_url']); ?>"><i class="bi bi-pinterest"></i></a></li>
                <?php endif ?>
                <?php if (!empty($user_meta['user_youtube_url'])) : ?>
                    <li><a href="<?php echo esc_url($user_meta['user_youtube_url']); ?>"><i class="bx bxl-youtube"></i></a></li>
                <?php endif ?>
            </ul>
        </div>
    </section>
<?php endif; ?>