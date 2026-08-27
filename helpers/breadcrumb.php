<?php
if (!function_exists('egns_breadcrumb')) {

    function egns_breadcrumb($list_style = 'ul', $list_id = 'breadcrumb', $list_class = 'linkpva-breadcrumb', $active_class = 'active', $echo = true)
    {

        $svg_icon = '<i class="bi bi-chevron-right"></i>';

        // Opening
        $breadcrumb = '<' . $list_style . ' id="' . $list_id . '" class="' . $list_class . '">';

        // Home link
        if (is_front_page()) {
            $breadcrumb .= '<li class="' . $active_class . '">' . esc_html__('Home', 'linkpva') . '</li>';
        } else {
            $breadcrumb .= '<li class="breadcrumb-item"><a href="' . esc_url(home_url()) . '">' . esc_html__('Home', 'linkpva') . '</a></li>';
        }

        // Blog page setup
        $blog_page_id = get_option('page_for_posts');

        if ('page' == get_option('show_on_front') && $blog_page_id) {

            // If on blog home
            if (is_home()) {
                $breadcrumb .= '<li class="' . $active_class . '">' . $svg_icon . esc_html(get_the_title($blog_page_id)) . '</li>';
            }
            // If inside Posts archive area (category, tag, date, single post)
            elseif (is_category() || is_tag() || is_author() || is_date() || is_singular('post')) {
                $breadcrumb .= '<li class="breadcrumb-item">' . $svg_icon . '<a href="' . esc_url(get_permalink($blog_page_id)) . '">' . esc_html(get_the_title($blog_page_id)) . '</a></li>';
            }
        }

        /*
        |----------------------------------------------------------
        | Category, Tag, Author, Date ARCHIVES (SVG FIXED)
        |----------------------------------------------------------
        */
        if (is_category() || is_tag() || is_author() || is_date()) {

            $breadcrumb .= '<li class="' . $active_class . '">' . $svg_icon;

            if (is_category()) {
                $breadcrumb .= single_cat_title('', false);
            } elseif (is_tag()) {
                $breadcrumb .= single_tag_title('', false);
            } elseif (is_author()) {
                $breadcrumb .= get_the_author();
            } elseif (is_day()) {
                $breadcrumb .= get_the_time('F j, Y');
            } elseif (is_month()) {
                $breadcrumb .= get_the_time('F, Y');
            } elseif (is_year()) {
                $breadcrumb .= get_the_time('Y');
            }

            $breadcrumb .= '</li>';
        }

        /*
        |----------------------------------------------------------
        | Single Post
        |----------------------------------------------------------
        */
        if (is_singular('post')) {
            $breadcrumb .= '<li class="' . $active_class . '">' . $svg_icon . esc_html(get_the_title()) . '</li>';
        }

        /*
        |----------------------------------------------------------
        | Page (With parents)
        |----------------------------------------------------------
        */
        if (is_page() && !is_front_page()) {

            $post = get_post(get_the_ID());

            if ($post->post_parent) {
                $crumbs = [];
                $parent_id = $post->post_parent;

                while ($parent_id) {
                    $page = get_post($parent_id);
                    $crumbs[] = '<li class="breadcrumb-item">' . $svg_icon .
                        '<a href="' . esc_url(get_permalink($page->ID)) . '">' .
                        esc_html(get_the_title($page->ID)) . '</a></li>';
                    $parent_id = $page->post_parent;
                }

                $crumbs = array_reverse($crumbs);

                foreach ($crumbs as $crumb) {
                    $breadcrumb .= $crumb;
                }
            }

            $breadcrumb .= '<li class="' . $active_class . '">' . $svg_icon . esc_html(get_the_title()) . '</li>';
        }

        /*
        |----------------------------------------------------------
        | Attachment
        |----------------------------------------------------------
        */
        if (is_attachment()) {

            $parent = get_post(get_post()->post_parent);

            if ($parent) {
                $breadcrumb .= '<li class="breadcrumb-item">' . $svg_icon .
                    '<a href="' . esc_url(get_permalink($parent->ID)) . '">' .
                    esc_html(get_the_title($parent->ID)) . '</a></li>';
            }

            $breadcrumb .= '<li class="' . $active_class . '">' . $svg_icon . esc_html(get_the_title()) . '</li>';
        }

        /*
        |----------------------------------------------------------
        | SEARCH
        |----------------------------------------------------------
        */
        if (is_search()) {
            $breadcrumb .= '<li class="' . $active_class . '">' .  $svg_icon . esc_html__('Explorer Data: ', 'linkpva') . esc_html(get_search_query()) . '</li>';
        }

        /*
        |----------------------------------------------------------
        | 404
        |----------------------------------------------------------
        */
        if (is_404()) {
            $breadcrumb .= '<li class="' . $active_class . '">' . $svg_icon . esc_html__('404', 'linkpva') . '</li>';
        }

        /*
        |----------------------------------------------------------
        | Custom Post Type Archive
        |----------------------------------------------------------
        */
        if (is_post_type_archive()) {
            $breadcrumb .= '<li class="' . $active_class . '">' . $svg_icon .
                esc_html(post_type_archive_title('', false)) . '</li>';
        }

        /*
        |----------------------------------------------------------
        | Custom Taxonomy (SVG fixed)
        |----------------------------------------------------------
        */
        if (is_tax()) {

            $term = get_queried_object();
            $taxonomy = $term->taxonomy;

            $cpt = get_taxonomy($taxonomy)->object_type[0];

            if ($cpt && get_post_type_archive_link($cpt)) {

                $cpt_obj = get_post_type_object($cpt);

                $breadcrumb .= '<li class="breadcrumb-item">' . $svg_icon .
                    '<a href="' . esc_url(get_post_type_archive_link($cpt)) . '">' .
                    esc_html($cpt_obj->labels->name) . '</a></li>';
            }

            $breadcrumb .= '<li class="' . $active_class . '">' . $svg_icon . esc_html($term->name) . '</li>';
        }

        /*
        |----------------------------------------------------------
        | Custom Post Type Single
        |----------------------------------------------------------
        */
        if (is_single() && !is_singular('post') && !is_attachment()) {

            $cpt = get_post_type();
            $cpt_obj = get_post_type_object($cpt);

            if ($cpt_obj && get_post_type_archive_link($cpt)) {
                $breadcrumb .= '<li class="breadcrumb-item">' . $svg_icon .
                    '<a href="' . esc_url(get_post_type_archive_link($cpt)) . '">' .
                    esc_html($cpt_obj->labels->name) . '</a></li>';
            }

            $breadcrumb .= '<li class="' . $active_class . '">' . $svg_icon . esc_html(get_the_title()) . '</li>';
        }

        $breadcrumb .= '</' . $list_style . '>';

        if ($echo) {
            echo sprintf(__("%s", 'linkpva'), $breadcrumb);
        } else {
            return $breadcrumb;
        }
    }
}
