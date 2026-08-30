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

$account_type_taxonomy = function_exists('aventis_get_product_attribute_taxonomy') ? aventis_get_product_attribute_taxonomy('Account Type') : '';
$account_age_taxonomy = function_exists('aventis_get_product_attribute_taxonomy') ? aventis_get_product_attribute_taxonomy('Account Age') : '';
$account_type_terms = $account_type_taxonomy ? get_terms(array('taxonomy' => $account_type_taxonomy, 'hide_empty' => true, 'orderby' => 'name')) : array();
$account_age_terms = $account_age_taxonomy ? get_terms(array('taxonomy' => $account_age_taxonomy, 'hide_empty' => true, 'orderby' => 'name')) : array();
$account_type_terms = is_wp_error($account_type_terms) ? array() : $account_type_terms;
$account_age_terms = is_wp_error($account_age_terms) ? array() : $account_age_terms;
$product_categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => true, 'orderby' => 'name'));
$product_categories = is_wp_error($product_categories) ? array() : $product_categories;
$stock_options = function_exists('wc_get_product_stock_status_options') ? wc_get_product_stock_status_options() : array();
$selected_product_categories = isset($_GET['product_category']) ? aventis_sanitize_product_archive_values(wp_unslash($_GET['product_category'])) : array();
$selected_account_types = isset($_GET['account_type']) ? aventis_sanitize_product_archive_values(wp_unslash($_GET['account_type'])) : array();
$selected_account_ages = isset($_GET['account_age']) ? aventis_sanitize_product_archive_values(wp_unslash($_GET['account_age'])) : array();
$selected_stock_statuses = isset($_GET['stock_status']) ? aventis_sanitize_product_archive_values(wp_unslash($_GET['stock_status'])) : array();
$current_page = max(1, absint(get_query_var('paged')), absint(get_query_var('product-page')));
$posts_per_page = function_exists('aventis_get_product_archive_posts_per_page') ? aventis_get_product_archive_posts_per_page() : max(1, absint(get_option('posts_per_page')));
$archive_url = get_post_type_archive_link('product');
$archive_context = array();

if (is_product_taxonomy()) {
	$queried_object = get_queried_object();

	if ($queried_object instanceof WP_Term) {
		$archive_context = array(
			'taxonomy' => $queried_object->taxonomy,
			'term_id'  => $queried_object->term_id,
		);
		$term_url = get_term_link($queried_object);
		$archive_url = !is_wp_error($term_url) ? $term_url : $archive_url;
	}
}

?>

<section class="linkpva-inner-section linkpva-woocommerce-archive"
	data-product-archive
	data-context="<?php echo esc_attr(wp_json_encode($archive_context)); ?>"
	data-search="<?php echo esc_attr(get_search_query()); ?>"
	data-current-page="<?php echo esc_attr($current_page); ?>"
	data-products-per-page="<?php echo esc_attr($posts_per_page); ?>"
	data-max-pages="<?php echo esc_attr(max(1, (int) $wp_query->max_num_pages)); ?>">
	<div class="container">
		<div class="row g-4">
			<aside class="col-lg-3">
				<form class="linkpva-content-card linkpva-filter-card" method="get" action="<?php echo esc_url($archive_url); ?>" data-product-filters>
					<h2><?php esc_html_e('Filter Products', 'linkpva'); ?></h2>
					<?php if ($product_categories) : ?>
						<div class="linkpva-filter-group">
							<h3><?php esc_html_e('Product category', 'linkpva'); ?></h3>
							<?php foreach ($product_categories as $category) : ?>
								<label><input type="checkbox" name="product_category[]" value="<?php echo esc_attr($category->slug); ?>" <?php checked(in_array($category->slug, $selected_product_categories, true)); ?>> <?php echo esc_html($category->name); ?></label>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if ($account_type_terms) : ?>
						<div class="linkpva-filter-group">
							<h3><?php esc_html_e('Account type', 'linkpva'); ?></h3>
							<?php foreach ($account_type_terms as $term) : ?>
								<label><input type="checkbox" name="account_type[]" value="<?php echo esc_attr($term->slug); ?>" <?php checked(in_array($term->slug, $selected_account_types, true)); ?>> <?php echo esc_html($term->name); ?></label>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if ($account_age_terms) : ?>
						<div class="linkpva-filter-group">
							<h3><?php esc_html_e('Account age', 'linkpva'); ?></h3>
							<?php foreach ($account_age_terms as $term) : ?>
								<label><input type="checkbox" name="account_age[]" value="<?php echo esc_attr($term->slug); ?>" <?php checked(in_array($term->slug, $selected_account_ages, true)); ?>> <?php echo esc_html($term->name); ?></label>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if ($stock_options) : ?>
						<div class="linkpva-filter-group">
							<h3><?php esc_html_e('Availability', 'linkpva'); ?></h3>
							<?php foreach ($stock_options as $stock_status => $stock_label) : ?>
								<label><input type="checkbox" name="stock_status[]" value="<?php echo esc_attr($stock_status); ?>" <?php checked(in_array($stock_status, $selected_stock_statuses, true)); ?>> <?php echo esc_html($stock_label); ?></label>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<button class="linkpva-button linkpva-button-primary w-100" type="button" data-reset-product-filters><?php esc_html_e('Reset Filters', 'linkpva'); ?></button>
				</form>
			</aside>
			<div class="col-lg-9">
				<div class="linkpva-shop-toolbar">
					<div data-product-result-count><?php echo wp_kses_post(aventis_get_product_archive_result_count($wp_query->found_posts, $current_page, $posts_per_page)); ?></div>
					<?php woocommerce_catalog_ordering(); ?>
				</div>
				<div class="row g-4" data-product-grid>
					<?php if (woocommerce_product_loop()) : ?>
						<?php while (have_posts()) : the_post(); ?>
							<?php global $product; ?>
							<?php do_action('egns_aventis_shop_page_product_card'); ?>
						<?php endwhile; ?>
					<?php else : ?>
						<?php do_action('egns_aventis_shop_page_no_products'); ?>
					<?php endif; ?>
				</div>
				<div data-product-pagination-wrap>
					<?php echo aventis_get_product_archive_pagination($current_page, $wp_query->max_num_pages, $archive_url); ?>
				</div>
				<div class="screen-reader-text" aria-live="polite" aria-atomic="true" data-product-status></div>
			</div>
		</div>
	</div>
</section>

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');


get_footer();
