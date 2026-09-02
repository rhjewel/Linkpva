<?php

use Egns\Helper\Egns_Helper;

if (! defined('ABSPATH')) {
    exit;
}

$template_id = '';

if (class_exists('Egns_Core')) {
    // Page option priority first
    $template_id = Egns_Helper::egns_page_option_value('footer_one_template');

    // Then theme option
    if (empty($template_id)) {
        $template_id = Egns_Helper::egns_get_theme_option('footer_one_template');
    }
}


if (! empty($template_id) && class_exists('\Egns_Core\Egns_Helper')) {
    echo \Egns_Core\Egns_Helper::get_footer_data($template_id);
} else { ?>
    <footer class="linkpva-footer">
        <div class="linkpva-footer-copyright text-center">
            <p>&copy; <span data-current-year><?php echo esc_html(wp_date('Y')); ?></span> <?php echo esc_html__('LinkPVA. All rights reserved.', 'linkpva'); ?></p>
        </div>
    </footer>
<?php } ?>