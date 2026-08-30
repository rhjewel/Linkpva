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

?>

<section class="linkpva-inner-section">
	<div class="container">
		<div class="row g-4">
			<aside class="col-lg-3">
				<form class="linkpva-content-card linkpva-filter-card">
					<h2>Filter Products</h2>
					<div class="linkpva-filter-group">
						<h3>Account type</h3><label><input type="checkbox" name="type" value="verified">
							Verified Accounts</label><label><input type="checkbox" name="type" value="aged">
							Old/Aged Accounts</label><label><input type="checkbox" name="type" value="pva"> PVA
							Accounts</label><label><input type="checkbox" name="type" value="followers"> With
							Followers</label>
					</div>
					<div class="linkpva-filter-group">
						<h3>Account age</h3><label><input type="checkbox" name="age" value="1-3"> 1–3
							years</label><label><input type="checkbox" name="age" value="3-5"> 3–5
							years</label><label><input type="checkbox" name="age" value="5+"> 5+ years</label>
					</div>
					<div class="linkpva-filter-group">
						<h3>Availability</h3><label><input type="checkbox" name="stock" value="available">
							Available listings</label>
					</div><button class="linkpva-button linkpva-button-primary w-100" type="button">Apply
						Filters</button>
				</form>
			</aside>
			<div class="col-lg-9">
				<div class="linkpva-shop-toolbar">
					<p>Showing 1–8 of 12 sample products</p>
					<label>
						<span class="visually-hidden">Sort products</span>
						<select>
							<option>Default sorting</option>
							<option>Price: low to high</option>
							<option>Price: high to low</option>
							<option>Newest first</option>
						</select>
					</label>
				</div>
				<div class="row g-4">
					<?php if (woocommerce_product_loop()) : ?>
						<?php while (have_posts()) : the_post(); ?>
							<?php global $product; ?>
							<?php do_action('egns_aventis_shop_page_product_card'); ?>
						<?php endwhile; ?>
					<?php else : ?>
						<?php do_action('egns_aventis_shop_page_no_products'); ?>
					<?php endif; ?>
				</div>
				<nav class="linkpva-pagination" aria-label="Product pagination">
					<span aria-current="page">1</span>
					<a href="#">2</a>
					<a href="#" aria-label="Next page"><i class="bi bi-arrow-right"></i></a>
				</nav>
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
