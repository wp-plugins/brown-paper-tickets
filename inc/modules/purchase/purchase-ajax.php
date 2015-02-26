<?php

namespace BrownPaperTickets\Modules\Purchase;

class Ajax
{

	public static function get_cart_contents()
	{
		Utilities::check_nonce();

		wp_send_json( Ajax::get_session_var() );
	}

	public static function init_cart() {
		Utilities::check_nonce();

		$dev_id = get_option( '_bpt_dev_id' );

		if ( $dev_id ) {
			$cart = new \BrownPaperTickets\APIv2\ManageCart( $dev_id );

			$cart_info = $cart->initCart();

			Ajax::set_session_var( 'cart_id', $cart_info['cartID'] ); 
			Ajax::set_session_var( 'cart_created_at', $cart_info['cartCreatedAt'] );

			wp_send_json_success( array( 'message' => 'Cart created.' ) );
		}

		wp_send_json_error( array( 'message' => 'Unable to create cart.' ) );
	}

	public static function add_prices() {
		Utilities::check_nonce();

		if ( ! isset( $_POST['prices'] ) ) {
			wp_send_json_error( array( 'message' => 'No prices were sent.' ) );
		}

		if ( Ajax::set_session_var( 'prices', $_POST['prices'] ) ) {
			wp_send_json( array(
					'message' => 'Prices added.',
					'prices' => Ajax::get_session_var( 'prices' )
				)
			);
		}

		wp_send_json_error( array( 'message' => 'Unable to add prices.' ) );
	}

	private static function verify_nonce()
	{
		$nonce = $_REQUEST['nonce'];

		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'bpt-purchase-tickets' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized.' ) );
		}
	}

	private static function set_session_var( $key, $value ) {
		Ajax::init_session();

		return $_SESSION['bpt_cart'][ $key ] = $value;

	}

	private static function get_session_var( $key = null ) {
		Ajax::init_session();

		if ( ! $key ) {
			return $_SESSION['bpt_cart'];
		}

		return $_SESSION['bpt_cart'][ $key ];
	}

	private static function init_session()
	{
		if ( ! session_id() ) {

			session_start();
		}
	}

}
