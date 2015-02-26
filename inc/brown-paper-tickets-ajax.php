<?php

namespace BrownPaperTickets;

require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-api.php' );
require_once( plugin_dir_path( __FILE__ ).'/../lib/bptWordpress.php' );

use BrownPaperTickets\BptWordpress;
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

		$events = get_transient( '_bpt_event_list_events' . $post_id  );

		if ( $event_id ) {
			$single_event = self::get_single_event( $event_id, $events );

			if ( $single_event ) {
				$events = $single_event;
			}
		}

		$events = self::filter_hidden_prices( $events );

		exit( json_encode( $events ) );
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

	public static function bpt_hide_prices() {

		$response = array();
		$nonce = $_POST['bptNonce'];

		if ( isset( $_POST['admin'] ) ) {
			exit('admin');
			$nonceTitle = 'bpt-admin-nonce';

		} else {
			$nonceTitle = 'bpt-event-list-nonce';
		}

		self::check_nonce( $nonce, $nonceTitle );

		if ( BptWordpress::is_user_an_admin() ) {

			$hidden_prices = get_option( '_bpt_hidden_prices' );

			if ( $hidden_prices === '' || !$hidden_prices) {
				$hidden_prices = array();
			}

			$prices = $_POST['prices'];

			$response['prices'] = $prices;
			foreach( $prices as $price ) {

				if ( array_key_exists( $price['priceId'], $hidden_prices ) ) {
					continue;
				}

				if (empty($price['eventTitle'])) {
					$response['error'] = 'Event title is required.';

					exit(json_encode($response));
				}


				if (empty($price['eventId'])) {
					$response['error'] = 'Event ID is required.';

					exit(json_encode($response));
				}

				if (empty($price['priceId'])) {
					$response['error'] = 'Price ID is required.';

					exit(json_encode($response));
				}

				if ( empty( $price['priceName'] ) ) {
					$response['error'] = 'Price name is required.';

					exit( json_encode( $response ) );
				}

				$id = $price['priceId'];

				$hidden_prices[ $id ] = $price;

			}

			$response['hiddenPrices'] = $hidden_prices;

			$update_option = update_option( '_bpt_hidden_prices', $hidden_prices );

			if ( $update_option ) {

				$response['success'] = 'Price has been hidden';
				$response['priceID'] = $price['priceId'];

				exit( json_encode( $response ) );

			} else {

				$response['error'] = 'Could not hide price.';
				$response['priceID'] = $price['priceId'];

				exit( json_encode( $response ) );
			}
		} else {

			$response = array(
				'error' => 'Not authorized.',
			);

			exit( json_encode( $response ) );
		}
	}

	public static function bpt_unhide_prices() {

		$response = array();
		$nonce = $_POST['bptNonce'];

		if ( isset( $_POST['admin'] ) ) {
			$nonceTitle = 'bpt-admin-nonce';

		} else {

			$nonceTitle = 'bpt-event-list-nonce';
		}

		self::check_nonce( $nonce, $nonceTitle );


		if ( BptWordpress::is_user_an_admin() ) {

			$hidden_prices = get_option( '_bpt_hidden_prices' );

			if ( ! $hidden_prices ) {
				$response['error'] = 'No hidden prices';
				exit( json_encode( $response ) );
			}

			$prices = $_POST['prices'];
			$response['prices'] = $prices;

			foreach ( $prices as $price ) {
				$id = $price['priceId'];
				unset( $hidden_prices[ $id ] );
			}

			$response['hiddenPrices'] = $hidden_prices;
			$update_option = update_option( '_bpt_hidden_prices', $hidden_prices );

			if ( $update_option ) {
				$response['success'] = 'Price is now visible.';
				$response['priceID'] = $price['priceId'];
				exit( json_encode( $response ) );
			} else {
				$response['error'] = 'Could not unhide price.';
				$response['priceID'] = $price['priceId'];
				exit( json_encode( $response ) );
			}
		} else {

			$response = array(
				'error' => 'Not authorized.',
			);

			exit( json_encode( $response ) );
		}
	}

	private static function check_nonce( $nonce, $nonce_title ) {

		header( 'Content-type: application/json' );

		if ( ! wp_verify_nonce( $nonce, $nonce_title ) ) {
			exit(
				json_encode(
					array(
						'error' => 'Invalid nonce.',
					)
				)
			);
		}

		return true;

	}

	/**
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
	 * Filter Hidden Prices
	 * @param  mixed $events Either a json string or an array of events.
	 * @return array         The modified/filtered array.
	 */
	private static function filter_hidden_prices( $events ) {

		if ( is_string( $events ) ) {
			$events = json_decode( $events, true );
		}

		$hidden_prices = get_option( '_bpt_hidden_prices' );

		if ( $hidden_prices && ! empty( $hidden_prices ) ) {

			// If the user is an admin, we'll just add a property "hidden" to the
			// price.
			if ( BptWordpress::is_user_an_admin() ) {

				foreach ( $events as &$event ) { /// The & is a reference. Makes it so
												// you work on the actual array, not
												// just a copy of it

					foreach ( $event['dates'] as &$date ) {

						foreach ( $date['prices'] as &$price ) {

							if ( array_key_exists( $price['id'], $hidden_prices ) ) {

								$price['hidden'] = true;

							}
						}
					}
				}
			} else {
				// If the user is not an admin, we'll remove that price.
				foreach ( $events as &$event ) { // The & is a reference. Makes it so
												// you work on the actual array, not
												// just a copy of it.

					foreach ( $event['dates'] as &$date ) {

						$i = 0;

						foreach ( $date['prices'] as &$price ) {

							if ( array_key_exists( $price['id'], $hidden_prices ) ) {

								unset( $date['prices'][ $i ] );

							}

							$i++;
						}

						$date['prices'] = array_values( $date['prices'] );
					}
				}
			}
		}

		return $events;
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