<?php

if (class_exists('CSF') && !empty(Egns\Helper\Egns_Helper::egns_get_theme_option('footer_one_template'))) {
    echo \Egns_Core\Egns_Helper::get_footer_data(Egns\Helper\Egns_Helper::egns_get_theme_option('footer_one_template'));
} else { ?>
    <footer class="linkpva-footer">
        <div class="linkpva-footer-copyright text-center">
            <p>&copy; <span data-current-year><?php echo esc_html(wp_date('Y')); ?></span> <?php echo esc_html__('LinkPVA. All rights reserved.', 'linkpva'); ?></p>
        </div>
    </footer>
<?php } ?>