<?php

use Egns\Helper\Egns_Helper;

if (!class_exists('Egns_Handler')) {

	/**
	 * Main theme class with configuration
	 */

	class Egns_Handler
	{

		/**
		 * Initializes a singleton instance
		 *
		 * @return \Egns_Handler
		 */
		private static ?self $instance = null;


		public static function get_instance()
		{
			if (is_null(self::$instance)) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Main Class Constructor
		 */
		public function __construct()
		{

			// Include all require files
			require_once get_template_directory() . '/helpers/constants.php';
			require_once EGNS_HELPERS_ROOT_DIR . '/theme_setup.php';
			require_once EGNS_HELPERS_ROOT_DIR . '/assets.php';
			require_once EGNS_HELPERS_ROOT_DIR . '/helper.php';
			require_once EGNS_HELPERS_ROOT_DIR . '/breadcrumb.php';
			require_once EGNS_HELPERS_ROOT_DIR . '/comments.php';
			require_once EGNS_HELPERS_ROOT_DIR . '/woo-hooks-mutated.php';
			require_once EGNS_HELPERS_ROOT_DIR . '/custom-with-ajax.php';
			require_once EGNS_HELPERS_ROOT_DIR . '/nav-walker.php';
			require_once EGNS_INC_ROOT_DIR . '/plugins/tgma/activation.php';

			// Instantiation helper classes
			new Egns\Helper\Egns_Assets();
			new Egns\Helper\Egns_Theme_Setup();
			new Egns\Helper\Egns_Helper();
		}
	}

	Egns_Handler::get_instance();
}


/**
 * Remove p tag from contact form 7
 */
add_filter('wpcf7_autop_or_not', '__return_false');


/**
 * moves wp-comment-cookies-consent to the end right before the submit
 */
function linkpva_move_cookies($fields)
{
	if (isset($fields['cookies'])) {
		$cookies = $fields['cookies'];
		unset($fields['cookies']);
		$fields['cookies'] = $cookies;
	}
	return $fields;
}
add_filter('comment_form_fields', 'linkpva_move_cookies');

/**
 * Output Custom CSS and JS in Frontend Get From Theme Option Panel
 */
function linkpva_enqueue_option_assets()
{
	$custom_css = Egns_Helper::egns_get_theme_option('custom_css');
	if (!empty($custom_css)) {
		$custom_css = preg_replace('#</?(?:style|script|title)\b[^>]*>#i', '', (string) $custom_css);
		wp_add_inline_style('egns-theme', $custom_css);
	}
	$custom_js = Egns_Helper::egns_get_theme_option('custom_javascript');
	if (! empty($custom_js)) {
		$custom_js = preg_replace('#</?(?:script|style|title)\b[^>]*>#i', '', (string) $custom_js);
		wp_add_inline_script('custom-main', $custom_js);
	}
}
add_action('wp_enqueue_scripts', 'linkpva_enqueue_option_assets', 100);

/**
 * Ensure WordPress never prints an empty <title> tag.
 */
function linkpva_fallback_document_title($title)
{
	$title = trim((string) $title);

	if ('' !== $title) {
		return $title;
	}

	$blogname = trim((string) get_bloginfo('name'));

	if ('' !== $blogname) {
		return $blogname;
	}

	return esc_html__('Linkpva', 'linkpva');
}
add_filter('pre_get_document_title', 'linkpva_fallback_document_title', 999);



/**
 * Custom post people posts_per_page
 **/
add_action('pre_get_posts', function ($query) {
	$number = Egns_Helper::egns_get_theme_option('people_posts_per_page') ?? '8';
	// Only modify the main query on the frontend
	if (!is_admin() && $query->is_main_query() && is_post_type_archive('people')) {
		$query->set('posts_per_page', $number);
	}
});

/**
 * Custom post career posts_per_page
 **/
add_action('pre_get_posts', function ($query) {
	$number = Egns_Helper::egns_get_theme_option('career_posts_per_page') ?? '9';
	// Only modify the main query on the frontend
	if (!is_admin() && $query->is_main_query() && is_post_type_archive('career')) {
		$query->set('posts_per_page', $number);
	}
});

/**
 * Custom post case-study posts_per_page
 **/
add_action('pre_get_posts', function ($query) {
	$number = Egns_Helper::egns_get_theme_option('case_study_posts_per_page') ?? '9';
	// Only modify the main query on the frontend
	if (!is_admin() && $query->is_main_query() && is_post_type_archive('case-study')) {
		$query->set('posts_per_page', $number);
	}
});


/**
 * Upgrade Font Awesome Free 6 with a front-end enqueue style
 **/
if (! function_exists('linkpva_enqueue_fa6')) {
	function linkpva_enqueue_fa6()
	{
		wp_enqueue_style('csf-fa6', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css', array(), '6.6.0', 'all');
		wp_enqueue_style('csf-fa6-v4-shims', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/v4-shims.min.css', array(), '6.6.0', 'all');
	}
	add_action('wp_enqueue_scripts', 'linkpva_enqueue_fa6');
}

/**
 * successful add-to-cart- too redirect checkout page
 */
add_filter( 'woocommerce_add_to_cart_redirect', function( $url ) {
    return wc_get_checkout_url();
} );