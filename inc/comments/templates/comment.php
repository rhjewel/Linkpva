<?php

/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package linkpva
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}

?>
<?php
if (have_comments()) {
    Egns\Helper\Egns_Helper::egns_template_part('comments', 'templates/parts/comment-list');
};

if (comments_open()) {
    Egns\Helper\Egns_Helper::egns_template_part('comments', 'templates/parts/comment-form');
};
?>
