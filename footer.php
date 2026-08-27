<?php
// Hook to include page footer template
do_action('egns_action_page_footer_template');
?>

</div>
</div><!-- close smooth scroll effect  -->
<?php
if (is_singular('career')) {
    get_template_part('inc/common/templates/career-form');
}
?>
</div><!-- close #app div from header.php -->

<?php wp_footer(); ?>
</body>

</html>