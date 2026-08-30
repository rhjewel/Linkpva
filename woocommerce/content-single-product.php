<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

$product_id = $product->get_id();
$product_categories = get_the_terms($product_id, 'product_cat');
$product_category = (!is_wp_error($product_categories) && !empty($product_categories)) ? $product_categories[0]->name : '';
$short_description = apply_filters('woocommerce_short_description', $product->get_short_description());
$stock_status = $product->get_stock_status();
$stock_options = function_exists('wc_get_product_stock_status_options') ? wc_get_product_stock_status_options() : array();
$stock_label = $stock_options[$stock_status] ?? ucfirst(str_replace('_', ' ', $stock_status));
$stock_icon = 'outofstock' === $stock_status ? 'bi bi-x-circle-fill' : ('onbackorder' === $stock_status ? 'bi bi-clock-fill' : 'bi bi-check-circle-fill');
$product_meta = get_post_meta($product_id, 'EGNS_PRODUCT_META_ID', true);
$product_specifications = is_array($product_meta) ? ($product_meta['product_specifications'] ?? array()) : array();
$product_specifications = array_values(array_filter((array) $product_specifications, function ($specification) {
	return is_array($specification) && '' !== trim((string) ($specification['specification_label'] ?? '')) && '' !== trim((string) ($specification['specification_value'] ?? ''));
}));
$attribute_specifications = array();

// Product Specifications have priority. Use visible product attributes only
// when the Codestar repeater has no complete label/value rows.
if (!$product_specifications) {
	foreach ($product->get_attributes() as $attribute) {
		if (!$attribute->get_visible()) {
			continue;
		}

		$attribute_name = $attribute->get_name();
		$attribute_values = $attribute->is_taxonomy()
			? wc_get_product_terms($product_id, $attribute_name, array('fields' => 'names'))
			: $attribute->get_options();

		if (is_wp_error($attribute_values)) {
			continue;
		}

		$attribute_values = array_values(array_filter(array_map('sanitize_text_field', (array) $attribute_values), 'strlen'));

		if (!$attribute_values) {
			continue;
		}

		$attribute_specifications[] = array(
			'specification_label' => wc_attribute_label($attribute_name, $product),
			'specification_value' => implode(', ', $attribute_values),
		);
	}
}

$specifications = $product_specifications ?: $attribute_specifications;
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

	<div class="row g-5 align-items-start">
		<div class="col-lg-6">
			<div class="linkpva-product-gallery">
				<?php
				/**
				 * Hook: woocommerce_before_single_product_summary.
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action('woocommerce_before_single_product_summary');
				?>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="linkpva-product-summary">
				<?php if ($product_category) : ?>
					<span class="linkpva-product-category"><?php echo esc_html($product_category); ?></span>
				<?php endif; ?>
				<h1 class="product_title entry-title"><?php echo esc_html($product->get_name()); ?></h1>
				<span class="linkpva-stock is-<?php echo esc_attr($stock_status); ?>"><i class="<?php echo esc_attr($stock_icon); ?>" aria-hidden="true"></i> <?php echo esc_html($stock_label); ?></span>
				<?php if ($product->get_price_html()) : ?>
					<span class="linkpva-product-price-large"><?php echo wp_kses_post($product->get_price_html()); ?></span>
				<?php endif; ?>
				<?php if ($short_description) : ?>
					<div class="woocommerce-product-details__short-description"><?php echo wp_kses_post($short_description); ?></div>
				<?php endif; ?>

				<div class="linkpva-purchase-row">
					<?php woocommerce_template_single_add_to_cart(); ?>
				</div>

				<?php if ($specifications) : ?>
					<ul class="linkpva-spec-list">
						<?php foreach ($specifications as $specification) : ?>
							<?php
							$specification_label = trim((string) ($specification['specification_label'] ?? ''));
							$specification_value = trim((string) ($specification['specification_value'] ?? ''));
							if ('' === $specification_label || '' === $specification_value) {
								continue;
							}
							?>
							<li><span><?php echo esc_html($specification_label); ?></span><span><?php echo esc_html($specification_value); ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php
				/**
				 * Hook: woocommerce_single_product_summary.
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 10
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 * @hooked WC_Structured_Data::generate_product_data() - 60
				 */
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 9);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
				do_action('woocommerce_single_product_summary');
				?>
			</div>
		</div>
	</div>

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action('woocommerce_after_single_product_summary');
	?>
</div>

<?php do_action('woocommerce_after_single_product'); ?>
