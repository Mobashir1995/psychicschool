<?php

add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text_1' );    // 2.1 +

	function woo_custom_cart_button_text_1()
	{
		return __('Register Now', 'stm_domain');
	}


function abc_set_size() {
   add_image_size( 'img-270-135', 270, 135, true );
add_image_size( 'img-300-150', 300, 150, true );

	}
add_action( 'after_setup_theme', 'abc_set_size' );


