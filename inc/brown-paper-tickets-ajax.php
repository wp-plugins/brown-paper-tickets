<?php

namespace BrownPaperTickets;

require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-api.php' );

use BrownPaperTickets\BPTFeed;

class BPTAjaxActions {

	/**
	 * Get the Events
	 */
	public static function bpt_get_events() {

		$nonce     = $_POST['bptNonce'];
		$post_id   = null;
		$client_id = null;
		$event_id  = null;
		$widget_id = null;

		if ( isset( $_POST['postID'] ) ) {
			$post_id = $_POST['postID'];
		}

		if ( isset( $_POST['clientID'] ) ) {
			$client_id = $_POST['clientID'];
		}

		if ( isset( $_POST['eventID'] ) ) {
			$event_id = $_POST['eventID'];
		}

		if ( isset( $_POST['widgetID'] ) ) {
			$widget_id = $_POST['widgetID'];
		}

		self::check_nonce( $nonce, 'bpt-event-list-nonce' );

		$events = new BPTFeed;

		if ( ! self::cache_data() ) {
				
			exit( $events->get_json_events( $client_id, $event_id ) );
		}

		if ( ! get_transient( '_bpt_event_list_events' . $post_id ) && self::cache_data() ) {

			set_transient( '_bpt_event_list_events' . $post_id , $events->get_json_events( $client_id, $event_id ), self::cache_time() );

		}

		exit( get_transient( '_bpt_event_list_events' . $post_id  ) );
	}

	/**
	 * Get Specific Producer's events.
	 */
		
	public static function bpt_get_calendar_events() {

		$nonce           = $_POST['bptNonce'];
		$widget_instance = $_POST['widgetID'];

		header( 'Content-type: application/json' );

		self::check_nonce( $nonce, 'bpt-calendar-widget-nonce' );

		if ( isset( $_POST['clientID'] ) ) {
			$client_id = $_POST['clientID'];
		} else {
			$client_id = get_option( '_bpt_client_id' );
		}

		if ( ! $client_id ) {
			exit( json_encode( array( 'error' => 'No Client ID' ) ) );
		}

		if ( ! $widget_instance ) {
			exit( json_encode( array( 'error' => 'No Widget Instance' ) ) );
		}

		if ( ! self::cache_data() ) {

			$events = new BPTFeed;

			exit( $events->get_json_calendar_events( $client_id ) );
		}

		if ( get_transient( '_bpt_calendar_events_' . $widget_instance ) === false && self::cache_data() ) {

			$events = new BPTFeed;

			set_transient( '_bpt_calendar_events_' . $widget_instance, $events->get_json_calendar_events( $client_id ), self::cache_time() );

		}

		exit( get_transient( '_bpt_calendar_events_' . $widget_instance ) );

	}

	public static function bpt_get_account() {

		$nonce = $_POST['bptNonce'];

		self::check_nonce( $nonce, 'bpt-admin-nonce' );

		if ( ! get_transient( 'bpt_user_account_info' ) && self::cache_data() ) {

			$account = new BPTFeed;

			set_transient( '_bpt_user_account_info', $account->get_json_account(), 0 );
			
		}

		exit( get_transient( '_bpt_user_account_info' ) );
	}

	/**
	 * Account Test Setup
	 */

	public static function bpt_account_test() {

		$nonce = $_POST['bptNonce'];

		self::check_nonce( $nonce, 'bpt-setup-wizard-nonce' );

		$account = new BPTFeed;


		$dev_id    = $_POST['devID'];
		$client_id = $_POST['clientID'];

		if ( ! isset( $dev_id ) || ! $_POST['devID'] ) {
			exit(
				json_encode(
					array(
						'error' => 'No Developer ID.',
					)
				)
			);
		}

		if ( ! isset( $client_id ) || ! $_POST['clientID'] ) {
			exit(
				json_encode(
					array(
						'error' => 'No Client ID.',
					)
				)
			);
		}

		exit( $account->bpt_setup_wizard_test( $dev_id, $client_id ) );
	}

	public static function bpt_delete_cache() {

		global $wpdb;

		header( 'Content-type: application/json' );

		$cached_data = $wpdb->get_results(
			'SELECT *
			FROM `wp_options`
			WHERE `option_name` LIKE \'%_transient__bpt_%\'',
			OBJECT
		);

		if ( empty( $cached_data ) ) {
			$result = array(
				'status' => 'success',
				'message' => 'No cached data to delete.',
			);

			exit (json_encode( $result ) );
		}
		
		if ( ! empty( $cached_data ) ) {

			foreach ( $cached_data as $cache ) {

				$option_name = $cache->option_name;

				$option_name = str_replace( '_transient_', '', $option_name );

				delete_transient( $option_name );
			}
		}

		$result = array(
			'status' => 'success',
			'message' => 'Cached data has been deleted.',
		);

		exit( json_encode( $result ) );
	}

	private static function check_nonce( $nonce, $nonce_title ) {

		header( 'Content-type: application/json' );

		if ( ! wp_verify_nonce( $nonce, $nonce_title ) ) {
			exit(
				json_encode(
					array(
						'error' => 'Could not obtain events.',
					)
				)
			);
		}

		return true;

	}

	/**
	 * Cache Dataheader( 'Content-type: application/json' );
	 * 
	 * @return boolean Returns whether or not the plugin should cache data.
	 */
	private static function cache_data() {

		$cache_time = get_option( '_bpt_cache_time' );

		if ( $cache_time === 'false' ) {
			return false;
		}

		return true;
	}


	/**
	 * Returns the amount of time to cache the data for. This should
	 * only be called if self::cache_data() is true.
	 * 
	 * @return integer The amount of time in seconds the data should be 
	 * cached.
	 */
	private static function cache_time() {

		$cache_time = get_option( '_bpt_cache_time' );

		$cache_unit = get_option( '_bpt_cache_unit' );

		if ( $cache_unit === 'minutes' ) {
			return $cache_time * MINUTE_IN_SECONDS;
		}

		if ( $cache_unit === 'hours' ) {
			return $cache_time * HOUR_IN_SECONDS;
		}        

		if ( $cache_unit === 'days' ) {

			return $cache_time * DAY_IN_SECONDS;
		}

		return 0;

	}

}