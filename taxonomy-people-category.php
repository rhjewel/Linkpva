<?php

/**
 * The template for displaying taxonomy pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package linkpva
 */

use Egns\Helper\Egns_Helper;

get_header();

if (!is_front_page()) {

    // Include breadcrumb template
    Egns\Helper\Egns_Helper::egns_template_part('breadcrumb', 'templates/breadcrumb-archive');
}


// Include taxonomy template
Egns\Helper\Egns_Helper::egns_template_part('people', 'archive');

get_footer();
