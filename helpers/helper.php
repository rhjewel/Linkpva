<?php

namespace Egns\Helper;

use Egns_Helpers as GlobalEgns_Helpers;
use Elementor\Plugin;

if (!class_exists('Egns_Helper')) {

	/**
	 * Helper handlers class
	 */
	class Egns_Helper
	{

		/**
		 * Helper Class constructor
		 */
		function __construct()
		{
			// Before, After page load
			$this->actions();

			// Fire hook to include main header template
			add_action('egns_action_page_header_template', array($this, 'egns_load_page_header'));

			// Fire hook to include main footer template
			add_action('egns_action_page_footer_template', array($this, 'egns_load_page_footer'));
		}

		public function egns_load_page_header()
		{
			// Include header template
			echo apply_filters('egns_filter_header_template', self::egns_header_template());
		}


		public function egns_load_page_footer()
		{
			// Include Footer template
			echo apply_filters('egns_filter_footer_template', self::egns_footer_template());
		}


		/**
		 * Method that echo module template part.
		 *
		 * @param string $module name of the module from inc folder
		 * @param string $template full path of the template to load
		 * @param string $slug
		 * @param array  $params array of parameters to pass to template
		 */
		public static function egns_template_part($module, $template, $slug = '', $params = array())
		{
			echo self::egns_get_template_part($module, $template, $slug, $params);
		}

		/**
		 * Method that load module template part.
		 *
		 * @param string $module name of the module from inc folder
		 * @param string $template full path of the template to load
		 * @param string $slug
		 * @param array  $params array of parameters to pass to template
		 *
		 * @return string - string containing html of template
		 */
		public static function egns_get_template_part($module, $template, $slug = '', $params = array())
		{

			//HTML Content from template
			$html          = '';
			$template_path = EGNS_INC_ROOT_DIR . '/' . $module;

			$temp = $template_path . '/' . $template;
			if (is_array($params) && count($params)) {
				extract($params);
			}

			$template = '';

			if (!empty($temp)) {
				if (!empty($slug)) {
					$template = "{$temp}-{$slug}.php";

					if (!file_exists($template)) {
						$template = $temp . '.php';
					}
				} else {
					$template = $temp . '.php';
				}
			}

			if ($template) {
				ob_start();
				include($template);
				$html = ob_get_clean();
			}

			return $html;
		}

		/**
		 * Method that check file exists or not.
		 *
		 * @param string $module name of the module from inc folder
		 * @param string $template full path of the template to load
		 * @param string $slug
		 *
		 * @return boolean - if exists then return true or false
		 */
		public static function egns_check_template_part($module, $template, $slug = '', $params = array())
		{

			//HTML Content from template
			$html          = '';
			$template_path = EGNS_INC_ROOT_DIR . '/' . $module;

			$temp = $template_path . '/' . $template;
			if (is_array($params) && count($params)) {
				extract($params);
			}

			$template = '';

			if (!empty($temp)) {
				if (!empty($slug)) {
					$template = "{$temp}-{$slug}.php";

					if (!file_exists($template)) {
						return false;
					} else {
						return true;
					}
				} else {
					$template = $temp . '.php';
					if (!file_exists($template)) {
						return false;
					} else {
						return true;
					}
				}
			}
		}


		public static function get_people_post_option()
		{
			$posts = get_posts(['post_type' => 'people', 'posts_per_page' => -1]);
			$options = [];

			foreach ($posts as $post) {
				$options[$post->ID] = get_the_title($post->ID);
			}

			return $options;
		}


		public static function get_career_post_option()
		{
			$posts = get_posts(['post_type' => 'career', 'posts_per_page' => -1]);
			$options = [];

			foreach ($posts as $post) {
				$options[$post->ID] = get_the_title($post->ID);
			}

			return $options;
		}


		public static function get_people_category_options()
		{
			$terms = get_terms([
				'taxonomy' => 'people-category',
				'hide_empty' => false,
			]);

			$options = [];

			if (!is_wp_error($terms)) {
				foreach ($terms as $term) {
					$options[$term->term_id] = $term->name;
				}
			}

			return $options; // Just ID => Name
		}




		/**
		 * Method that checks if forward plugin installed
		 *
		 * @param string $plugin - plugin name
		 *
		 * @return bool
		 */
		public static function egns_is_installed($plugin)
		{

			switch ($plugin) {
				case 'egns-core';
					return class_exists('Egns_Core');
					break;
				case 'woocommerce';
					return class_exists('WooCommerce');
					break;
				default:
					return false;
			}
		}


		/**
		 * Overwrite the theme option when page option is available.
		 *
		 * @param mixed $theme_option theme option value.
		 * @param mixed $page_option page option value.
		 * @since   1.0.0
		 * @return bool 
		 */
		public static function is_enabled($theme_option, $page_option)
		{

			if (class_exists('CSF')) {

				if ($theme_option == 1) {

					if ($page_option == 1) {
						return true;
					} elseif (is_singular('product') || is_singular('case-study') || is_singular('people') || is_singular('career') ||  is_singular('post') || is_single('post') || self::linkpva_is_blog_pages() || is_404()) {
						return true;
					} elseif ($theme_option == 1 && empty($page_option) && $page_option != 0) {
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
		/**
		 * Get all created menus with ID
		 *
		 * @since   1.0.0
		 */
		public static function list_all_menus()
		{
			// Get all registered menus
			$menus = get_terms('nav_menu', array('hide_empty' => true));

			// Initialize an empty array to store menu names with ID
			$menu_names = array();

			// Check if there are any menus
			if (!empty($menus)) {
				// Loop through each menu and add its name to the array
				foreach ($menus as $menu) {
					$menu_names[$menu->term_id] = $menu->name;
				}
			}

			// Return the array of menu names
			return $menu_names;
		}


		/**
		 * Display published posts related by category.
		 *
		 * @param int $post_id Current post ID.
		 * @return void
		 */
		public static function display_related_posts_by_category($post_id)
		{
			$post_id = absint($post_id);

			if (!$post_id || 'post' !== get_post_type($post_id)) {
				return;
			}

			$categories = wp_get_post_categories($post_id, array('fields' => 'ids'));

			if (empty($categories) || is_wp_error($categories)) {
				return;
			}

			$posts_per_page = absint(apply_filters('linkpva_related_posts_limit', 3, $post_id));

			if (!$posts_per_page) {
				return;
			}

			$args = array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'category__in'        => $categories,
				'post__not_in'        => array($post_id),
				'posts_per_page'      => $posts_per_page,
				'orderby'             => 'date',
				'order'               => 'DESC',
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			);

			$args          = apply_filters('linkpva_related_posts_query_args', $args, $post_id, $categories);
			$related_posts = new \WP_Query($args);

			if (!$related_posts->have_posts()) {
				return;
			}

			$posts_page_id = absint(get_option('page_for_posts'));
			$blog_url      = $posts_page_id ? get_permalink($posts_page_id) : home_url('/');
			$blog_url      = $blog_url ?: home_url('/');
			$heading_id    = 'linkpva-related-posts-heading-' . $post_id;
?>
			<section class="linkpva-section linkpva-surface-section" aria-labelledby="<?php echo esc_attr($heading_id); ?>">
				<div class="container">
					<div class="linkpva-heading-row">
						<div class="linkpva-section-heading">
							<span class="linkpva-section-tag"><?php echo esc_html__('Continue Reading', 'linkpva'); ?></span>
							<h2 id="<?php echo esc_attr($heading_id); ?>"><?php echo esc_html__('Related Articles', 'linkpva'); ?></h2>
						</div>
						<a class="linkpva-text-link" href="<?php echo esc_url($blog_url); ?>">
							<?php echo esc_html__('All articles', 'linkpva'); ?> <i class="bi bi-arrow-right" aria-hidden="true"></i>
						</a>
					</div>
					<div class="row g-4">
						<?php while ($related_posts->have_posts()) : ?>
							<?php
							$related_posts->the_post();
							self::egns_template_part('blog', 'templates/grid/post/post', 'default');
							?>
						<?php endwhile; ?>
					</div>
				</div>
			</section>
			<?php
			wp_reset_postdata();
		}


		/**
		 * Return Career Page Option Value Based on Given Page Option ID.
		 *
		 * @since 1.0.0
		 * @param string $key1 meta key
		 * @param string $key2 meta key
		 * @param string $default meta key
		 * @return mixed Page Option Value.
		 */
		public static function  egns_career_option_value($key1, $key2 = '', $default = '')
		{

			$page_options = get_post_meta(get_the_ID(), 'EGNS_CAREER_META_ID', true);

			if (isset($page_options[$key1][$key2])) {
				return $page_options[$key1][$key2];
			} else {
				if (isset($page_options[$key1])) {
					return  $page_options[$key1];
				} else {
					return $default;
				}
			}
		}


		/**
		 * @param string $string clean special chars, spaces with hyphens
		 *
		 * @since   1.0.0
		 */
		public static function clean($string)
		{
			$string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
			$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);  // Removes special chars.

			return preg_replace('/-+/', '', $string);  // Replaces multiple hyphens with single one.
		}

		/**
		 * Get first category with link
		 *
		 * @since   1.0.0
		 */
		public static function the_first_category()
		{
			$categories = get_the_category();
			if (!empty($categories)) {
				echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
			}
		}

		/**
		 * Option Dynamic Styles (Header)
		 *
		 * @since   1.0.0
		 */
		public function egns_header_template()
		{

			$get_header_style        = self::egns_get_theme_option('header_menu_style');
			$get_page_header_style   = self::egns_page_option_value('page_header_menu_style');
			$page_main_header_enable = self::egns_page_option_value('page_main_header_enable');

			// Get page header layout
			if (!empty($page_main_header_enable) && ($page_main_header_enable == 'disable') && class_exists('CSF')) {
				$get_header_style = 'no_header';
			} else {
				if (!empty($get_page_header_style) && $get_page_header_style == 'header_one' && class_exists('CSF')) {
					$get_header_style = 'header_one';
				}
				if (!empty($get_page_header_style) && $get_page_header_style == 'header_two' && class_exists('CSF')) {
					$get_header_style = 'header_two';
				}
				if (!empty($get_page_header_style) && $get_page_header_style == 'header_three' && class_exists('CSF')) {
					$get_header_style = 'header_three';
				}
			}

			switch ($get_header_style) {
				case 'header_one':
					get_template_part('inc/header/templates/parts/header_one');
					break;
				case 'header_two':
					get_template_part('inc/header/templates/parts/header_two');
					break;
				case 'header_three':
					get_template_part('inc/header/templates/parts/header_three');
					break;
				case 'no_header':
					break;
				default:
					get_template_part('inc/header/templates/parts/header_one');
					break;
			}
		}



		/**
		 * Option Dynamic Styles (Footer)
		 *
		 * @since   1.2.0
		 */
		public function egns_footer_template()
		{
			$get_footer_style 	  	 	= self::egns_get_theme_option('footer_layout_style');
			$get_page_footer_style 		= self::egns_page_option_value('page_footer_layout');
			$page_main_footer_enable 	= self::egns_page_option_value('page_main_footer_enable');

			// Page Footer Layout
			if (!empty($page_main_footer_enable) && ($page_main_footer_enable == 'disable') && class_exists('CSF')) {
				$get_footer_style = 'no_footer';
			} else {
				if (!empty($get_page_footer_style) && $get_page_footer_style == 'footer_one' && class_exists('CSF')) {
					$get_footer_style = 'footer_one';
				}
				if (!empty($get_page_footer_style) && $get_page_footer_style == 'footer_two' && class_exists('CSF')) {
					$get_footer_style = 'footer_two';
				}
				if (!empty($get_page_footer_style) && $get_page_footer_style == 'footer_three' && class_exists('CSF')) {
					$get_footer_style = 'footer_three';
				}
			}

			switch ($get_footer_style) {
				case 'footer_one':
					get_template_part('inc/footer/templates/parts/footer_one');
					break;
				case 'footer_two':
					get_template_part('inc/footer/templates/parts/footer_two');
					break;
				case 'footer_three':
					get_template_part('inc/footer/templates/parts/footer_three');
					break;
				case 'no_footer':
					break;
				default:
					get_template_part('inc/footer/templates/parts/footer_one');
					break;
			}
		}


		/**
		 * Is Pages
		 *
		 * @since   1.0.0
		 */
		public static function egns_is_blog_pages()
		{
			return ((((is_search()) || (is_404()) || is_archive()) || (is_single()) || (is_singular())  ||  (is_author()) || (is_category()) || (is_home()) || (is_tag()))) ? true : false;
		}

		/**
		 * Is Blog Pages
		 *
		 * @since   1.2.0
		 */
		public static function linkpva_is_blog_pages()
		{
			return ((((is_search()) || is_archive()) ||  (is_author()) || (is_category()) || (is_home())  || (is_tag()))) ? true : false;
		}

		/**
		 * Get theme options.
		 *
		 * @param string $key Required. Option name.
		 * @param string $key2 Required. Option key.
		 * @param string $default Optional. Default value.
		 * @since   1.0.0
		 */

		public static function egns_get_theme_option($key, $key2 = '', $default = '')
		{
			$egns_theme_options = get_option('egns_theme_options');

			if (!empty($key2)) {
				return isset($egns_theme_options[$key][$key2]) ? $egns_theme_options[$key][$key2] : $default;
			} else {
				return isset($egns_theme_options[$key]) ? $egns_theme_options[$key] : $default;
			}
		}

		/**
		 * Css Minifier.
		 * @param mixed $css get css
		 * @since 1.0.0
		 */
		public static function cssminifier($css)
		{
			$css = str_replace(
				["\r\n", "\r", "\n", "\t", '    '],
				'',
				preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', trim($css))
			);
			return str_replace(
				['{ ', ' }', ' {', '} ', ' screen and '],
				['{', '}', '{', '}', ''],
				$css
			);
		}

		/**
		 * Return Page Option Value Based on Given Page Option ID.
		 *
		 * @since 1.0.0
		 * @param string $key1 Required. Option name.
		 * @param string $key2 Required. Option key.
		 * @param string $default Optional. Default value.
		 * 
		 * @return mixed Page Option Value.
		 */
		public static function  egns_page_option_value($key1, $key2 = '', $default = '')
		{

			$page_options = get_post_meta(get_the_ID(), 'egns_page_options', true);

			if (isset($page_options[$key1][$key2])) {

				return $page_options[$key1][$key2];
			} else {
				if (isset($page_options[$key1])) {

					return  $page_options[$key1];
				} else {
					return $default;
				}
			}
		}


		/**
		 * Return post Option Value Based on Given post Option ID.
		 * @since 1.0.0
		 * @param string $key1 post Option id. By Default It will return all value.
		 * @param string $key2 post Option id. By Default It will return all value.
		 * @param string $default post Option id. By Default It will return all value.
		 * @return mixed post Option Value.
		 */
		public static function  egns_post_option_value($key1, $key2 = '', $default = '')
		{

			$post_options = get_post_meta(get_the_ID(), 'egns_post_options', true);

			if (isset($post_options[$key1][$key2])) {

				return $post_options[$key1][$key2];
			} else {
				if (isset($post_options[$key1])) {

					return  $post_options[$key1];
				} else {
					return $default;
				}
			}
		}

		/**
		 * Return people Option Value Based on Given people Option ID.
		 * @since 1.0.0
		 * @param string $key1 people Option id. By Default It will return all value.
		 * @param string $key2 people Option id. By Default It will return all value.
		 * @param string $default people Option id. By Default It will return all value.
		 * @return mixed people Option Value.
		 */
		public static function  egns_people_option_value($key1, $key2 = '', $default = '')
		{
			$people_options = get_post_meta(get_the_ID(), 'EGNS_PEOPLE_META_ID', true);
			if (isset($people_options[$key1][$key2])) {
				return $people_options[$key1][$key2];
			} else {
				if (isset($people_options[$key1])) {
					return  $people_options[$key1];
				} else {
					return $default;
				}
			}
		}


		/**
		 * Return casestudy Option Value Based on Given casestudy Option ID.
		 *
		 * @since 1.0.0
		 *
		 * @param string $casestudy_option_key Optional. casestudy Option id. By Default It will return all value.
		 * 
		 * @return mixed casestudy Option Value.
		 */
		public static function  egns_case_study_option_value($key1, $key2 = '', $default = '')
		{

			$casestudy_options = get_post_meta(get_the_ID(), 'EGNS_CASESTUDY_META_ID', true);

			if (isset($casestudy_options[$key1][$key2])) {

				return $casestudy_options[$key1][$key2];
			} else {
				if (isset($casestudy_options[$key1])) {

					return  $casestudy_options[$key1];
				} else {
					return $default;
				}
			}
		}


		/**
		 * Get Blog layout
		 *
		 * @since   1.0.0
		 */
		public static function egns_post_layout()
		{
			$egns_theme_options = get_option('egns_theme_options');

			$blog_layout = !empty($egns_theme_options['blog_layout_options']) ? $egns_theme_options['blog_layout_options'] : 'default';

			return $blog_layout;
		}

		/**
		 * Escape any String with Translation
		 *
		 * @since   1.0.0
		 */

		public static function egns_translate($value)
		{
			echo sprintf(__('%s', 'linkpva'), $value);
		}
		/**
		 * Escape any String with Translation
		 *
		 * @since   1.0.0
		 */

		public static function egns_translate_with_escape_($value)
		{
			$value = esc_html($value);
			echo sprintf(__('%s', 'linkpva'), $value);
		}

		/**
		 * Dynamic blog layout for post archive pages.
		 *
		 * @since   1.0.0
		 */
		public static function egns_dynamic_blog_layout()
		{
			$blog_layout = self::egns_post_layout();
			if (!empty($blog_layout)) {
				if ('default' == $blog_layout) {
					get_template_part('template-parts/blog/blog-standard');
				} elseif ($blog_layout == 'layout-01') {
					get_template_part('template-parts/blog/blog-grid-sidebar');
				}
			} else {
				get_template_part('template-parts/blog/blog-standard');
			}
		}

		/**
		 * 
		 * @return string audio url for audio post
		 */
		public static function egns_embeded_audio($width, $height)
		{
			$url = esc_url(get_post_meta(get_the_ID(), 'egns_audio_url', 1));
			if (!empty($url)) {
				return '<div class="post-media">' . wp_oembed_get($url, array('width' => $width, 'height' => $height)) . '</div>';
			}
		}

		/**
		 * @return string Checks For Embed Audio In The Post.
		 */
		public static function egns_has_embeded_audio()
		{
			$url = esc_url(get_post_meta(get_the_ID(), 'egns_audio_url', 1));
			if (!empty($url)) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Post Meta Box Key Information
		 *
		 * @param  String $meta_key
		 */

		public static function egns_post_meta_box_value($meta_key, $meta_key_value)
		{
			return get_post_meta(get_the_ID(), $meta_key, true)[$meta_key_value];
		}

		/**
		 * Calculate the estimated reading time.
		 *
		 * @param string $content Main content.
		 * @param array  $extra_contents Additional content to include.
		 * @return int Reading time in minutes.
		 */
		public static function calculate_reading_time($content, $extra_contents = array())
		{
			$all_content = (string) $content;

			if (!empty($extra_contents) && is_array($extra_contents)) {
				foreach ($extra_contents as $item) {
					if (is_scalar($item)) {
						$all_content .= ' ' . $item;
					}
				}
			}

			$plain_text = strip_shortcodes($all_content);
			$plain_text = wp_strip_all_tags($plain_text, true);
			$plain_text = html_entity_decode($plain_text, ENT_QUOTES, get_bloginfo('charset'));
			$word_count = preg_match_all("/[\p{L}\p{M}\p{N}]+(?:['’][\p{L}\p{M}\p{N}]+)*/u", $plain_text, $words);
			$word_count = false === $word_count ? 0 : $word_count;

			$words_per_minute = absint(apply_filters('linkpva_reading_words_per_minute', 200));
			$words_per_minute = max(1, $words_per_minute);

			return max(1, (int) ceil($word_count / $words_per_minute));
		}

		/**
		 * Add unique anchors to article headings and prepare table of contents data.
		 *
		 * @param string $content Filtered post content.
		 * @return array Prepared content and headings.
		 */
		public static function prepare_post_content_with_toc($content)
		{
			$headings = array();
			$used_ids = array();

			$prepared_content = preg_replace_callback('/<h([2-3])([^>]*)>(.*?)<\/h\1>/is', function ($matches) use (&$headings, &$used_ids) {
				$level        = absint($matches[1]);
				$attributes   = $matches[2];
				$heading_html = $matches[3];
				$heading_text = trim(wp_strip_all_tags(html_entity_decode($heading_html, ENT_QUOTES, get_bloginfo('charset'))));

				if ('' === $heading_text) {
					return $matches[0];
				}

				$heading_id = '';

				if (preg_match('/\sid\s*=\s*(["\'])(.*?)\1/i', $attributes, $id_match)) {
					$heading_id = sanitize_title($id_match[2]);
				}

				$heading_id = '' !== $heading_id ? $heading_id : sanitize_title($heading_text);
				$heading_id = '' !== $heading_id ? $heading_id : 'article-section';
				$base_id    = $heading_id;
				$suffix     = 2;

				while (isset($used_ids[$heading_id])) {
					$heading_id = $base_id . '-' . $suffix;
					$suffix++;
				}

				$used_ids[$heading_id] = true;
				$attributes            = preg_replace('/\s+id\s*=\s*(["\']).*?\1/i', '', $attributes);
				$headings[]             = array(
					'id'    => $heading_id,
					'level' => $level,
					'title' => $heading_text,
				);

				return '<h' . $level . $attributes . ' id="' . esc_attr($heading_id) . '">' . $heading_html . '</h' . $level . '>';
			}, (string) $content);

			return array(
				'content'  => null !== $prepared_content ? $prepared_content : $content,
				'headings' => $headings,
			);
		}


		/**
		 * @return [string] Embed gallery for the post.
		 */
		public static function egns_gallery_images()
		{
			$images = get_post_meta(get_the_ID(), 'egns_gallery_images', 1);

			$images = explode(',', $images);
			if ($images && count($images) > 1) {
				$gallery_slide  = '<div class="swiper blog-archive-slider">';
				$gallery_slide .= '<div class="swiper-wrapper">';
				foreach ($images as $image) {
					$gallery_slide .= '<div class="swiper-slide"><a href="' . get_the_permalink() . '"><img src="' . wp_get_attachment_image_url($image, 'full') . '" alt="' . esc_attr(get_the_title()) . '"></a></div>';
				}
				$gallery_slide .= '</div>';
				$gallery_slide .= '</div>';

				$gallery_slide .= '<div class="slider-arrows arrows-style-2 sibling-3 text-center d-flex flex-row justify-content-between align-items-center w-100">';
				$gallery_slide .= '<div class="blog1-prev swiper-prev-arrow" tabindex="0" role="button" aria-label="' . esc_html('Previous slide') . '"> <i class="bi bi-arrow-left"></i> </div>';

				$gallery_slide .= '<div class="blog1-next swiper-next-arrow" tabindex="0" role="button" aria-label="' . esc_html('Next slide') . '"><i class="bi bi-arrow-right"></i></div>';
				$gallery_slide .= '</div>';

				return $gallery_slide;
			}
		}

		/**
		 * @return [string] Has Gallery for Gallery post.
		 */
		public static function has_egns_gallery()
		{
			$images = get_post_meta(get_the_ID(), 'egns_gallery_images', 1);
			if (!empty($images)) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * @return string get the attachment image.
		 */
		public static function egns_thumb_image()
		{
			$image = get_post_meta(get_the_ID(), 'egns_thumb_images', 1);
			echo '<a href="' . get_the_permalink() . '"><img src="' . isset($image['url']) . '" alt="' . esc_attr(get_the_title()) . ' "class="img-fluid wp-post-image"></a>';
		}

		/**
		 * @return [quote] text for quote post
		 */
		public static function egns_quote_content()
		{
			$text = get_post_meta(get_the_ID(), 'egns_quote_text', 1);
			if (!empty($text)) {
				return sprintf(esc_attr__("%s", 'linkpva'), $text);
			}
		}

		/**
		 * @return [string] video url for video post
		 */
		public static function egns_embeded_video($width = '', $height = '')
		{
			$url = esc_url(get_post_meta(get_the_ID(), 'egns_video_url', 1));
			if (!empty($url)) {
				return wp_oembed_get($url, array('width' => $width, 'height' => $height));
			}
		}

		/**
		 * @return [string] Has embed video for video post.
		 */
		public static function has_egns_embeded_video()
		{
			$url = esc_url(get_post_meta(get_the_ID(), 'egns_video_url', 1));
			if (!empty($url)) {
				return true;
			} else {
				return false;
			}
		}


		public static function get_theme_logo($logo_url, $echo = true)
		{
			if (has_custom_logo()):
				the_custom_logo();

			elseif (!empty($logo_url)):
			?>
				<?php if (!empty($logo_url)): ?>
					<a href="<?php echo esc_url(home_url('/')); ?>">
						<img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
					</a>
				<?php endif ?>

				<?php
			else : {
				?>
					<div class="site-title">
						<h3><a href="<?php echo esc_url(home_url('/')) ?>"><?php echo esc_html(get_bloginfo('name')); ?></a></h3>
					</div>

				<?php
				}
			endif;
		}


		public static function get_copyright_theme_logo($logo_url, $echo = true)
		{
			if (has_custom_logo()):
				the_custom_logo();
			elseif (!empty($logo_url)):
				?>
				<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
					<?php if (!empty($logo_url)): ?>
						<img class="img-fluid" src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"></a>
			<?php endif ?>
			<?php
			endif;
		}

		/**
		 * Menu links.
		 *
		 * @param string $theme_location menu type.
		 * @param string $container_class main class.
		 * @param string $after icon tag.
		 * @param string $link_before icon tag.
		 * @param string $link_after icon tag.
		 * @param string $menu_class .
		 * @param string $depth.
		 * @since 1.0.0
		 */
		public static function egns_get_theme_menu($theme_location = 'primary-menu', $container_class = '', $link_before = '', $link_after = '', $after = '<i class="bi bi-plus dropdown-icon"></i>', $menu_class = 'menu-list', $depth = 3, $echo = true)
		{
			if (has_nav_menu($theme_location)) {
				wp_nav_menu(
					array(
						'theme_location'  => $theme_location,
						'container'       => false, // This will prevent any container div from being added
						'container_class' => $container_class,
						'link_before'     => $link_before . '<span>', // Add opening span tag
						'link_after'      => '</span>' . $link_after, // Add closing span tag
						'after'           => $after,
						'container_id'    => '',
						'menu_class'      => $menu_class,
						'fallback_cb'     => '',
						'menu_id'         => '',
						'depth'           => $depth,
						// Conditionally add the walker
						'walker'          => class_exists('CSF') ? new \Egns_Menu_Walker() : null,
					)
				);
			} else {
				if (is_user_logged_in()) { ?>
				<div class="set-menu">
					<h4>
						<a href="<?php echo admin_url(); ?>nav-menus.php">
							<?php echo esc_html__('Set Menu Here...', 'linkpva'); ?>
						</a>
					</h4>
				</div>
			<?php }
			}
		}


		/**
		 * Output WordPress theme menu with custom parameters.
		 *
		 * @param string $theme_location  Menu location slug (registered in theme).
		 * @param string $container_class Class for the container.
		 * @param string $link_after      HTML to add after each menu link.
		 * @param string $after           HTML to add after each menu item.
		 * @param string $menu_class      Class for the <ul> element.
		 * @param int    $depth           Depth of menu levels.
		 * @param bool   $echo            Whether to echo the menu.
		 *
		 * @since 1.0.0
		 */
		public static function egns_get_theme_menu_two(
			$theme_location = 'primary-menu',
			$container_class = '',
			$link_after = '',
			$after = '<span class="dropdown-icon2"><i class="bi bi-plus"></i></span>',
			$menu_class = 'main-menu',
			$depth = 3,
			$echo = true
		) {
			if (has_nav_menu($theme_location)) {
				$args = array(
					'theme_location'  => $theme_location,
					'container'       => false,
					'container_class' => $container_class,
					'link_before'     => '',
					'link_after'      => $link_after,
					'after'           => $after,
					'container_id'    => '',
					'menu_class'      => $menu_class,
					'fallback_cb'     => '',
					'menu_id'         => '',
					'depth'           => $depth,
					'walker'          => class_exists('CSF') ? new \Egns_Menu_Walker() : null,
					'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
					'echo'            => $echo,
				);
				wp_nav_menu($args);
			} else {
				if (is_user_logged_in() && $echo) {
					echo '<div class="set-menu">';
					echo '<h4><a href="' . esc_url(admin_url('nav-menus.php')) . '">';
					echo esc_html__('Set Menu Here...', 'linkpva');
					echo '</a></h4>';
					echo '</div>';
				}
			}
		}


		/**
		 * Display theme logo with support for:
		 * - WordPress Custom Logo (Customizer)
		 * - Light/Dark fallback logos
		 * - Text fallback (site title)
		 *
		 * @param string $light_logo URL for light mode logo
		 * @param string $dark_logo  URL for dark mode logo
		 * @param bool   $echo       Echo or return HTML
		 *
		 * @return string|null
		 */
		public static function get_theme_dark_light_logo($light_logo = '', $dark_logo = '', $echo = true)
		{
			// Start output buffering so we can echo or return clean HTML
			ob_start();

			// 1. If WordPress Custom Logo exists (set from Customizer)
			if (has_custom_logo()) {

				// Output the custom logo (WP function)
				the_custom_logo();

				// 2. If there is no WP custom logo but fallback logos are provided
			} elseif (!empty($light_logo) || !empty($dark_logo)) { ?>

			<a class="mobile-logo-wrap" href="<?php echo esc_url(home_url('/')); ?>">
				<?php if (!empty($light_logo)): ?>
					<img src="<?php echo esc_url($light_logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="light">
				<?php endif; ?>
				<?php if (!empty($dark_logo)): ?>
					<img src="<?php echo esc_url($dark_logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="dark">
				<?php endif; ?>
			</a>

		<?php
				// 3. Final fallback — if no custom or image logo exists
			} else { ?>

			<!-- Text-based logo fallback using site title -->
			<div class="site-title">
				<h3>
					<a href="<?php echo esc_url(home_url('/')); ?>">
						<?php echo esc_html(get_bloginfo('name')); ?>
					</a>
				</h3>
			</div>

			<?php }

			// Capture the buffered output
			$output = ob_get_clean();

			// Echo or return the output based on parameter
			if ($echo) {
				echo sprintf('%s', $output);
			}

			return $output;
		}


		/**
		 * Sanitize SVG content safely using a controlled list of allowed tags & attributes.
		 *
		 * @param string $svg Raw SVG markup.
		 * @return string Sanitized SVG markup.
		 */
		public static function sanitize_svg($svg)
		{

			$allowed_svg_elements = [
				'svg' => [
					'class'          => true,
					'aria-hidden'    => true,
					'role'           => true,
					'xmlns'          => true,
					'width'          => true,
					'height'         => true,
					'viewBox'        => true,
					'fill'           => true,
					'stroke'         => true,
					'stroke-width'   => true,
					'preserveAspectRatio' => true,
				],
				'g' => [
					'fill'           => true,
					'stroke'         => true,
					'stroke-width'   => true,
					'transform'      => true,
				],
				'path' => [
					'd'             => true,
					'fill'          => true,
					'stroke'        => true,
					'stroke-width'  => true,
				],
				'circle' => [
					'cx'            => true,
					'cy'            => true,
					'r'             => true,
					'fill'          => true,
					'stroke'        => true,
				],
				'rect' => [
					'x'             => true,
					'y'             => true,
					'width'         => true,
					'height'        => true,
					'rx'            => true,
					'ry'            => true,
					'fill'          => true,
					'stroke'        => true,
				],
				'line' => [
					'x1'            => true,
					'y1'            => true,
					'x2'            => true,
					'y2'            => true,
					'stroke'        => true,
				],
				'polyline' => [
					'points'        => true,
					'fill'          => true,
					'stroke'        => true,
				],
				'polygon' => [
					'points'        => true,
					'fill'          => true,
					'stroke'        => true,
				],
				'title' => [],
				'desc' => [],
			];

			return wp_kses($svg, $allowed_svg_elements);
		}



		/**
		 * Displays SVG content or Image.
		 * @since 1.0.0
		 * @param string $file_url The URL of the SVG file.
		 * @param object $filesystem Optional. The filesystem object. Defaults to null.
		 * @param array $attrs set image tag class or alt
		 */

		public static function display_image($file_url, $filesystem = null, $attrs = [])
		{
			if (empty($file_url)) {
				return;
			}

			// Detect file extension
			$file_ext = strtolower(pathinfo(parse_url($file_url, PHP_URL_PATH), PATHINFO_EXTENSION));

			// Allowed image types
			$raster_images = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

			/**
			 * =====================
			 * SVG HANDLING
			 * =====================
			 */
			if ($file_ext === 'svg') {

				if (is_null($filesystem) && function_exists('WP_Filesystem')) {
					$filesystem = $GLOBALS['wp_filesystem'] ?? null;
				} elseif (is_null($filesystem)) {
					include_once ABSPATH . 'wp-admin/includes/file.php';
					WP_Filesystem();
					$filesystem = $GLOBALS['wp_filesystem'] ?? null;
				}

				$file_contents = '';

				if ($filesystem && $filesystem->exists($file_url)) {
					$file_contents = $filesystem->get_contents($file_url);
				} else {
					$response = wp_remote_get($file_url);
					if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
						$file_contents = wp_remote_retrieve_body($response);
					}
				}

				if (!empty($file_contents)) {
					echo  self::sanitize_svg($file_contents);
				}

				return;
			}

			/**
			 * =====================
			 * NORMAL IMAGE HANDLING
			 * =====================
			 */
			if (in_array($file_ext, $raster_images, true)) {

				$attr_string = '';
				foreach ($attrs as $key => $value) {
					$attr_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
				}

				printf('<img src="%s"%s loading="lazy" alt="%s">', esc_url($file_url), $attr_string, esc_attr($attrs['alt'] ?? ''));
			}
		}


		/**
		 * Post Details Pagination
		 * @since   1.0.0
		 */

		public static function egns_get_post_pagination()
		{
			wp_link_pages(
				array(
					'before'         => '<ul class="page-paginations d-flex justify-content-center align-items-center">' . esc_html__('Pages: ', 'linkpva') . '<li class="page-item">',
					'after'          => '</li></ul>',
					'link_before'    => '',
					'link_after'     => '',
					'next_or_number' => 'number',
					'separator'      => '</li><li>',
					'pagelink'       => '%',
					'echo'           => 1
				)
			);
		}

		/**
		 * Show post pagination
		 * @param array $custom_query
		 */
		public static function egns_get_archive_pagination($custom_query = null)
		{
			if (is_null($custom_query)) {
				$custom_query = $GLOBALS['wp_query'] ?? null;
			}

			$big = 999999999; // dummy value for pagination base replacement
			$current_page = max(1, get_query_var('paged'));

			$pagination_links = paginate_links(array(
				'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
				'format'    => '?paged=%#%',
				'current'   => $current_page,
				'total'     => $custom_query->max_num_pages,
				'type'      => 'array',
				'prev_text' => '',
				'next_text' => '',
			));

			if ($pagination_links) {
				echo '<div class="paginations-button">';

				if ($current_page > 1) {
					echo '<a href="' . esc_url(get_pagenum_link($current_page - 1)) . '">';
			?>
				<svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
					<g>
						<path d="M7.86133 9.28516C7.14704 7.49944 3.57561 5.71373 1.43276 4.99944C3.57561 4.28516 6.7899 3.21373 7.86133 0.713728" stroke-width="1.5" stroke-linecap="round"></path>
					</g>
				</svg>
			<?php
					echo esc_html__('Prev', 'linkpva');
					echo '</a>';
				}

				echo '</div>';

				echo '<ul class="paginations">';
				foreach ($pagination_links as $link) {
					if (strpos($link, 'prev') !== false || strpos($link, 'next') !== false) {
						continue;
					}
					echo '<li class="page-item' . (strpos($link, 'current') !== false ? ' active' : '') . '">';
					echo sprintf('%s', $link); // OUTPUT FIXED HERE
					echo '</li>';
				}
				echo '</ul>';

				echo '<div class="paginations-button">';

				if ($current_page < $custom_query->max_num_pages) {
					echo '<a href="' . esc_url(get_pagenum_link($current_page + 1)) . '">';
					echo esc_html__('Next', 'linkpva');
			?>
				<svg width="10" height="10" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg">
					<g>
						<path d="M1.42969 9.28613C2.14397 7.50042 5.7154 5.7147 7.85826 5.00042C5.7154 4.28613 2.50112 3.21471 1.42969 0.714705" stroke-width="1.5" stroke-linecap="round"></path>
					</g>
				</svg>
			<?php
					echo '</a>';
				}

				echo '</div>';
			}
		}





		/**
		 * Option Dynamic Styles.
		 *
		 * @since   1.0.0
		 */
		public function egns_enqueue_scripts()
		{
			$objects = array(
				'sticky_header_enable' => self::sticky_header_enable(),
				'animation_enable'     => self::animation_enable(),
				'is_egns_core_enable'  => class_exists('CSF') ? true : false,
			);
			wp_localize_script('custom-main', 'theme_options', $objects);
		}

		public static function sticky_header_enable()
		{
			$sticky_header      = Egns_Helper::egns_get_theme_option('header_sticky_enable');
			$page_sticky_option = Egns_Helper::egns_page_option_value('sticky_header_enable');

			if (Egns_Helper::is_enabled($sticky_header, $page_sticky_option)) {
				return true;
			} else {
				return false;
			}
		}

		public static function animation_enable()
		{
			$animation_enable = Egns_Helper::egns_get_theme_option('animation_enable');

			if ($animation_enable == 1) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Get Page Options Value
		 *
		 * @since   1.0.0
		 */

		public static function egns_get_options_value($theme_option, $page_option)
		{
			if (!empty($page_option)) {
				return $page_option;
			} else {
				return $theme_option;
			}
		}



		/**
		 * Post layout for post formet.
		 *
		 * @since   1.0.0
		 */
		public static function dynamic_post_format()
		{
			$format = get_post_format(get_the_ID());

			switch ($format) {
				case 'video';
					self::egns_template_part('post-thumb', 'video');
					break;
				case 'audio';
					self::egns_template_part('post-thumb', 'audio');
					break;
				case 'gallery';
					self::egns_template_part('post-thumb', 'gallery');
					break;
				case 'quote';
					self::egns_template_part('post-thumb', 'quote');
					break;
				case 'image';
					self::egns_template_part('post-thumb', 'image');
					break;
				default:
					break;
			}
		}


		/**
		 * Define the core functionality of the.
		 *
		 * @since   1.0.0
		 */
		public function actions()
		{
			add_action('egns_page_before', array($this, 'open_container'));
			add_action('egns_page_after', array($this, 'close_container'));
			add_action('egns_post_before', array($this, 'post_open_container'));
			add_action('egns_post_after', array($this, 'post_close_container'));
			add_action('egns_header_template', array($this, 'egns_header_template'));
		}


		/**
		 * Is elementor.
		 *
		 * @since   1.0.0
		 */
		public static function is_elementor()
		{
			if (self::linkpva_is_blog_pages()) {
				return false;
			}

			if (did_action('elementor/loaded')) {
				return Plugin::$instance->documents->get(get_the_ID())->is_built_with_elementor();
			} else {
				return false;
			}
		}

		/**
		 * Open Page Container.
		 *
		 * @since   1.0.0
		 */
		public function open_container()
		{
			if (!self::is_elementor()): ?>
			<div class="container">
			<?php
			endif;
		}

		/**
		 * Close Page Container.
		 *
		 * @since   1.0.0
		 */
		public function close_container()
		{
			if (!self::is_elementor()):
			?>
			</div> <!-- End Main Content Area  -->
		<?php endif;
		}

		/**
		 * Post Open Container.
		 *
		 * @since   1.0.0
		 */
		public function post_open_container()
		{
			if (is_single()) {
		?>
			<div class="blog-details">
			<?php
			} else {
			?>
				<div>
				<?php
			}
		}

		/**
		 * Post Close Container.
		 *
		 * @since   1.0.0
		 */
		public function post_close_container()
		{
				?>
				</div>
	<?php
		}
	} // end Main Egns Helper class






}
