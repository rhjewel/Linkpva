<?php

get_header();

if (!is_front_page()) {
    // Include breadcrumb template
    Egns\Helper\Egns_Helper::egns_template_part('breadcrumb', 'templates/breadcrumb-single');
}

// Include single template
Egns\Helper\Egns_Helper::egns_template_part('career', 'single');

get_footer();
