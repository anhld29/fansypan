<?php 
$brand_primary = dh_format_color(dh_get_theme_option('brand-primary','#bca480'));
$brand_primary = apply_filters('dh_brand_primary', $brand_primary);
if($brand_primary === '#bca480'){
	return '';
}
// $darken_10_brand_primary = darken(dh_format_color($brand_primary),'10%');
$fade_70_brand_primary = fade(dh_format_color($brand_primary),'70%');
?>
a:hover,
a:focus {
  color: <?php echo dh_print_string($brand_primary) ?>;
}

.fade-loading i,
.loadmore-action .loadmore-loading span {
  background: <?php echo dh_print_string($brand_primary) ?>;
}

.loadmore-action .btn-loadmore:hover,
.loadmore-action .btn-loadmore:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  background: <?php echo dh_print_string($brand_primary) ?>;
  color: <?php echo dh_print_string($brand_primary) ?> !important;
}
.text-primary {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.btn:hover, 
.btn:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.btn:hover:after, 
.btn:focus:after {
  background-color: <?php echo dh_print_string($brand_primary) ?>;
}
.readmore-link a:hover, 
.readmore-link a:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.box_border:hover {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  box-shadow: inset 0 0 0 1px <?php echo dh_print_string($brand_primary) ?>;
  -moz-box-shadow: inset 0 0 0 1px <?php echo dh_print_string($brand_primary) ?>;
  -webkit-box-shadow: inset 0 0 0 1px <?php echo dh_print_string($brand_primary) ?>;
  -ms-box-shadow: inset 0 0 0 1px <?php echo dh_print_string($brand_primary) ?>;
}
.minicart-side .minicart-footer .minicart-actions .button.checkout-button {
  background-color: <?php echo dh_print_string($brand_primary) ?>;
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.minicart-side .minicart-footer .minicart-actions .button.checkout-button:hover, 
.minicart-side .minicart-footer .minicart-actions .button.checkout-button:focus, 
.minicart-side .minicart-footer .minicart-actions .button.checkout-button:active, 
.minicart-side .minicart-footer .minicart-actions .button.checkout-button.active{
border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce #payment #place_order:hover, .woocommerce #payment #place_order:focus, .woocommerce #payment #place_order:active, .woocommerce #payment #place_order.active {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce > div .button:not(.checkout-button):after, .woocommerce .cart .button:after,
.woocommerce .widget_price_filter .price_slider_amount .button:after,
.woocommerce .shop-loop.list ul.products li.product figcaption .list-info-meta .loop-add-to-cart a:after,
.minicart-side .minicart-footer .minicart-actions .button:after,
.woocommerce .cart-collaterals .cart_totals .checkout-button:after,
.readmore-link a:after,
.woocommerce table.cart td.actions .button:after,
.woocommerce #payment #place_order:after {
  background-color: <?php echo dh_print_string($brand_primary) ?>;
}
.bg-primary {
  background-color: <?php echo dh_print_string($brand_primary) ?>;
}
.btn-primary {
  background-color: <?php echo dh_print_string($brand_primary) ?>;
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}

.navbar-default .navbar-nav > li > a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.navbar-default .navbar-nav .active > a,
.navbar-default .navbar-nav .open > a {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.navbar-default .navbar-nav > .current-menu-ancestor > a,
.navbar-default .navbar-nav > .current-menu-parent > a,
.navbar-default .navbar-nav > .current-menu-ancestor > a:hover,
.navbar-default .navbar-nav > .current-menu-parent > a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
@media (max-width: 899px) {
  .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover {
    color: <?php echo dh_print_string($brand_primary) ?>;
  }
}
@media (min-width: 900px) {
  .primary-nav > .megamenu > .dropdown-menu > li .dropdown-menu a:hover {
    color: <?php echo dh_print_string($brand_primary) ?>;
  }
  .primary-nav .dropdown-menu a:hover {
    color: <?php echo dh_print_string($brand_primary) ?>;
  }
  .header-type-center .primary-nav > li > a .navicon {
    color: <?php echo dh_print_string($brand_primary) ?>;
  }
}
.primary-nav .dropdown-menu .open > a {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.primary-nav > li.current-menu-parent > a,
.primary-nav > li.current-menu-parent > a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.navbar-search .search-form-wrap.show-popup .searchform:before {
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.cart-icon-mobile span {
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.offcanvas-nav a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.offcanvas-nav .dropdown-menu a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.breadcrumb > li a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.mejs-controls .mejs-time-rail .mejs-time-current,
.mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current {
  background: <?php echo dh_print_string($brand_primary) ?> !important;
}
.ajax-modal-result a,
.user-modal-result a {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
a[data-toggle="popover"],
a[data-toggle="tooltip"] {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.caroufredsel.product-slider.nav-position-center .product-slider-title ~ .caroufredsel-wrap .caroufredsel-next:hover:after,
.caroufredsel.product-slider.nav-position-center .product-slider-title ~ .caroufredsel-wrap .caroufredsel-prev:hover:after {
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.caroufredsel .caroufredsel-wrap .caroufredsel-next:hover,
.caroufredsel .caroufredsel-wrap .caroufredsel-prev:hover {
  background-color: <?php echo dh_print_string($brand_primary) ?>;
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.topbar {
  background: <?php echo dh_print_string($brand_primary) ?>;
}

.topbar-social a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.navbar-header-left .social a i:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.navbar-header-right > div a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.header-type-classic .header-right > div .minicart-link:hover,
.header-type-classic .header-right > div a.wishlist:hover,
.header-type-classic .header-right > div .navbar-search-button:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.footer-widget .social-widget-wrap a:hover i {
  color: <?php echo dh_print_string($brand_primary) ?> !important;
}
.entry-format {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.sticky .entry-title:before {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.entry-meta a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.post-navigation a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.author-info .author-social a:hover {
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.comment-author a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.comment-reply-link {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
#review_form .form-submit input[type="submit"],
.comment-form .form-submit input[type="submit"] {
  background-color: <?php echo dh_print_string($brand_primary) ?>;
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}
#review_form .form-submit button[type="submit"]::after, 
.comment-form .form-submit button[type="submit"]::after{
 background-color: <?php echo dh_print_string($brand_primary) ?>;
}
#review_form .form-submit button[type="submit"]:hover, .comment-form .form-submit button[type="submit"]:hover, #review_form .form-submit button[type="submit"]:focus, .comment-form .form-submit button[type="submit"]:focus{
 border-color: <?php echo dh_print_string($brand_primary) ?>;
}
#wp-calendar > tbody > tr > td > a {
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.recent-tweets ul li a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.widget-post-thumbnail li .posts-thumbnail-content .posts-thumbnail-meta a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
<?php if(defined('WOOCOMMERCE_VERSION')):?>
.woocommerce-account .woocommerce .button:hover,
.woocommerce-account .woocommerce .button:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce .return-to-shop .button:hover,
.woocommerce .return-to-shop .button:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce div.product-quickview-content div.summary .share-links .share-icons a:hover,
.woocommerce div.product div.summary .share-links .share-icons a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce div.product-quickview-content form.cart .button:hover,
.woocommerce div.product form.cart .button:hover,
.woocommerce div.product-quickview-content form.cart input.button:hover,
.woocommerce div.product form.cart input.button:hover,
.woocommerce div.product-quickview-content form.cart .button:focus,
.woocommerce div.product form.cart .button:focus,
.woocommerce div.product-quickview-content form.cart input.button:focus,
.woocommerce div.product form.cart input.button:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce div.product-quickview-content form.cart .variations .woocommerce-variation-select .swatch-select.selected,
.woocommerce div.product form.cart .variations .woocommerce-variation-select .swatch-select.selected {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce span.out_of_stock {
  background: <?php echo dh_print_string($brand_primary) ?>; 
}
@media (min-width: 992px) {
  .woocommerce .shop-loop.list ul.products li.product figcaption .loop-add-to-wishlist .yith-wcwl-add-to-wishlist .add_to_wishlist:hover,
  .woocommerce .shop-loop.list ul.products li.product figcaption .loop-add-to-wishlist .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a:hover,
  .woocommerce .shop-loop.list ul.products li.product figcaption .loop-add-to-wishlist .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a:hover {
    background: <?php echo dh_print_string($brand_primary) ?>;
    border-color: <?php echo dh_print_string($brand_primary) ?>;
  }
  .woocommerce .shop-loop.list ul.products li.product figcaption .shop-loop-quickview a:hover {
    background: <?php echo dh_print_string($brand_primary) ?>;
    border-color: <?php echo dh_print_string($brand_primary) ?>;
  }
  .woocommerce .shop-loop.list ul.products li.product figcaption .list-info-meta .loop-add-to-cart a:hover,
  .woocommerce .shop-loop.list ul.products li.product figcaption .list-info-meta .loop-add-to-cart a:focus {
    border-color: <?php echo dh_print_string($brand_primary) ?>;
    background: <?php echo dh_print_string($brand_primary) ?>;
  }
}
.woocommerce ul.products li.product .yith-wcwl-add-to-wishlist .add_to_wishlist:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce ul.products li.product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a:hover,
.woocommerce ul.products li.product .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce > div .button:not(.checkout-button):hover,
.woocommerce .cart .button:hover,
.woocommerce > div .button:not(.checkout-button):focus,
.woocommerce .cart .button:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  background: <?php echo dh_print_string($brand_primary) ?>;
}

.woocommerce ul.products li.product.style-2 .info-content-wrap .loop-add-to-cart a:after {
  background: <?php echo dh_print_string($brand_primary) ?>;
}

.woocommerce .star-rating span,
.woocommerce .star-rating::before{
	color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce p.stars.has-active a, .woocommerce p.stars:hover a{
	color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce .woocommerce-tabs .nav-tabs > li.reviews_tab span{
	 background: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce table.cart td.actions .button:hover,
.woocommerce table.cart td.actions .button:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce ul.cart_list li a:hover,
.woocommerce ul.product_list_widget li a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce.dhwc_widget_brands ul.product-brands li ul.children li a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce.widget_product_categories ul.product-categories li ul.children li a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce.widget_product_search form:before {
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce .widget_shopping_cart .buttons .button:hover,
.woocommerce .widget_shopping_cart .buttons .button:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce .widget_shopping_cart .buttons .button.checkout {
  background-color: <?php echo dh_print_string($brand_primary) ?>;
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce .cart-collaterals .shipping_calculator .button {
  background-color: <?php echo dh_print_string($brand_primary) ?>;
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce .cart-collaterals .cart_totals .checkout-button:hover,
.woocommerce .cart-collaterals .cart_totals .checkout-button:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce form .form-row button.button:hover,
.woocommerce form .form-row input.button:hover,
.woocommerce form .form-row button.button:focus,
.woocommerce form .form-row input.button:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce .checkout .woocommerce-checkout-review-order{
	border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce .checkout .woocommerce-checkout-review-order-table .order-total .amount {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce .widget_price_filter .price_slider_amount .button:hover,
.woocommerce .widget_price_filter .price_slider_amount .button:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  background: <?php echo dh_print_string($brand_primary) ?>;
}

.minicart-icon span {
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce div.product-quickview-content .stock, .woocommerce div.product .stock,
.minicart .minicart-body .cart-product .cart-product-title a:hover {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.minicart .minicart-footer .minicart-actions .button:hover,
.minicart .minicart-footer .minicart-actions .button:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.minicart-side .minicart-footer .minicart-actions .button::after{
 background: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce-contine-shoppong-btn:hover,
.woocommerce-contine-shoppong-btn:focus {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.product-slider-title.color-primary .el-heading {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.product-slider-title.color-primary .el-heading:before {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce ul.products li.product figure .product-wrap .product-images>a:after {
  background: <?php echo dh_print_string($fade_70_brand_primary) ?>;
}
.woocommerce ul.products li.product.style-3 span.onsale {
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.woocommerce ul.products li.product.style-3 span.onsale:before {
    border-color: <?php echo dh_print_string($brand_primary) ?> transparent <?php echo dh_print_string($brand_primary) ?> transparent;
}
.woocommerce ul.products li.product.style-3 .product-wrap .product-images .loop-action .shop-loop-quickview a:hover:before, .woocommerce ul.products li.product.style-3 .product-wrap .product-images .loop-action .loop-add-to-cart a:hover:before {
    color: <?php echo dh_print_string($brand_primary) ?>;
}
<?php endif;?>
.box-ft-3 .bof-tf-sub-title,
.box-ft-2 .bof-tf-sub-title,
.box-ft-1 .bof-tf-sub-title {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.box-ft-4 .bof-tf-title-wrap .bof-tf-title-wrap-2,
.box-ft-4-full-box.box-ft-4 .bof-tf-title-wrap:after {
  border-color: <?php echo dh_print_string($brand_primary) ?>;
}
.box-ft-4 .bof-tf-title-wrap .bof-tf-title {
  color: <?php echo dh_print_string($brand_primary) ?>;
}
.box-ft-4 .bof-tf-title-wrap .bof-tf-title-wrap-2 > a:before {
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.box-ft-4 .bof-tf-title-wrap.bg-primary {
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.footer-widget .title-footer span {
  background: <?php echo dh_print_string($brand_primary) ?>;
}
.footer-featured.light i {
  color: <?php echo dh_print_string($brand_primary) ?>;
}