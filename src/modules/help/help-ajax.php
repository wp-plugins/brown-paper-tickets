<?php

namespace BrownPaperTickets\Modules\Help;

use BrownPaperTickets\BptWordpress as Utilities;

class Ajax
{
	private static $nonce_title = 'bpt-admin-nonce';

	public static function get_all_options() {
		$get = filter_input_array( INPUT_GET, FILTER_SANITIZE_ENCODED );

		Utilities::check_nonce( $get['nonce'], self::$nonce_title );

		global $wpdb;

		$options = $wpdb->get_results(
			'SELECT *
			FROM `wp_options`
			WHERE `option_name` LIKE \'%_bpt_%\'',
			OBJECT
		);

		$results = array();

		foreach ( $options as &$option ) {
			$option_name = str_replace( '_bpt_', '', $option->option_name );
			$results[ $option_name ] = $option->option_value;
		}

		wp_send_json( $results );
	}
}
