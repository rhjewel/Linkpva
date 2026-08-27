<?php

/**
 * Product archive AJAX search and load more.
 */
function aventis_get_recent_product_searches()
{
    $recent_searches = get_option('aventis_recent_product_searches', array());

    return array_slice(array_filter(array_map('sanitize_text_field', (array) $recent_searches)), 0, 4);
}

function aventis_add_recent_product_search($query)
{
    $query = trim(sanitize_text_field($query));

    if ('' === $query) {
        return;
    }

    $recent_searches = aventis_get_recent_product_searches();
    $recent_searches = array_values(array_filter($recent_searches, function ($recent_search) use ($query) {
        return strtolower($recent_search) !== strtolower($query);
    }));

    array_unshift($recent_searches, $query);
    update_option('aventis_recent_product_searches', array_slice($recent_searches, 0, 4), false);
}

function aventis_get_product_archive_posts_per_page()
{
    if (function_exists('wc_get_default_product_rows_per_page') && function_exists('wc_get_default_products_per_row')) {
        $posts_per_page = wc_get_default_product_rows_per_page() * wc_get_default_products_per_row();
    } else {
        $posts_per_page = get_option('posts_per_page');
    }

    return max(1, absint(apply_filters('loop_shop_per_page', $posts_per_page)));
}

function aventis_build_product_archive_ajax_args($page, $search, $context)
{
    $args = array(
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'posts_per_page'      => aventis_get_product_archive_posts_per_page(),
        'paged'               => max(1, absint($page)),
        'ignore_sticky_posts' => true,
    );

    if ('' !== $search) {
        $args['s'] = $search;
    }

    $tax_query = array();

    if (function_exists('wc_get_product_visibility_term_ids')) {
        $product_visibility_terms = wc_get_product_visibility_term_ids();

        if (!empty($product_visibility_terms['exclude-from-catalog'])) {
            $tax_query[] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => array(absint($product_visibility_terms['exclude-from-catalog'])),
                'operator' => 'NOT IN',
            );
        }
    }

    if (!empty($context['taxonomy']) && !empty($context['term_id']) && taxonomy_exists($context['taxonomy'])) {
        $tax_query[] = array(
            'taxonomy' => sanitize_key($context['taxonomy']),
            'field'    => 'term_id',
            'terms'    => array(absint($context['term_id'])),
        );
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    return $args;
}

function aventis_render_product_archive_results($query)
{
    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            global $product;
            $product = wc_get_product(get_the_ID());
            do_action('egns_aventis_shop_page_product_card');
        }
    } else {
        do_action('egns_aventis_shop_page_no_products');
    }

    wp_reset_postdata();

    return ob_get_clean();
}

function aventis_product_archive_ajax()
{
    if (!class_exists('WooCommerce')) {
        wp_send_json_error(array('message' => esc_html__('WooCommerce is not active.', 'linkpva')), 400);
    }

    check_ajax_referer('ajax-nonce', 'nonce');

    $page    = isset($_POST['page']) ? absint($_POST['page']) : 1;
    $search  = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';
    $save_recent = !empty($_POST['save_recent']) && 'yes' === sanitize_text_field(wp_unslash($_POST['save_recent']));
    $recent_only = !empty($_POST['recent_only']) && 'yes' === sanitize_text_field(wp_unslash($_POST['recent_only']));
    $context = array();

    if (!empty($_POST['context'])) {
        $decoded_context = json_decode(wp_unslash($_POST['context']), true);

        if (is_array($decoded_context)) {
            $context = $decoded_context;
        }
    }

    if ($save_recent && '' !== $search && 1 === $page) {
        aventis_add_recent_product_search($search);
    }

    if ($recent_only) {
        wp_send_json_success(array(
            'recent' => aventis_get_recent_product_searches(),
        ));
    }

    $products = new WP_Query(aventis_build_product_archive_ajax_args($page, $search, $context));

    wp_send_json_success(array(
        'html'        => aventis_render_product_archive_results($products),
        'page'        => $page,
        'max_pages'   => absint($products->max_num_pages),
        'found_posts' => absint($products->found_posts),
        'recent'      => aventis_get_recent_product_searches(),
    ));
}
add_action('wp_ajax_aventis_product_archive_ajax', 'aventis_product_archive_ajax');
add_action('wp_ajax_nopriv_aventis_product_archive_ajax', 'aventis_product_archive_ajax');

