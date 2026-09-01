<?php

use Egns\Helper\Egns_Helper;

$post_id         = get_the_ID();
$post_title      = get_the_title($post_id);
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


        <figure class="linkpva-article-hero">
            <?php the_post_thumbnail() ?>
        </figure>

        <div class="linkpva-article-layout">
            <aside class="linkpva-toc">
                <h2>On this page</h2>
                <ol>
                    <li><a href="#requirements">Start with requirements</a></li>
                    <li><a href="#specifications">Review specifications</a></li>
                    <li><a href="#delivery">Check delivery</a></li>
                    <li><a href="#policies">Read policies</a></li>
                </ol>
            </aside>
            <div class="linkpva-article-content">
                <p>Clear product information helps customers compare available listings without relying on broad
                    or unsupported labels. Before choosing any account listing, identify the specifications
                    relevant to your intended, lawful use and verify that the product page answers your
                    questions.</p>
                <h2 id="requirements">Start with your requirements</h2>
                <p>Begin by deciding which details actually matter. This may include account category, region,
                    age range, verification information, profile completeness, connection or follower range, and
                    delivery conditions. A more expensive option is not automatically a better fit.</p>
                <blockquote>A useful listing explains what is included, what is not included, and which
                    conditions apply before purchase.</blockquote>
                <h2 id="specifications">Review listing specifications</h2>
                <p>Compare listings using the same set of attributes. Pay particular attention to:</p>
                <ul>
                    <li>The account category and how the seller defines it</li>
                    <li>Age or creation-date range</li>
                    <li>Region and available profile information</li>
                    <li>Verification or PVA-related details</li>
                    <li>Follower or connection ranges, if relevant</li>
                    <li>Any customer requirements before delivery</li>
                </ul>
                <p>Terms such as verified or premium should be supported by specific information. They do not
                    imply platform endorsement or guarantee performance.</p>
                <h2 id="delivery">Check delivery information</h2>
                <p>Read the expected delivery method, confirmation process, and estimated timeframe. Sensitive
                    order details should never appear in a public page, URL, or analytics event. A protected
                    customer workflow is the appropriate direction for the final commerce system.</p>
                <h2 id="policies">Read policies before checkout</h2>
                <p>Review the delivery, replacement, refund, privacy, and terms pages before purchasing. Note
                    any reporting window and the information required to review a support request. Contact
                    purchase support if a specification or policy condition is unclear.</p>
                <h3>Responsible use</h3>
                <p>Customers remain responsible for ensuring that a purchase and intended use comply with
                    applicable law and relevant platform rules. LinkPVA is an independent marketplace and is not
                    affiliated with or endorsed by LinkedIn.</p>

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
                    <div class="comment-and-form-area linkpva-sec-mt">
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