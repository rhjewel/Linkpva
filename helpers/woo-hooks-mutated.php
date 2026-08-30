<?php
/*-------------------------
**** WooCommerce Hooks ****
--------------------------*/

if (class_exists('WooCommerce')) {
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
     * Get the Account Type product attribute value.
     */
    function egns_aventis_get_product_account_type($product)
    {
        if (!$product || !is_a($product, 'WC_Product')) {
            return '';
        }

        foreach ($product->get_attributes() as $attribute) {
            $attribute_name = $attribute->get_name();
            $attribute_label = function_exists('wc_attribute_label') ? wc_attribute_label($attribute_name, $product) : $attribute_name;
            $normalized_name = sanitize_title(preg_replace('/^pa_/', '', $attribute_name));

            if ('account-type' !== $normalized_name && 'account-type' !== sanitize_title($attribute_label)) {
                continue;
            }

            $values = $attribute->is_taxonomy()
                ? wc_get_product_terms($product->get_id(), $attribute_name, array('fields' => 'names'))
                : $attribute->get_options();

            if (!is_wp_error($values)) {
                $values = array_values(array_filter(array_map('sanitize_text_field', (array) $values)));
                return implode(', ', $values);
            }
        }

        return '';
    }

    /**
     * Get newline-separated Product Features from the Codestar product meta.
     */
    function egns_aventis_get_product_features($product_id, $limit = 3)
    {
        $product_meta = get_post_meta($product_id, 'EGNS_PRODUCT_META_ID', true);
        $features = is_array($product_meta) ? ($product_meta['product_feature_lbl'] ?? '') : '';

        if ('' === trim((string) $features)) {
            $features = get_post_meta($product_id, 'product_feature_lbl', true);
        }

        $features = preg_split('/\r\n|\r|\n/', wp_strip_all_tags((string) $features));
        $features = array_values(array_filter(array_map('trim', (array) $features)));

        return array_slice($features, 0, max(1, absint($limit)));
    }

    /**
     * Render the shared archive/related product card.
     */
    function egns_aventis_render_product_card($product, $column_class, $visual_index = 0, $feature_limit = 3)
    {
        if (!$product || !is_a($product, 'WC_Product') || !$product->is_visible()) {
            return;
        }

        $product_id = $product->get_id();
        $product_title = $product->get_name();
        $product_permalink = $product->get_permalink();
        $account_type = egns_aventis_get_product_account_type($product);
        $features = egns_aventis_get_product_features($product_id, $feature_limit);
        $categories = get_the_terms($product_id, 'product_cat');
        $category_name = (!is_wp_error($categories) && !empty($categories)) ? $categories[0]->name : '';
        $visual_classes = array('', 'is-purple', 'is-cyan', 'is-green');
        $visual_class = $visual_classes[absint($visual_index) % count($visual_classes)];
        ?>
        <div class="<?php echo esc_attr($column_class); ?>">
            <article class="linkpva-product-card">
                <div class="linkpva-product-visual<?php echo $visual_class ? ' ' . esc_attr($visual_class) : ''; ?>">
                    <?php if ($account_type) : ?>
                        <span class="linkpva-product-badge"><?php echo esc_html($account_type); ?></span>
                    <?php endif; ?>
                    <?php
                    if ($product->get_image_id()) {
                        echo wp_get_attachment_image($product->get_image_id(), 'woocommerce_thumbnail', false, array(
                            'alt'      => $product_title,
                            'loading'  => 'lazy',
                            'decoding' => 'async',
                        ));
                    } else {
                        echo wc_placeholder_img('woocommerce_thumbnail', array('alt' => $product_title));
                    }
                    ?>
                </div>
                <div class="linkpva-product-body">
                    <?php if ($category_name) : ?>
                        <span class="linkpva-product-category"><?php echo esc_html($category_name); ?></span>
                    <?php endif; ?>
                    <h3><a href="<?php echo esc_url($product_permalink); ?>"><?php echo esc_html($product_title); ?></a></h3>
                    <?php if ($features) : ?>
                        <ul>
                            <?php foreach ($features as $feature) : ?>
                                <li><i class="bi bi-check2" aria-hidden="true"></i> <?php echo esc_html($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <div class="linkpva-product-footer">
                        <strong><?php echo wp_kses_post($product->get_price_html()); ?></strong>
                        <a href="<?php echo esc_url($product_permalink); ?>" aria-label="<?php echo esc_attr(sprintf(__('View %s details', 'linkpva'), $product_title)); ?>"><i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </article>
        </div>
        <?php
    }

    /**
     * Archive page product card.
     */
    function egns_aventis_shop_product_card()
    {
        global $product;

        if (!$product || !is_a($product, 'WC_Product')) {
            return;
        }

        static $card_index = 0;
        egns_aventis_render_product_card($product, 'col-md-6 col-xl-4', $card_index, 3);
        $card_index++;
    }
    add_action('egns_aventis_shop_page_product_card', 'egns_aventis_shop_product_card');

    /**
     * Add custom WooCommerce related product cards.
     */
    function egns_woocommerce_related_products($current_product_id, $limit = 3)
    {
        $current_product_id = absint($current_product_id);
        $limit = max(1, absint($limit));

        if (!$current_product_id || !function_exists('wc_get_related_products')) {
            return;
        }

        $related_ids = wc_get_related_products($current_product_id, $limit * 3, array($current_product_id));
        $related_products = array();

        foreach ($related_ids as $related_id) {
            $related_product = wc_get_product($related_id);

            if (!$related_product || 'publish' !== get_post_status($related_id) || !$related_product->is_visible()) {
                continue;
            }

            $related_products[] = $related_product;
            if (count($related_products) >= $limit) {
                break;
            }
        }

        if (!$related_products) {
            return;
        }

        $shop_url = wc_get_page_permalink('shop');
        $heading_id = 'linkpva-related-products-heading-' . $current_product_id;
        ?>
        <section class="linkpva-section linkpva-products" aria-labelledby="<?php echo esc_attr($heading_id); ?>">
            <div class="container">
                <div class="linkpva-heading-row">
                    <div class="linkpva-section-heading">
                        <span class="linkpva-section-tag"><?php esc_html_e('You May Also Like', 'linkpva'); ?></span>
                        <h2 id="<?php echo esc_attr($heading_id); ?>"><?php esc_html_e('Related Products', 'linkpva'); ?></h2>
                    </div>
                    <?php if ($shop_url) : ?>
                        <a class="linkpva-text-link" href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('View all products', 'linkpva'); ?> <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    <?php endif; ?>
                </div>
                <div class="row g-4">
                    <?php foreach ($related_products as $index => $related_product) : ?>
                        <?php egns_aventis_render_product_card($related_product, 'col-md-6 col-lg-4', $index + 1, 2); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }

    function egns_related_products_output()
    {
        global $product;

        if (!$product || !is_a($product, 'WC_Product')) {
            return;
        }
        egns_woocommerce_related_products($product->get_id(), 3);
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