function aventis_enqueue_product_archive_ajax_assets()
{
    if (!class_exists('WooCommerce') || (!is_shop() && !is_product_taxonomy())) {
        return;
    }

    wp_enqueue_script(
        'linkpva-woocommerce-archive',
        EGNS_ASSETS_JS_ROOT . '/woocommerce-archive.js',
        array('jquery', 'custom-main'),
        filemtime(EGNS_ASSETS_JS_ROOT_DIR . '/woocommerce-archive.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'aventis_enqueue_product_archive_ajax_assets', 30);



// Function to add a search query to recent searches
function add_recent_search($query)
{
    // Trim the search query to remove leading and trailing spaces
    $query = trim($query);

    // Check if the query is not empty
    if (!empty($query)) {
        $recent_searches = get_option('recent_searches', array());

        // Remove any existing occurrences of the query
        $recent_searches = array_diff($recent_searches, array($query));

        // Add the query to the beginning of the array
        array_unshift($recent_searches, $query);

        // Limit the number of recent searches, adjust as needed
        $max_recent_searches = 6;

        // Trim the array to the maximum allowed size
        $recent_searches = array_slice($recent_searches, 0, $max_recent_searches);

        // Update the option
        update_option('recent_searches', $recent_searches);
    }
}

// Function to get recent searches
function get_recent_searches()
{
    return get_option('recent_searches', array());
}

// Call add_recent_search whenever a search is performed
if (isset($_GET['s'])) {
    $search_query = sanitize_text_field($_GET['s']);
    add_recent_search($search_query);
}

// AJAX handler to clear search history
function clear_search_history()
{
    delete_option('recent_searches');
    wp_send_json_success();
}
add_action('wp_ajax_clear_search_history', 'clear_search_history');

/**
 * People archive AJAX search and load more.
 */
function aventis_get_people_archive_posts_per_page()
{
    $posts_per_page = class_exists('Egns\Helper\Egns_Helper') ? \Egns\Helper\Egns_Helper::egns_get_theme_option('people_posts_per_page') : '';
    $posts_per_page = !empty($posts_per_page) ? absint($posts_per_page) : 8;

    return max(1, $posts_per_page);
}

function aventis_get_people_archive_meta($post_id, $key, $default = '')
{
    $meta = get_post_meta($post_id, 'EGNS_PEOPLE_META_ID', true);

    if (is_array($meta) && isset($meta[$key])) {
        return $meta[$key];
    }

    $single_meta = get_post_meta($post_id, $key, true);

    return !empty($single_meta) ? $single_meta : $default;
}

function aventis_get_people_archive_designation($post_id)
{
    $designation = aventis_get_people_archive_meta($post_id, 'people_designation');

    if (!empty($designation)) {
        return $designation;
    }

    foreach (array('designation', 'member_designation') as $key) {
        $designation = get_post_meta($post_id, $key, true);

        if (!empty($designation)) {
            return $designation;
        }
    }

    return get_the_excerpt($post_id);
}

function aventis_get_people_archive_socials($post_id)
{
    $socials = aventis_get_people_archive_meta($post_id, 'people_info_list', array());

    if (is_array($socials) && !empty($socials)) {
        return array_filter($socials, function ($social) {
            return !empty($social['social_icon_link']);
        });
    }

    foreach (array('people_linkedin_url', 'linkedin_url', 'linkedin') as $key) {
        $link = get_post_meta($post_id, $key, true);

        if (!empty($link)) {
            return array(
                array(
                    'social_icon'      => 'fa fa-linkedin',
                    'social_icon_link' => $link,
                ),
            );
        }
    }

    return array();
}

function aventis_render_people_archive_social_icon($icon = '')
{
    if (!empty($icon)) {
        echo '<i class="' . esc_attr($icon) . '"></i>';
        return;
    }
?>
    <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
        <path d="M1.44229 2.88941C2.23885 2.88941 2.88459 2.24259 2.88459 1.44471C2.88459 0.646816 2.23885 0 1.44229 0C0.645737 0 0 0.646816 0 1.44471C0 2.24259 0.645737 2.88941 1.44229 2.88941Z" />
        <path d="M4.24598 3.98446V11.9997H6.73044V8.03598C6.73044 6.99008 6.92688 5.9772 8.22151 5.9772C9.49835 5.9772 9.51417 7.17298 9.51417 8.102V12.0003H12V7.60481C12 5.44567 11.5359 3.78638 9.01649 3.78638C7.80689 3.78638 6.99609 4.45128 6.66452 5.08054H6.6309V3.98446H4.24598ZM0.197266 3.98446H2.68569V11.9997H0.197266V3.98446Z" />
    </svg>
<?php
}

function aventis_render_people_archive_card()
{
    $post_id     = get_the_ID();
    $title       = get_the_title();
    $permalink   = get_permalink();
    $image_url   = has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'full') : get_template_directory_uri() . '/assets/img/user.jpg';
    $designation = aventis_get_people_archive_designation($post_id);
    $socials     = aventis_get_people_archive_socials($post_id);
?>
    <div class="col-lg-3 col-md-4 col-sm-6 team-item">
        <div class="team-card">
            <a href="<?php echo esc_url($permalink); ?>" class="team-img">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
            </a>
            <div class="team-content">
                <h2 class="team-name"><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h2>
                <?php if (!empty($designation)) : ?>
                    <span><?php echo esc_html($designation); ?></span>
                <?php endif; ?>
                <?php if (!empty($socials)) : ?>
                    <div class="social">
                        <?php foreach ($socials as $social) :
                            $social_link = !empty($social['social_icon_link']) ? $social['social_icon_link'] : '';

                            if (empty($social_link)) {
                                continue;
                            }
                        ?>
                            <a href="<?php echo esc_url($social_link); ?>" target="_blank" rel="nofollow noopener" aria-label="<?php echo esc_attr__('Social Profile', 'linkpva'); ?>">
                                <?php aventis_render_people_archive_social_icon($social['social_icon'] ?? ''); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
}

function aventis_build_people_archive_ajax_args($page, $search)
{
    $args = array(
        'post_type'           => 'people',
        'post_status'         => 'publish',
        'posts_per_page'      => aventis_get_people_archive_posts_per_page(),
        'paged'               => max(1, absint($page)),
        'ignore_sticky_posts' => true,
    );

    if ('' !== $search) {
        $args['s'] = $search;
    }

    return $args;
}

function aventis_render_people_archive_results($query)
{
    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            aventis_render_people_archive_card();
        }
    }

    wp_reset_postdata();

    return ob_get_clean();
}

function aventis_people_archive_ajax()
{
    check_ajax_referer('ajax-nonce', 'nonce');

    $page   = isset($_POST['page']) ? absint($_POST['page']) : 1;
    $search = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';
    $people = new WP_Query(aventis_build_people_archive_ajax_args($page, $search));

    wp_send_json_success(array(
        'html'        => aventis_render_people_archive_results($people),
        'page'        => $page,
        'max_pages'   => absint($people->max_num_pages),
        'found_posts' => absint($people->found_posts),
        'shown'       => absint($people->post_count),
    ));
}
add_action('wp_ajax_aventis_people_archive_ajax', 'aventis_people_archive_ajax');
add_action('wp_ajax_nopriv_aventis_people_archive_ajax', 'aventis_people_archive_ajax');

function aventis_enqueue_people_archive_ajax_assets()
{
    if (!is_post_type_archive('people')) {
        return;
    }

    wp_enqueue_script(
        'linkpva-people-archive',
        EGNS_ASSETS_JS_ROOT . '/people-archive.js',
        array('jquery', 'custom-main'),
        filemtime(EGNS_ASSETS_JS_ROOT_DIR . '/people-archive.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'aventis_enqueue_people_archive_ajax_assets', 30);
