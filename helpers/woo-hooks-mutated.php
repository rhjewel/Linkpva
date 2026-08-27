<?php
/*-------------------------
**** WooCommerce Hooks ****
--------------------------*/

use Egns\Helper\Egns_Helper;

if (class_exists('WooCommerce')) {
    global $product;

    /**
     * 
     * WooCommerce before, after wrapper div change
     * 
     * */
    function aventis_wrapper_start()
    {
        echo '<div class="shop-page-wrapper mt-120 mb-120">
    <div class="container one">';
    }

    function aventis_wrapper_end()
    {
        echo '</div>
	</div>';
    }
    add_action('woocommerce_before_main_content', 'aventis_wrapper_start', 10);
    add_action('woocommerce_after_main_content', 'aventis_wrapper_end', 10);

    /**
     * remove default woocommerce sidebar
     */
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

    /**
     * remove default breadcrumb product
     */
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

    /**
     * remove default product content wrapper
     */
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

    /** 
     * Remove sale badge from single product page
     */
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

    /**
     * remove default related_products
     */
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

    /**
     * Show quantity minus button
     */
    function aventis_display_quantity_minus()
    {
        echo '<button type="button" class="minus" aria-label="' . esc_attr__('Decrease quantity', 'linkpva') . '"><i class="bi bi-dash"></i></button>';
    }
    add_action('woocommerce_before_quantity_input_field', 'aventis_display_quantity_minus');

    /**
     * Show quantity plus button
     */
    function aventis_display_quantity_plus()
    {
        echo '<button type="button" class="plus" aria-label="' . esc_attr__('Increase quantity', 'linkpva') . '"><i class="bi bi-plus"></i></button>';
    }
    add_action('woocommerce_after_quantity_input_field', 'aventis_display_quantity_plus');

    /**
     * Set default quantity only for single product page if empty
     */
    function aventis_set_default_quantity($args, $product)
    {
        if (is_product() && empty($args['input_value'])) {
            $args['input_value'] = max(1, isset($args['min_value']) ? (float) $args['min_value'] : 1);
        }

        return $args;
    }
    add_filter('woocommerce_quantity_input_args', 'aventis_set_default_quantity', 10, 2);

    /**
     * Quantity plus/minus script
     */
    function aventis_add_cart_quantity_plus_minus()
    {
        if (!is_product() && !is_cart()) {
            return;
        }
        wc_enqueue_js("
        jQuery(function($){
            $(document).on('click', '.quantity .plus, .quantity .minus', function() {
                var \$qty  = $(this).closest('.quantity').find('.qty');
                var currentVal = parseFloat(\$qty.val());
                var max  = parseFloat(\$qty.attr('max'));
                var min  = parseFloat(\$qty.attr('min'));
                var step = parseFloat(\$qty.attr('step'));
                if (isNaN(currentVal)) {
                    currentVal = 0;
                }
                if (isNaN(step) || step <= 0) {
                    step = 1;
                }
                if (isNaN(min)) {
                    min = 0;
                }
                if (isNaN(max)) {
                    max = '';
                }
                if ($(this).hasClass('plus')) {
                    if (max !== '' && currentVal >= max) {
                        \$qty.val(max);
                    } else {
                        \$qty.val((currentVal + step).toFixed(step % 1 !== 0 ? 2 : 0));
                    }
                } else {
                    if (currentVal <= min) {
                        \$qty.val(min);
                    } else {
                        \$qty.val((currentVal - step).toFixed(step % 1 !== 0 ? 2 : 0));
                    }
                }
                \$qty.trigger('change');
            });
        });
    ");
    }
    add_action('wp_footer', 'aventis_add_cart_quantity_plus_minus');

    /**
     * Remove default WooCommerce archive item parts
     */
    function egns_aventis_remove_woocommerce_hooks()
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        if (is_shop() || is_product_category() || is_product_tag()) {
            remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
            remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        }
    }
    add_action('wp', 'egns_aventis_remove_woocommerce_hooks');


    /**
     * Archive page product card
     */
    function egns_aventis_shop_product_card()
    {
        global $product;

        if (!$product || !is_a($product, 'WC_Product')) {
            return;
        }

        $product_id        = $product->get_id();
        $product_title     = $product->get_name();
        $product_permalink = get_permalink($product_id);
        $product_price     = $product->get_price_html();
        $product_image     = get_the_post_thumbnail_url($product_id, 'full');
        $product_image     = $product_image ? $product_image : wc_placeholder_img_src();
        $rating_count      = $product->get_rating_count();
        $average_rating    = $product->get_average_rating();
        $review_count      = $product->get_review_count();

        $add_to_cart_url   = $product->add_to_cart_url();
        $add_to_cart_text  = $product->add_to_cart_text();
        $product_type      = $product->get_type();
        $product_sku       = $product->get_sku();

        $button_classes = implode(' ', array_filter(array(
            'cart',
            'button',
            'product_type_' . $product->get_type(),
            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
            ($product->supports('ajax_add_to_cart') && $product->get_type() === 'simple') ? 'ajax_add_to_cart' : '',
        )));
?>


        <div class="col-lg-4 col-sm-6 fade_anim" data-delay=".2">
            <div class="product-card">
                <div class="product-card-img-wrap">
                    <a href="<?php echo esc_url($product_permalink); ?>" class="product-card-img">
                        <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>">
                    </a>
                    <a href="<?php echo esc_url($add_to_cart_url); ?>"
                        class="custom-add-to-cart-btn cart-btn <?php echo esc_attr($button_classes); ?>" data-quantity="1" data-product_id="<?php echo esc_attr($product_id); ?>" data-product_sku="<?php echo esc_attr($product_sku); ?>" aria-label="<?php echo esc_attr($add_to_cart_text); ?>" rel="nofollow">
                        <?php echo esc_html($add_to_cart_text); ?>
                    </a>
                </div>
                <div class="product-card-content">
                    <?php if (wc_review_ratings_enabled()) : ?>
                        <ul class="rating">
                            <?php
                            $rounded_rating = round($average_rating);
                            for ($i = 1; $i <= 5; $i++) :
                                if ($i <= $rounded_rating) :
                            ?>
                                    <li>&#9733;</li>
                                <?php
                                else :
                                ?>
                                    <li>&#9734;</li>
                            <?php
                                endif;
                            endfor;
                            ?>
                        </ul>
                    <?php endif; ?>
                    <h3>
                        <a href="<?php echo esc_url($product_permalink); ?>">
                            <?php echo esc_html($product_title); ?>
                        </a>
                    </h3>
                    <span><?php echo wp_kses_post($product_price); ?></span>
                </div>
            </div>
        </div>

    <?php
    }
    add_action('egns_aventis_shop_page_product_card', 'egns_aventis_shop_product_card');



    /**
     * Add Custom WooCommerce Related Product card
     */
    function egns_woocommerce_related_products($current_product_id, $limit = 8)
    {
        if (!$current_product_id || !class_exists('WooCommerce')) {
            return;
        }

        // Get product categories
        $cat_terms   = wp_get_post_terms($current_product_id, 'product_cat');
        $categories  = wp_list_pluck($cat_terms, 'term_id');

        // Get product tags
        $tag_terms = wp_get_post_terms($current_product_id, 'product_tag');
        $tags      = wp_list_pluck($tag_terms, 'term_id');

        // Build tax query safely
        $tax_query = array();

        if (!empty($categories) || !empty($tags)) {
            $tax_query['relation'] = 'OR';

            if (!empty($categories)) {
                $tax_query[] = array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $categories,
                );
            }

            if (!empty($tags)) {
                $tax_query[] = array(
                    'taxonomy' => 'product_tag',
                    'field'    => 'term_id',
                    'terms'    => $tags,
                );
            }
        } else {
            return; // No categories/tags, so no related products
        }

        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'posts_per_page'      => $limit,
            'post__not_in'        => array($current_product_id),
            'orderby'             => 'rand',
            'ignore_sticky_posts' => 1,
            'tax_query'           => $tax_query,
        );

        $related_products = new WP_Query($args);

        if (!$related_products->have_posts()) {
            wp_reset_postdata();
            return;
        }
    ?>
        <div class="related-product-section mt-100">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h3><?php echo esc_html__('Related Product', 'linkpva'); ?></h3>
                    </div>
                </div>
            </div>
            <div class="related-product-slider-area">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="swiper related-product-slider">
                            <div class="swiper-wrapper">
                                <?php while ($related_products->have_posts()) : $related_products->the_post(); ?>
                                    <?php
                                    $product = wc_get_product(get_the_ID());

                                    if (!$product || !is_a($product, 'WC_Product')) {
                                        continue;
                                    }

                                    $product_id        = $product->get_id();
                                    $product_title     = $product->get_name();
                                    $product_permalink = get_permalink($product_id);
                                    $product_price     = $product->get_price_html();
                                    $product_image     = get_the_post_thumbnail_url($product_id, 'full');
                                    $product_image     = $product_image ? $product_image : wc_placeholder_img_src();
                                    $rating_count      = $product->get_rating_count();
                                    $average_rating    = $product->get_average_rating();
                                    $review_count      = $product->get_review_count();

                                    $add_to_cart_url   = $product->add_to_cart_url();
                                    $add_to_cart_text  = $product->add_to_cart_text();
                                    $product_type      = $product->get_type();
                                    $product_sku       = $product->get_sku();

                                    $button_classes = implode(' ', array_filter(array(
                                        'cart',
                                        'button',
                                        'product_type_' . $product->get_type(),
                                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                                        ($product->supports('ajax_add_to_cart') && $product->get_type() === 'simple') ? 'ajax_add_to_cart' : '',
                                    )));
                                    ?>
                                    <div class="swiper-slide">
                                        <div class="product-card">
                                            <div class="product-card-img-wrap">
                                                <a href="<?php echo esc_url($product_permalink); ?>" class="product-card-img">
                                                    <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>">
                                                </a>
                                                <a href="<?php echo esc_url($add_to_cart_url); ?>"
                                                    class="custom-add-to-cart-btn cart-btn <?php echo esc_attr($button_classes); ?>" data-quantity="1" data-product_id="<?php echo esc_attr($product_id); ?>" data-product_sku="<?php echo esc_attr($product_sku); ?>" aria-label="<?php echo esc_attr($add_to_cart_text); ?>" rel="nofollow">
                                                    <?php echo esc_html($add_to_cart_text); ?>
                                                </a>
                                            </div>
                                            <div class="product-card-content">
                                                <?php if (wc_review_ratings_enabled()) : ?>
                                                    <ul class="rating">
                                                        <?php
                                                        $rounded_rating = round($average_rating);
                                                        for ($i = 1; $i <= 5; $i++) :
                                                            if ($i <= $rounded_rating) :
                                                        ?>
                                                                <li>&#9733;</li>
                                                            <?php
                                                            else :
                                                            ?>
                                                                <li>&#9734;</li>
                                                        <?php
                                                            endif;
                                                        endfor;
                                                        ?>
                                                    </ul>
                                                <?php endif; ?>
                                                <h3>
                                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                                        <?php echo esc_html($product_title); ?>
                                                    </a>
                                                </h3>
                                                <span><?php echo wp_kses_post($product_price); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slider-btn-grp two">
                    <div class="slider-btn related-product-slider-prev">
                        <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <path d="M2 6L10 12V0L2 6Z" />
                            </g>
                        </svg>
                    </div>
                    <div class="slider-btn related-product-slider-next">
                        <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <path d="M10.6665 6L2.6665 12L2.6665 0L10.6665 6Z" />
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    <?php
        wp_reset_postdata();
    }

    function egns_related_products_output()
    {
        global $product;

        if (!$product || !is_a($product, 'WC_Product')) {
            return;
        }
        egns_woocommerce_related_products($product->get_id(), 8);
    }
    add_action('woocommerce_after_single_product_summary', 'egns_related_products_output', 20);


    /**
     * Product not found message
     */
    function egns_aventis_shop_no_products()
    {
    ?>
        <div class="col-12">
            <div class="linkpva-product-no-results">
                <span class="no-results-title"><?php echo esc_html__('Sorry Nothing Found!', 'linkpva'); ?></span>
                <span class="no-results-description"><?php echo esc_html__('Nothing Match your search terms. Please try again with some different keywords.', 'linkpva'); ?></span>
            </div>
        </div>
    <?php
    }
    add_action('egns_aventis_shop_page_no_products', 'egns_aventis_shop_no_products');



    /**
     * woocommerce product single excerpt position change
     */
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 9);

    /**
     * Buy Now button after single product add to cart button
     */
    function aventis_single_product_buy_now_button()
    {
        global $product;

        if (! $product || ! is_a($product, 'WC_Product')) {
            return;
        }

        // Show only for simple products
        if (! $product->is_type('simple')) {
            return;
        }

        $buy_now_url = add_query_arg(
            array(
                'add-to-cart' => $product->get_id(),
                'quantity'    => 1,
            ),
            wc_get_checkout_url()
        );
    ?>
        <a class="primary-btn3 two" href="<?php echo esc_url($buy_now_url); ?>">
            <span data-text="<?php esc_attr_e('Buy Now', 'linkpva'); ?>"><?php esc_html_e('Buy Now', 'linkpva'); ?></span>
            <svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
                <g>
                    <path d="M8.88883 5L2.22217 10L2.22217 0L8.88883 5Z"></path>
                </g>
            </svg>
        </a>
<?php
    }
    add_action('woocommerce_after_add_to_cart_button', 'aventis_single_product_buy_now_button');


    /**
     * Wrap WooCommerce tabs with custom parent div
     */
    function aventis_custom_product_tabs_wrapper()
    {
        echo '<div class="product-description-and-review-area">';
        woocommerce_output_product_data_tabs();
        echo '</div>';
    }

    function aventis_wrap_woocommerce_tabs()
    {
        if (!is_product()) {
            return;
        }

        // Remove default tabs output
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
        // Add custom wrapped tabs
        add_action('woocommerce_after_single_product_summary', 'aventis_custom_product_tabs_wrapper', 10);
    }
    add_action('wp', 'aventis_wrap_woocommerce_tabs');

    /**
     * Rename description tab title and remove description tab title
     */
    function rename_description_tab($tabs)
    {
        if (isset($tabs['description'])) {
            $tabs['description']['title'] = __('Product Details', 'linkpva');
        }
        return $tabs;
    }
    add_filter('woocommerce_product_tabs', 'rename_description_tab', 98);

    function remove_description_tab_title($title)
    {
        if (is_product()) {
            return '';  // Return empty to remove the title
        }
        return $title;
    }
    add_filter('woocommerce_product_description_heading', 'remove_description_tab_title');


    /**
     * Change gallery thumbnail default size to custom size
     * */
    add_filter('woocommerce_get_image_size_gallery_thumbnail', function ($size) {
        return array(
            'width'  => 200,
            'height' => 250,
            'crop'   => 0,
        );
    });




    //   End WooCommerce class   
}
