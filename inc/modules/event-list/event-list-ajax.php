<?php

namespace BrownPaperTickets\Modules\EventList;

use \BrownPaperTickets\BptWordpress as Utilities;
use \BrownPaperTickets\BPTFeed as BPTFeed;

class Ajax {

	private static $nonce_title = 'bpt-event-list-nonce';

	/**
	 * Get the Events
	 */
	public static function get_events() {

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

		Utilities::check_nonce( $nonce, self::$nonce_title );

		$events = new BPTFeed;

		if ( ! Utilities::cache_data() ) {
			$events = $events->get_json_events( $client_id, $event_id );
			$events = self::filter_hidden_prices( $events );

			wp_send_json( $events, true );
		}

		if ( ! get_transient( '_bpt_event_list_events' . $post_id ) && Utilities::cache_data() ) {
			set_transient( '_bpt_event_list_events' . $post_id , $events->get_json_events( $client_id, $event_id ), Utilities::cache_time() );
		}

		$events = get_transient( '_bpt_event_list_events' . $post_id  );
		$events = json_decode( $events, true );

		if ( $event_id ) {
			$single_event = self::get_single_event( $event_id, $events );

			if ( $single_event ) {
				$events = $single_event;
			}
		}

		$events = self::sort_events( $events );
		$events = self::apply_price_options( $events );

		wp_send_json( $events );
	}

	/**
	 * Gets a specific event from an array of events.
	 * @param  integer $eventId The event ID.
	 * @param  mixed $events Either a json string or an array of events.
	 * @return mixed            Returns the single event array or false if no event.
	 */
	private static function get_single_event( $event_id, $events ) {
		if ( is_string( $events ) ) {
			$events = json_decode( $events, true );
		}

		$single_event = false;

		foreach ( $events as $event ) {
			if ( $event['id'] === (integer) $event_id ) {
				$single_event = $event;
			}
		}

		return array( $single_event );
	}

	public static function hide_prices() {

		$response = array();
		$nonce = $_POST['bptNonce'];

		if ( isset( $_POST['admin'] ) ) {
			exit('admin');
			$nonceTitle = 'bpt-admin-nonce';

		} else {
			$nonceTitle = 'bpt-event-list-nonce';
		}

		Utilities::check_nonce( $nonce, $nonceTitle );

		if ( Utilities::is_user_an_admin() ) {

			$hidden_prices = get_option( '_bpt_hidden_prices' );

			if ( $hidden_prices === '' || ! $hidden_prices ) {
				$hidden_prices = array();
			}

			$prices = $_POST['prices'];

			$response['prices'] = $prices;

			foreach ( $prices as $price ) {

				if ( array_key_exists( $price['priceId'], $hidden_prices ) ) {
					continue;
				}

				if ( empty( $price['eventTitle'] ) ) {
					$response['error'] = 'Event title is required.';

				}


				if ( empty( $price['eventId'] ) ) {
					$response['error'] = 'Event ID is required.';

				}

				if ( empty( $price['priceId'] ) ) {
					$response['error'] = 'Price ID is required.';

				}

				if ( empty( $price['priceName'] ) ) {
					$response['error'] = 'Price name is required.';

				}

				$id = $price['priceId'];

				$hidden_prices[ $id ] = $price;

			}

			$response['hiddenPrices'] = $hidden_prices;

			$update_option = update_option( '_bpt_hidden_prices', $hidden_prices );

			if ( $update_option ) {

				$response['success'] = 'Price has been hidden';
				$response['priceID'] = $price['priceId'];

			} else {

				$response['error'] = 'Could not hide price.';
				$response['priceID'] = $price['priceId'];
			}
		} else {

			$response = array(
				'error' => 'Not authorized.',
			);
		}

		wp_send_json( $response );
	}

