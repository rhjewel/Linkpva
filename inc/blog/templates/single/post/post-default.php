<?php

use Egns\Helper\Egns_Helper;

$post_id         = get_the_ID();
$post_title      = get_the_title($post_id);
$post_format     = get_post_format($post_id) ?: 'standard';
$post_categories = get_the_category($post_id);
$post_category   = !empty($post_categories) ? $post_categories[0] : null;
$author_id       = (int) get_post_field('post_author', $post_id);
$author_name     = get_the_author_meta('display_name', $author_id);
$author_bio      = trim(wp_strip_all_tags((string) get_the_author_meta('description', $author_id)));
$author_parts    = preg_split('/\s+/u', trim($author_name), -1, PREG_SPLIT_NO_EMPTY);
$author_parts    = is_array($author_parts) ? $author_parts : array();
$initial_parts   = count($author_parts) > 1 ? array(reset($author_parts), end($author_parts)) : $author_parts;
$author_initials = '';

foreach ($initial_parts as $initial_part) {
    $author_initials .= function_exists('mb_substr') ? mb_substr($initial_part, 0, 1) : substr($initial_part, 0, 1);
}

$author_initials = function_exists('mb_strtoupper') ? mb_strtoupper($author_initials) : strtoupper($author_initials);
$reading_time    = Egns_Helper::calculate_reading_time(get_post_field('post_content', $post_id));
$post_content    = apply_filters('the_content', get_the_content());
$article_data    = Egns_Helper::prepare_post_content_with_toc($post_content);
$article_content = $article_data['content'];
$toc_headings    = $article_data['headings'];
$format_media     = '';
$gallery_ids      = array();
$quote_text       = '';

switch ($post_format) {
    case 'audio':
        $audio_url = esc_url(get_post_meta($post_id, 'egns_audio_url', true));

        if ($audio_url) {
            $format_media = wp_oembed_get($audio_url, array('width' => 1200));
            $format_media = $format_media ?: wp_audio_shortcode(array('src' => $audio_url));
        }
        break;

    case 'video':
        $video_url = esc_url(get_post_meta($post_id, 'egns_video_url', true));

        if ($video_url) {
            $format_media = wp_oembed_get($video_url, array('width' => 1200));
            $format_media = $format_media ?: wp_video_shortcode(array('src' => $video_url));
        }
        break;

    case 'gallery':
        $gallery_value = get_post_meta($post_id, 'egns_gallery_images', true);
        $gallery_ids   = is_array($gallery_value) ? $gallery_value : explode(',', (string) $gallery_value);
        $gallery_ids   = array_values(array_filter(array_map('absint', $gallery_ids)));
        $gallery_ids   = array_values(array_filter($gallery_ids, 'wp_attachment_is_image'));
        break;

    case 'image':
        $image = get_post_meta($post_id, 'egns_thumb_images', true);

        if (is_array($image) && !empty($image['id'])) {
            $format_media = wp_get_attachment_image(absint($image['id']), 'full', false, array('loading' => 'eager'));
        } elseif (is_array($image) && !empty($image['url'])) {
            $format_media = sprintf('<img src="%s" alt="%s" loading="eager">', esc_url($image['url']), esc_attr($post_title));
        }
        break;

    case 'quote':
        $quote_text = trim((string) get_post_meta($post_id, 'egns_quote_text', true));
        break;
}

?>

<div class="linkpva-inner-section">
    <div class="container">

        <?php egns_breadcrumb('ol', 'breadcrumb', 'linkpva-breadcrumb justify-content-center'); ?>

        <div class="linkpva-article-header">
            <?php if ($post_category) : ?>
                <span class="linkpva-section-tag"><?php echo esc_html($post_category->name); ?></span>
            <?php endif; ?>
            <h1><?php echo esc_html($post_title); ?></h1>
            <div class="linkpva-article-meta">
                <span><i class="bi bi-person"></i> <?php echo esc_html($author_name); ?></span>
                <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C, $post_id)); ?>"><i class="bi bi-calendar3"></i> <?php echo esc_html(get_the_date('', $post_id)); ?></time>
                <span><i class="bi bi-clock"></i> <?php echo esc_html(sprintf(__('%s min read', 'linkpva'), number_format_i18n($reading_time))); ?></span>
            </div>
        </div>

        <?php if ('gallery' === $post_format && !empty($gallery_ids)) : ?>
            <div class="linkpva-article-hero is-gallery">
                <div class="swiper blog-archive-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($gallery_ids as $gallery_id) : ?>
                            <div class="swiper-slide">
                                <?php echo wp_get_attachment_image($gallery_id, 'full', false, array('loading' => 'eager')); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php if (count($gallery_ids) > 1) : ?>
                    <div class="slider-arrows arrows-style-2 sibling-3 d-flex justify-content-between align-items-center">
                        <div class="blog1-prev swiper-prev-arrow" tabindex="0" role="button" aria-label="<?php echo esc_attr__('Previous slide', 'linkpva'); ?>">
                            <i class="bi bi-arrow-left"></i>
                        </div>
                        <div class="blog1-next swiper-next-arrow" tabindex="0" role="button" aria-label="<?php echo esc_attr__('Next slide', 'linkpva'); ?>">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif ('quote' === $post_format && $quote_text) : ?>
            <figure class="linkpva-article-hero is-quote">
                <blockquote><?php echo wp_kses_post(wpautop($quote_text)); ?></blockquote>
            </figure>
        <?php elseif (in_array($post_format, array('audio', 'video', 'image'), true) && $format_media) : ?>
            <div class="linkpva-article-hero is-<?php echo esc_attr($post_format); ?>">
                <?php
                // Media HTML is generated by WordPress from administrator-managed post format fields.
                echo $format_media; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                ?>
            </div>
        <?php elseif (has_post_thumbnail($post_id)) : ?>
            <figure class="linkpva-article-hero">
                <?php echo get_the_post_thumbnail($post_id, 'full'); ?>
            </figure>
        <?php endif; ?>

        <div class="linkpva-article-layout<?php echo empty($toc_headings) ? ' has-no-sidebar' : ''; ?>">
            <?php if (!empty($toc_headings)) : ?>
                <aside class="linkpva-toc" aria-label="<?php echo esc_attr__('Article table of contents', 'linkpva'); ?>">
                    <h2><?php echo esc_html__('On this page', 'linkpva'); ?></h2>
                    <ol>
                        <?php foreach ($toc_headings as $heading) : ?>
                            <li class="toc-level-<?php echo esc_attr($heading['level']); ?>">
                                <a href="#<?php echo esc_attr($heading['id']); ?>"><?php echo esc_html($heading['title']); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </aside>
            <?php endif; ?>
            <div class="linkpva-article-content">
                <?php
                // The content has already passed through WordPress's standard content filters.
                echo $article_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                ?>

                <?php Egns_Helper::egns_get_post_pagination(); ?>

                <div class="linkpva-author-box">
                    <span><?php echo esc_html($author_initials); ?></span>
                    <div>
                        <h2><?php echo esc_html($author_name); ?></h2>
                        <?php if ($author_bio) : ?>
                            <p><?php echo esc_html($author_bio); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (comments_open() || get_comments_number()) : ?>
                    <div class="comment-and-form-area">
                        <?php
                        //If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) {
                            comments_template();
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php Egns_Helper::display_related_posts_by_category(get_the_ID()) ?>
