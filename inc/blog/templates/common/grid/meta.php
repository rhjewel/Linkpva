<?php

use Egns\Helper\Egns_Helper;
?>
<ul class="blog-meta">
    <li>
        <a href="<?php echo esc_url(home_url(get_the_date('Y/m/d'))) ?>"><?php echo get_the_date('d F, Y'); ?></a>
    </li>
    <li><?php echo Egns_Helper::calculate_reading_time(get_the_content()) . __(' min read', 'linkpva'); ?></li>
</ul>