	public static function unhide_prices() {

		$response = array();
		$nonce = $_POST['bptNonce'];

		if ( isset( $_POST['admin'] ) ) {
			$nonceTitle = 'bpt-admin-nonce';

		} else {

			$nonceTitle = 'bpt-event-list-nonce';
		}

		Utilities::check_nonce( $nonce, $nonceTitle );


		if ( Utilities::is_user_an_admin() ) {

			$hidden_prices = get_option( '_bpt_hidden_prices' );

			if ( ! $hidden_prices ) {
				$response['error'] = 'No hidden prices';
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

			} else {
				$response['error'] = 'Could not unhide price.';
				$response['priceID'] = $price['priceId'];
			}
		} else {

			$response = array(
				'error' => 'Not authorized.',
			);

		}

		wp_send_json( $response );
	}

	public static function set_price_max_quantity() {

		if ( isset( $_POST['maxQuantity'] ) ) {
			$max_quantity = get_option( '_bpt_price_max_quantity' );

			if ( ! $max_quantity ) {
				$max_quantity = array();
			}

			foreach ( $_POST['maxQuantity'] as $prices  ) {

				foreach ( $prices as $id => $max ) {

					$id = intval( $id );
					$max = intval( $max );

					if ( $max === 0 ) {
						unset( $max_quantity[ $id ] );
						continue;
					}

					$max_quantity[ $id  ] = $max;
				}
			}

			if ( update_option( '_bpt_price_max_quantity', $max_quantity ) ) {
				wp_send_json( array( 'success' => true, 'message' => 'Updated price quantity.', 'maxQuantity' => $max_quantity ) );
			}
		}

		wp_send_json_error( 'Unable to update price quantity.' );
	}

	private static function sort_events( $events ) {
		$sort = get_option( '_bpt_sort_events' );

		if ( $sort ) {

			if ( $sort === 'chrono' ) {
				usort( $events,
					function($a, $b) {

						if ( ! isset( $a['dates'] ) || ! isset( $b['dates'] ) ) {
							return 0;
						}

						if ( ! isset( $a['dates'][0] ) || ! isset( $b['dates'][0] ) ) {
							return 0;
						}

						if ( $a['dates'][0]['dateStart'] < $b['dates'][0]['dateStart'] ) {
							return -1;
						}
						if ( $a['dates'][0]['dateStart'] > $b['dates'][0]['dateStart'] ) {
							return 1;
						}
						return 0;
					}
				);
			}

			if ( $sort === 'reverse' ) {
				usort( $events,
					function($a, $b) {

						if ( ! isset( $a['dates'] ) || ! isset( $b['dates'] ) ) {
							return 0;
						}

						if ( ! isset( $a['dates'][0] ) || ! isset( $b['dates'][0] ) ) {
							return 0;
						}

						if ( $a['dates'][0]['dateStart'] > $b['dates'][0]['dateStart'] ) {
							return -1;
						}
						if ( $a['dates'][0]['dateStart'] < $b['dates'][0]['dateStart'] ) {
							return 1;
						}
						return 0;
					}
				);
			}
		}

		return $events;
	}

	private static function apply_price_options( $events ) {
		$events = self::filter_hidden_prices( $events );
		$events = self::apply_max_quantity( $events );
		$events = self::include_service_fee( $events );

		return $events;
	}

	private static function apply_max_quantity( $events ) {
		if ( $max_quantity = get_option( '_bpt_price_max_quantity' ) ) {

			foreach ( $events as &$event ) {
				foreach ( $event['dates'] as &$date ) {
					foreach ( $date['prices'] as &$price ) {
						foreach ( $max_quantity as $max ) {
							if ( key( $max_quantity ) === $price['id'] ) {
								$price['maxQuantity'] = $max;
							}
						}
					}
				}
			}
		}

		return $events;
	}

	private static function include_service_fee( $events ) {

		if ( get_option( '_bpt_include_service_fee' ) === 'true' ) {
			foreach ( $events as &$event ) {
				foreach ( $event['dates'] as &$date ) {
					foreach ( $date['prices'] as &$price ) {
						$price['value']  = $price['value'] + $price['serviceFee'];
					}
				}
			}
		}

		return $events;
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

		if ( $hidden_prices ) {

			// If the user is an admin, we'll just add a property "hidden" to the
			// price.
			if ( Utilities::is_user_an_admin() ) {

				foreach ( $events as &$event ) {

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
				foreach ( $events as &$event ) {

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
}