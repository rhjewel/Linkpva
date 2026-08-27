<?php

use Egns\Helper\Egns_Helper;

if (! defined('ABSPATH')) {
    exit;
}

$template_id = '';

if (class_exists('Egns_Core')) {
    // Page option priority first
    $template_id = Egns_Helper::egns_page_option_value('header_one_template');

    // Then theme option
    if (empty($template_id)) {
        $template_id = Egns_Helper::egns_get_theme_option('header_one_template');
    }
}

if (! empty($template_id) && class_exists('\Egns_Core\Egns_Helper')) {
    echo \Egns_Core\Egns_Helper::get_header_data($template_id);
} else {

?>
    <header class="linkpva-header" data-header>
        <div class="container">
            <div class="linkpva-header-inner">
                <a class="linkpva-logo" href="index.html" aria-label="LinkPVA home">
                    <img src="assets/images/logo.svg" width="190" height="46" alt="LinkPVA">
                </a>

                <button class="linkpva-menu-toggle" type="button" aria-expanded="false"
                    aria-controls="primary-navigation" data-open-label="Open navigation"
                    data-close-label="Close navigation" data-menu-toggle>
                    <span class="visually-hidden">Open navigation</span>
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>

                <div class="linkpva-mobile-menu-backdrop" aria-hidden="true" data-menu-backdrop></div>

                <nav class="linkpva-primary-nav" id="primary-navigation" aria-label="Primary navigation"
                    data-mobile-menu>
                    <div class="linkpva-mobile-nav-header">
                        <a class="linkpva-logo" href="index.html" aria-label="LinkPVA home">
                            <img src="assets/images/logo.svg" width="190" height="46" alt="LinkPVA">
                        </a>
                        <button class="linkpva-mobile-menu-close" type="button" aria-label="Close navigation"
                            data-menu-close>
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                        </button>
                    </div>
                    <ul>
                        <li class="menu-item"><a class="is-active" href="index.html" aria-current="page">Home</a></li>
                        <li class="menu-item menu-item-has-children">
                            <a href="shop.html" aria-haspopup="true" aria-expanded="false">Shop</a>
                            <ul class="sub-menu">
                                <li class="menu-item"><a href="shop.html">All Products</a></li>
                                <li class="menu-item menu-item-has-children">
                                    <a href="shop.html" aria-haspopup="true" aria-expanded="false">Verified Accounts</a>
                                    <ul class="sub-menu">
                                        <li class="menu-item"><a href="shop.html">US Verified Accounts</a></li>
                                        <li class="menu-item"><a href="shop.html">UK Verified Accounts</a></li>
                                        <li class="menu-item"><a href="shop.html">EU Verified Accounts</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item menu-item-has-children">
                                    <a href="shop.html" aria-haspopup="true" aria-expanded="false">Old Accounts</a>
                                    <ul class="sub-menu">
                                        <li class="menu-item"><a href="shop.html">1–3 Years Old</a></li>
                                        <li class="menu-item"><a href="shop.html">3–5 Years Old</a></li>
                                        <li class="menu-item"><a href="shop.html">5+ Years Old</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item"><a href="shop.html">PVA Accounts</a></li>
                                <li class="menu-item"><a href="shop.html">Accounts With Followers</a></li>
                            </ul>
                        </li>
                        <li class="menu-item"><a href="index.html#account-types">Categories</a></li>
                        <li class="menu-item"><a href="pricing.html">Pricing</a></li>
                        <li class="menu-item menu-item-has-children">
                            <a href="blog.html" aria-haspopup="true" aria-expanded="false">Blog</a>
                            <ul class="sub-menu">
                                <li class="menu-item"><a href="blog.html">All Articles</a></li>
                                <li class="menu-item"><a href="blog.html">Buyer Guides</a></li>
                                <li class="menu-item"><a href="blog.html">Account Types</a></li>
                                <li class="menu-item"><a href="blog.html">Order Help</a></li>
                            </ul>
                        </li>
                        <li class="menu-item"><a href="faq.html">FAQ</a></li>
                        <li class="menu-item"><a href="contact.html">Contact</a></li>
                    </ul>
                </nav>

                <div class="linkpva-header-actions">
                    <button class="linkpva-icon-button linkpva-search-toggle-button" type="button" aria-label="Search products" data-search-toggle>
                        <i class="bi bi-search" aria-hidden="true"></i>
                    </button>
                    <a class="linkpva-icon-button" href="login.html" aria-label="My account">
                        <i class="bi bi-person" aria-hidden="true"></i>
                    </a>
                    <a class="linkpva-cart-button" href="cart.html" aria-label="Shopping cart with 0 items">
                        <i class="bi bi-bag" aria-hidden="true"></i>
                        <span class="linkpva-cart-count">0</span>
                    </a>
                </div>
            </div>

            <form class="linkpva-search-form" action="shop.html" role="search" hidden data-search-form>
                <label class="visually-hidden" for="site-search">Search account listings</label>
                <i class="bi bi-search" aria-hidden="true"></i>
                <input id="site-search" name="s" type="search" placeholder="Search account listings...">
                <button type="submit">Search</button>
            </form>
        </div>
    </header>
<?php } ?>
