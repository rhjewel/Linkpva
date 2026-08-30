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
        <section class="linkpva-error-page">
            <div class="container"><strong aria-hidden="true"><?php echo esc_html__('404', 'linkpva'); ?></strong>
                <?php if (!empty(Egns\Helper\Egns_Helper::egns_get_theme_option('404_title'))) : ?>
                    <h1><?php echo wp_kses(Egns\Helper\Egns_Helper::egns_get_theme_option('404_title'), wp_kses_allowed_html('post')) ?></h1>
                <?php endif; ?>
                <?php if (!empty(Egns\Helper\Egns_Helper::egns_get_theme_option('404_content'))) : ?>
                    <p><?php echo wp_kses(Egns\Helper\Egns_Helper::egns_get_theme_option('404_content'), wp_kses_allowed_html('post')) ?></p>
                <?php endif; ?>
                <div class="linkpva-button-group justify-content-center">
                    <a class="linkpva-button linkpva-button-primary" href="<?php echo esc_url(home_url('/')); ?>"><i class="bi bi-house"></i> <?php echo esc_html__('Back to Home', 'linkpva'); ?></a>
                    <a class="linkpva-button linkpva-button-secondary" href="<?php echo esc_url(home_url('/shop')); ?>"><?php echo esc_html__('Browse Products', 'linkpva'); ?></a>
                </div>
            </div>
        </section>
    <?php else : ?>
        <section class="linkpva-error-page">
            <div class="container"><strong aria-hidden="true">404</strong>
                <h1><?php echo esc_html__('We Couldn’t Find That Page', 'linkpva'); ?></h1>
                <p><?php echo esc_html__('The page may have moved, the link may be incorrect, or the content may no longer be available.', 'linkpva'); ?></p>
                <div class="linkpva-button-group justify-content-center">
                    <a class="linkpva-button linkpva-button-primary" href="<?php echo esc_url(home_url('/')); ?>"><i class="bi bi-house"></i> <?php echo esc_html__('Back to Home', 'linkpva'); ?></a>
                    <a class="linkpva-button linkpva-button-secondary" href="<?php echo esc_url(home_url('/shop')); ?>"><?php echo esc_html__('Browse Products', 'linkpva'); ?></a>
                </div>
            </div>
        </section>
    <?php endif ?>
</div>

<?php
get_footer();
