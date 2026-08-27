<?php

use Egns\Inc\Blog_Helper;
?>

<div class="banner-content">
    <ul class="blog-meta">
        <li>
            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID')))  ?>"><?php echo get_the_author_meta('display_name') ?></a>
        </li>
        <li>
            <a class="publish-date" href="<?php echo esc_url(home_url(get_the_date('Y/m/d'))) ?>"><?php echo get_the_date('F d, Y'); ?></a>
        </li>
    </ul>
    <h1><?php the_title() ?></h1>
</div>