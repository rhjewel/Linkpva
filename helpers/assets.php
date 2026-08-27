<?php

namespace Egns\Helper;

if (!class_exists('Egns_Assets')) {

    /**
     * Assets handlers class
     */
    class Egns_Assets
    {

        /**
         * Class constructor
         */
        function __construct()
        {
            // Theme setup and admin enqueue files
            add_action('admin_enqueue_scripts', array($this, 'egns_enqueue_admin_assets'));

            // Theme setup and enqueue files
            add_action('wp_enqueue_scripts', array($this, 'egns_enqueue_assets'));
        }

        /**
         * Return all available scripts
         *
         * @version 1.0.0
         * @return array
         */
        function egns_get_scripts()
        {
            return [
                'popper' => [
                    'src'     => EGNS_ASSETS_ROOT . '/js/popper.min.js',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/js/popper.min.js'),
                    'deps'    => ['jquery'],
                ],
                'bootstrap' => [
                    'src'     => EGNS_ASSETS_ROOT . '/js/bootstrap.min.js',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/js/bootstrap.min.js'),
                    'deps'    => ['jquery'],
                ],
                'swiper' => [
                    'src'     => EGNS_ASSETS_ROOT . '/js/swiper-bundle.min.js',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/js/swiper-bundle.min.js'),
                    'deps'    => [],
                ],
                'nice-select' => [
                    'src'     => EGNS_ASSETS_ROOT . '/js/jquery.nice-select.min.js',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/js/jquery.nice-select.min.js'),
                    'deps'    => ['jquery'],
                ],
                'custom-main' => [
                    'src'     => EGNS_ASSETS_ROOT . '/js/custom.js',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/js/custom.js'),
                    'deps'    => [],
                ],

            ];
        }


        /**
         * Return all available styles
         *
         * @version 1.0.0
         * @return array
         */
        function egns_get_styles()
        {

            $styles = [
                'egns-fonts' => [
                    'src'     => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Manrope:wght@600;700;800&amp;display=swap',
                    'deps'    => [],
                    'version' => null,
                ],
                'swiper-slider' => [
                    'src'     => EGNS_ASSETS_ROOT . '/css/swiper-bundle.min.css',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/css/swiper-bundle.min.css'),
                ],
                'nice-select' => [
                    'src'     => EGNS_ASSETS_ROOT . '/css/nice-select.css',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/css/nice-select.css'),
                ],
                'bootstrap-icons' => [
                    'src'     => EGNS_ASSETS_ROOT . '/css/bootstrap-icons.css',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/css/bootstrap-icons.css'),
                ],
                'bootstrap' => [
                    'src'     => EGNS_ASSETS_ROOT . '/css/bootstrap.min.css',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/css/bootstrap.min.css'),
                ],
                'boxicons' => [
                    'src'     => EGNS_ASSETS_ROOT . '/css/boxicons.min.css',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/css/boxicons.min.css'),
                ],
                'blog-and-pages' => [
                    'src'     => EGNS_ASSETS_ROOT . '/css/blog-and-pages.css',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/css/blog-and-pages.css'),
                ],
                'woocommerce-custom' => [
                    'src'     => EGNS_ASSETS_ROOT . '/css/woocommerce-custom.css',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/css/woocommerce-custom.css'),
                ],
                'egns-style' => [
                    'src'     => EGNS_ASSETS_ROOT . '/css/style.css',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/css/style.css'),
                ],
                'egns-theme' => [
                    'src'     => EGNS_ROOT . '/style.css',
                    'version' => rand(10, 100),
                ],

            ];

            return $styles;
        }


        /**
         * Egens enqueue scripts and styles 
         * 
         * @since 1.0.0
         * 
         * @return void
         */
        public function egns_enqueue_assets()
        {
            $scripts = $this->egns_get_scripts();
            $styles  = $this->egns_get_styles();


            // Applied filter hook for scripts and styles
            $scripts = apply_filters('egns_filter_scripts', $scripts);
            $styles  = apply_filters('egns_filter_styles', $styles);

            // Enqueue all styles
            foreach ($styles as $handle => $style) {
                $deps = isset($style['deps']) ? $style['deps'] : false;

                wp_enqueue_style($handle, $style['src'], $deps, $style['version'], 'all');
            }

            // Enqueue all scripts
            foreach ($scripts as $handle => $script) {
                $deps = isset($script['deps']) ? $script['deps'] : false;

                wp_enqueue_script($handle, $script['src'], $deps, $script['version'], true);
            }

            if (is_singular() && comments_open() && get_option('thread_comments')) {
                wp_enqueue_script('comment-reply');
            }

            // Localize script 
            $objects = array(
                'sticky_header'  => class_exists('Egns_Core') ? Egns_Helper::sticky_header_enable() : '',
                'ajaxurl'        => admin_url('admin-ajax.php'),
                'posts_per_page' => get_option('posts_per_page'),
                'author_id'      => get_the_author_meta('ID'),
                'nonce'          => wp_create_nonce('ajax-nonce')
            );
            wp_localize_script('custom-main', 'localize_params', $objects);
        }


        /**
         * Return all available admin styles
         *
         * @version 1.0.0
         * @return array
         */
        function egns_get_admin_styles()
        {
            return [
                'egns-admin-style' => [
                    'src'     => EGNS_ASSETS_ROOT . '/css/admin.css',
                    'version' => filemtime(EGNS_ASSETS_ROOT_DIR . '/css/admin.css'),
                ],

            ];
        }


        /**
         * Egens enqueue admin scripts and styles 
         * 
         * @since 1.0.0
         * 
         * @return void
         */
        public function egns_enqueue_admin_assets()
        {
            $admin_styles = $this->egns_get_admin_styles();

            // Applied filter hook for styles
            $admin_styles = apply_filters('egns_filter_admin_styles', $admin_styles);

            // Enqueue all admin styles
            foreach ($admin_styles as $handle => $admin_style) {
                $deps = isset($admin_style['deps']) ? $admin_style['deps'] : false;

                wp_enqueue_style($handle, $admin_style['src'], $deps, $admin_style['version'], 'all');
            }
        }
    }
}
