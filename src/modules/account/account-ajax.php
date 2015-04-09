<?php

namespace BrownPaperTickets\Modules\Account;

require( 'account-api.php' );

use BrownPaperTickets\BptWordpress as Utilities;
use BrownPaperTickets\BPTFeed;

class Ajax
{
	private static $nonce_title = 'bpt-admin-nonce';

	public static function get_account() {

		Utilities::check_nonce( $_REQUEST['nonce'], self::$nonce_title );

		$account_info = false;

		if ( Utilities::cache_enabled() ) {

			if ( ! get_transient( '_bpt_user_account_info' ) ) {
				$account = new Api;
				$account_info = $account->get_account();
				set_transient( '_bpt_user_account_info', $account_info, 0 );
			}

			$account_info = get_transient( '_bpt_user_account_info' );

		} else {
			$account = new Api;
			$account_info = $account->get_account();
		}

		wp_send_json( $account_info );
	}


	/**
	 * Account Test Setup
	 */

	public static function test_account() {

		Utilities::check_nonce( $_REQUEST['nonce'], 'bpt-admin-nonce' );

		$dev_id    = ( isset( $_REQUEST['devID'] ) ? htmlentities( $_REQUEST['devID'] ) : false );
		$client_id = ( isset( $_REQUEST['clientID'] ) ? htmlentities( $_REQUEST['clientID'] ) : false );

		if ( ! $dev_id ) {
			wp_send_json( array( 'success' => false, 'error' => 'No developer ID.' ) );
		}

		if ( ! $client_id ) {
			wp_send_json( array( 'success' => false, 'error' => 'No client ID.' ) );
		}

		$account = new Api;

		wp_send_json( $account->test_account( $dev_id, $client_id ) );
	}

}
