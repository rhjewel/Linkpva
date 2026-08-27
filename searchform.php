<?php

/**
 * The template for displaying search forms
 *
 * @package linkpva
 */
?>

<form method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>" role="search">
    <div class="search-box">
        <input type="text" id="s" name="s" placeholder="<?php echo esc_attr__('Search service, industry & more', 'linkpva') ?>">
        <button type="submit"><i class="bx bx-search"></i></button>
    </div>
</form>