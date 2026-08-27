<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined('ABSPATH') || exit;

get_header();

if (!is_front_page()) {
	// Include breadcrumb template
	Egns\Helper\Egns_Helper::egns_template_part('breadcrumb', 'templates/breadcrumb-archive');
}

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');

global $wp_query;

$aventis_shop_query_context = array();
$aventis_queried_object     = get_queried_object();

if ($aventis_queried_object instanceof WP_Term && taxonomy_exists($aventis_queried_object->taxonomy)) {
	$aventis_shop_query_context = array(
		'taxonomy' => $aventis_queried_object->taxonomy,
		'term_id'  => absint($aventis_queried_object->term_id),
	);
}

$aventis_shop_search       = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
$aventis_shop_max_pages    = isset($wp_query->max_num_pages) ? absint($wp_query->max_num_pages) : 1;
$aventis_recent_searches   = function_exists('aventis_get_recent_product_searches') ? aventis_get_recent_product_searches() : array();
$aventis_not_found_image   = get_template_directory_uri() . '/assets/img/not-found.png';
?>
<div class="inner-page-search-area mb-70">
	<div class="container one">
		<div class="row justify-content-center fade_anim" data-delay=".2" data-fade-from="top">
			<div class="col-xl-8 col-lg-10 col-md-11">
				<div class="inner-page-search-wrap">
					<form class="search-filed linkpva-product-search-form" role="<?php echo esc_attr__('search', 'linkpva') ?>">
						<svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
							<g>
								<path
									d="M15.2239 14.1678L13.1189 12.0701C14.26 10.6603 14.8168 8.86631 14.6742 7.05809C14.5316 5.24988 13.7006 3.56531 12.3525 2.35175C11.0045 1.1382 9.24212 0.488192 7.42892 0.535764C5.61571 0.583336 3.88988 1.32486 2.60731 2.60743C1.32474 3.89001 0.583214 5.61583 0.535642 7.42904C0.48807 9.24224 1.13808 11.0046 2.35163 12.3526C3.56519 13.7007 5.24975 14.5317 7.05797 14.6743C8.86619 14.8169 10.6602 14.2602 12.07 13.119L14.1677 15.2241C14.2368 15.2938 14.3191 15.3491 14.4097 15.3869C14.5004 15.4247 14.5976 15.4441 14.6958 15.4441C14.794 15.4441 14.8912 15.4247 14.9819 15.3869C15.0725 15.3491 15.1548 15.2938 15.2239 15.2241C15.2937 15.1549 15.349 15.0726 15.3868 14.982C15.4245 14.8914 15.444 14.7941 15.444 14.6959C15.444 14.5977 15.4245 14.5005 15.3868 14.4099C15.349 14.3192 15.2937 14.237 15.2239 14.1678ZM2.05039 7.62937C2.05039 6.52598 2.37758 5.44736 2.9906 4.52992C3.60361 3.61248 4.47491 2.89743 5.49431 2.47517C6.51372 2.05292 7.63544 1.94244 8.71763 2.15771C9.79983 2.37297 10.7939 2.9043 11.5741 3.68452C12.3543 4.46474 12.8857 5.4588 13.1009 6.54099C13.3162 7.62318 13.2057 8.74491 12.7834 9.76431C12.3612 10.7837 11.6461 11.655 10.7287 12.268C9.81126 12.881 8.73265 13.2082 7.62925 13.2082C6.14964 13.2082 4.73064 12.6205 3.6844 11.5742C2.63816 10.528 2.05039 9.10898 2.05039 7.62937Z" />
							</g>
						</svg>
						<input type="search" name="s" value="<?php echo esc_attr($aventis_shop_search); ?>" placeholder="<?php echo esc_attr__('Search products', 'linkpva'); ?>" autocomplete="off">
					</form>
					<span class="linkpva-product-recent-searches" <?php echo empty($aventis_recent_searches) ? 'style="display:none;"' : ''; ?>>
						<?php echo esc_html__('Recent searches:', 'linkpva'); ?>
						<span class="linkpva-product-recent-search-list">
							<?php foreach ($aventis_recent_searches as $recent_search) : ?>
								<a href="#" data-search="<?php echo esc_attr($recent_search); ?>"><?php echo esc_html($recent_search); ?></a>
							<?php endforeach; ?>
						</span>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="shop-page linkpva-woocommerce-archive" data-context="<?php echo esc_attr(wp_json_encode($aventis_shop_query_context)); ?>" data-max-pages="<?php echo esc_attr(max(1, $aventis_shop_max_pages)); ?>" data-current-page="1" data-search="<?php echo esc_attr($aventis_shop_search); ?>" data-not-found-image="<?php echo esc_url($aventis_not_found_image); ?>" style="--linkpva-product-not-found-image:url('<?php echo esc_url($aventis_not_found_image); ?>');">
	<div class="row gy-5 justify-content-center">
		<?php if (is_active_sidebar('shop_sidebar')) : ?>
			<div class="col-lg-3 fade_anim" data-delay=".2" data-fade-from="left">
				<div class="shop-sidebar">
					<?php
					dynamic_sidebar('shop_sidebar');
					?>
				</div>
			</div>
		<?php endif; ?>
		<div class="<?php echo is_active_sidebar('shop_sidebar') ? 'col-lg-9' : 'col-lg-12' ?>">
			<div class="row gy-5 linkpva-product-results">
				<?php if (woocommerce_product_loop()) : ?>
					<?php while (have_posts()) : the_post(); ?>
						<?php global $product; ?>
						<?php do_action('egns_aventis_shop_page_product_card'); ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php do_action('egns_aventis_shop_page_no_products'); ?>
				<?php endif; ?>
			</div>
			<div class="load-more-btn linkpva-product-load-more-wrap mt-70 text-center fade_anim" data-delay=".2" data-ease="bounce" <?php echo esc_attr($aventis_shop_max_pages > 1 ? '' : 'style="display:none;"'); ?>>
				<a href="#" class="primary-btn1 transparent animate-btn linkpva-product-load-more" data-text="<?php echo esc_attr__('Load more', 'linkpva'); ?>">
					<?php echo esc_html__('Load more', 'linkpva'); ?>
				</a>
			</div>
		</div>
	</div>
</div>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');


get_footer();
