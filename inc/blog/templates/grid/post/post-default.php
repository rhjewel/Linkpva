<?php
$post_id       = get_the_ID();
$permalink     = get_permalink($post_id);
$title         = get_the_title($post_id);
$excerpt       = wp_trim_words(wp_strip_all_tags(get_the_excerpt($post_id)), 24, '…');
$category_list = get_the_category($post_id);
$category      = !empty($category_list) ? $category_list[0] : null;
$category_name = $category ? $category->name : '';
$category_slug = $category ? $category->slug : 'uncategorized';
$visual_styles = array('is-blue', 'is-purple', 'is-green');
$visual_style  = $visual_styles[$category ? absint($category->term_id) % count($visual_styles) : 0];
$post_format   = get_post_format($post_id) ?: 'standard';
$format_data   = array(
    'standard' => array('icon' => 'file-earmark-text', 'label' => __('Article', 'linkpva')),
    'aside'    => array('icon' => 'card-text', 'label' => __('Aside', 'linkpva')),
    'audio'    => array('icon' => 'music-note-beamed', 'label' => __('Audio', 'linkpva')),
    'chat'     => array('icon' => 'chat-left-text', 'label' => __('Chat', 'linkpva')),
    'gallery'  => array('icon' => 'images', 'label' => __('Gallery', 'linkpva')),
    'image'    => array('icon' => 'image', 'label' => __('Image', 'linkpva')),
    'link'     => array('icon' => 'link-45deg', 'label' => __('Link', 'linkpva')),
    'quote'    => array('icon' => 'quote', 'label' => __('Quote', 'linkpva')),
    'status'   => array('icon' => 'card-text', 'label' => __('Status', 'linkpva')),
    'video'    => array('icon' => 'play-circle', 'label' => __('Video', 'linkpva')),
);
$format        = $format_data[$post_format] ?? $format_data['standard'];
?>
<div id="post-<?php echo esc_attr($post_id); ?>" <?php post_class('col-md-6 col-lg-4'); ?> data-blog-item data-category="<?php echo esc_attr($category_slug); ?>">
    <article class="linkpva-blog-card">
        <a class="linkpva-blog-visual <?php echo esc_attr($visual_style); ?>" href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr(sprintf(__('Read %s', 'linkpva'), $title)); ?>">
            <?php if (has_post_thumbnail($post_id)) : ?>
                <?php echo get_the_post_thumbnail($post_id, 'blog-grid', array('loading' => 'lazy', 'decoding' => 'async')); ?>
            <?php else : ?>
                <i class="bi bi-<?php echo esc_attr($format['icon']); ?>" aria-hidden="true"></i>
            <?php endif; ?>

            <?php if ($category_name) : ?>
                <span><?php echo esc_html($category_name); ?></span>
            <?php endif; ?>
        </a>
        <div class="linkpva-blog-body">
            <div class="linkpva-blog-meta">
                <span class="linkpva-post-format">
                    <i class="bi bi-<?php echo esc_attr($format['icon']); ?>" aria-hidden="true"></i>
                    <?php echo esc_html($format['label']); ?>
                </span>
                <?php if ($category_name) : ?>
                    <span><?php echo esc_html($category_name); ?></span>
                <?php endif; ?>
                <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C, $post_id)); ?>"><?php echo esc_html(get_the_date('', $post_id)); ?></time>
            </div>
            <h3><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h3>

            <?php if ($excerpt) : ?>
                <p><?php echo esc_html($excerpt); ?></p>
            <?php endif; ?>

            <a class="linkpva-read-more" href="<?php echo esc_url($permalink); ?>">
                <?php echo esc_html__('Read article', 'linkpva'); ?> <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </article>
</div>
