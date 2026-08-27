<?php

/**
 * The template for displaying taxonomy pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package linkpva
 */

get_header();

if (!is_front_page()) {

    // Include breadcrumb template
    Egns\Helper\Egns_Helper::egns_template_part('breadcrumb', 'templates/breadcrumb-archive');
}


// Include archive template
Egns\Helper\Egns_Helper::egns_template_part('career', 'archive');

get_footer();
