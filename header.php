<!DOCTYPE html>
<html <?php language_attributes(); ?><?php echo class_exists('Egns_Core') && (1 == Egns\Helper\Egns_Helper::egns_get_theme_option('rtl_enable')) ? ' dir="rtl"' : '' ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
	<meta name="robots" content="noindex,follow" />
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php echo esc_url(get_bloginfo('pingback_url')); ?>">
	<?php wp_head(); ?>
</head>

<?php
$gsap = '';
if (class_exists('WooCommerce') && (is_shop() || is_product_category() || is_product_tag() || is_product())) {
	$gsap = 'gsap-gone';
} else {
	$gsap = 'tt-magic-cursor';
}

?>

<body <?php body_class($gsap); ?>>

	<?php
	// Hook to include default WordPress hook after body tag open
	if (function_exists('wp_body_open')) {
		wp_body_open();
	}
	?>

	<div id="magic-cursor">
		<div id="ball"></div>
	</div>

	<?php
	if (class_exists('Egns_Core') && (1 == Egns\Helper\Egns_Helper::egns_get_theme_option('scrolltop_enable'))) {
		get_template_part('inc/common/templates/scroll-top');
	}
	?>

	<!-- start #app -->
	<div id="app">

		<!-- For smooth scroll effect  -->
		<div id="smooth-wrapper">
			<div id="smooth-content">
				<?php
				// Hook to include page header template
				do_action('egns_action_page_header_template');
				?>