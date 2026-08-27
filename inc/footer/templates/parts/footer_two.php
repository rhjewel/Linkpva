<?php

if (class_exists('CSF') && !empty(Egns\Helper\Egns_Helper::egns_get_theme_option('footer_two_template'))) {
    echo \Egns_Core\Egns_Helper::get_footer_data(Egns\Helper\Egns_Helper::egns_get_theme_option('footer_two_template'));
} else { ?>
    <footer class="footer-section pt-16">
        <div class="copyright-area">
            <div class="container-fluid one">
                <span>© <?php echo esc_html(wp_date('Y')); ?> <?php echo esc_html__('Linkpva ', 'linkpva'); ?> <a href="<?php echo esc_url('https://www.egenslab.com/') ?>"><?php echo esc_html__('Egenslab', 'linkpva') ?></a>. <?php echo esc_html__('All Rights Reserved.', 'linkpva'); ?></span>
            </div>
        </div>
    </footer>
<?php } ?>