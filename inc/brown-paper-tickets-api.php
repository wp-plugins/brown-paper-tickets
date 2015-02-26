<?php

namespace BrownPaperTickets;

require_once( plugin_dir_path( __FILE__ ).'../lib/BptAPI/vendor/autoload.php' );
use BrownPaperTickets\APIv2\EventInfo;
use BrownPaperTickets\APIv2\AccountInfo;
/**
 * This handles all of the event info formatting using the data obtained
 * via the BPT APIv2 PHP class.
 */

class BPTFeed {

	protected $dev_id;
	protected $client_id;

	public function __construct() {

		$this->dev_id    = get_option( '_bpt_dev_id' );
		$this->client_id = get_option( '_bpt_client_id' );

	}

	/**
	 * JSON Functions
	 */


	/**
	 * Returns a json string of a specific producers events.
	 * @param  string  $client_id The Client ID of the producer you wish
	 *                            to get the events of.
	 * @param  boolean $dates     Get prices? Default is false.
	 * @param  boolean $prices    Get Prices? Default is false.
	 * @return json               The JSON string of the event Data.
	 */
	public function get_json_calendar_events( $client_id = null, $dates = true, $prices = false ) {

		if ( isset( $_POST['clientID'] ) &&  $_POST['clientID'] !== '' ) {
			$client_id = $_POST['clientID'];
		}

		$events = new EventInfo( $this->dev_id );

		$events = $events->getEvents( $client_id, null, $dates, $prices );

		$clndr_format = array();

		foreach ( $events as $event ) {

			if ( $event['live'] ) {
				foreach ( $event['dates'] as $date ) {

					if ( $date['live'] ) {

						$clndr_format[] = array(
							'eventID' => $event['id'],
							'dateID' => $date['id'],
							'date' => $date['dateStart'],
							'endDate' => $date['dateEnd'],
							'timeStart' => $date['timeStart'],
							'timeEnd' => $date['timeEnd'],
							'title' => $event['title'],
							'city' => $event['city'],
							'state' => $event['state'],
							'zip' => $event['zip'],
							'shortDescription' => $event['shortDescription'],
						);
					}
				}
			}
		}

		return json_encode( $clndr_format );
	}

	/**
	 * Get a json representation of your events based on the settings set within
	 * Wordpress.
	 * @param  string $client_id A comma delineated string of event Ids.
	 * @param  string $event_id  The
	 * @return string            Json string of events, dates and prices.
	 */
	public function get_json_events( $client_id = null, $event_id = null ) {

		if ( ! $client_id ) {
			$client_id = $this->client_id;
		}

		if ( ! $event_id ) {
			$event_id = null;
		}


		/**
		 * Get Event List Setting Options
		 *
		 */
		$show_dates           = get_option( '_bpt_show_dates' );
		$show_prices          = get_option( '_bpt_show_prices' );
		$show_past_dates      = get_option( '_bpt_show_past_dates' );
		$show_sold_out_dates  = get_option( '_bpt_show_sold_out_dates' );
		$show_sold_out_prices = get_option( '_bpt_show_sold_out_prices' );


		$event_info = new EventInfo( $this->dev_id );

		if ( $event_id ) {
			$client_id = null;
			$event_id  = explode( ' ', $event_id );
			$events    = array();

			foreach ( $event_id as $id ) {
				$events[] = $event_info->getEvents( $client_id, $id, $show_dates, $show_prices );
			}

			foreach ( $events as $event ) {
				$event_list[] = $event[0];
			}
		}

		if ( ! $event_id ) {
			$event_list = $event_info->getEvents( $client_id, $event_id, $show_dates, $show_prices );
		}


		if ( isset( $event_list['error'] ) ) {
			return json_encode( $event_list );
		}

		$event_list = $this->remove_bad_events( $event_list );

		$event_list = $this->sort_prices( $event_list );

		if ( $show_dates === 'true' ) {

			$remove_past = true;

			if ( $show_past_dates === 'false' ) {
				$remove_past = false;
			}

			$event_list = $this->remove_bad_dates( $event_list, true, $remove_past );
		}

		if ( $show_prices === 'true' && $show_sold_out_prices === 'false' ) {
			$event_list = $this->remove_bad_prices( $event_list );
		}

		return json_encode( $event_list );
	}

	public function get_json_account() {

		$account_info = new AccountInfo( $this->dev_id );

		return json_encode( $account_info->getAccount( $this->client_id ) );
	}


	/**
	 * Simple Get Account Call for testing that the settings are correct.
	 * $dev_id and $client_id must be passed to the function.
	 */

	public function bpt_setup_wizard_test( $dev_id, $client_id ) {

		$account_info = new AccountInfo( $dev_id );
		$event_list   = new EventInfo( $dev_id );

		$response = array(
			'account' => $account_info->getAccount( $client_id ),
			'events'  => $event_list->getEvents( $client_id ),
		);

		$response['events'] = $this->remove_bad_events( $response['events'] );

		return json_encode( $response );
	}

	/**
	 * Event Methods
	 */
	private function get_event_count() {
		$events = new EventInfo( $this->dev_id );
		return count( $events->getEvents( $this->client_id ) );
	}

	/**
	 * Date Methods
	 *
	 */
	private function date_has_past( $date ) {

		if ( strtotime( $date['dateStart'] ) < time() ) {
			return true;
		}
		return false;
	}

	private function date_is_live( $date ) {

		if ( ! $date['live'] ) {
			return false;
		} else {
			return true;
		}

	}

	private function date_is_sold_out( $date ) {

		if ( $this->date_has_past( $date ) === true && strtotime( $date['dateStart'] ) >= time() ) {
			return false;
		}

		return true;
	}

	/**
	 * Price Methods
	 */

	private function price_is_live( $price ) {
		if ( ! $price['live'] ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Conversion Methods
	 */
	/**
	 * Convert Date. Converst the Date to a human readable date.
	 *
	 * @param  string $date The String that needs to be formatted.
	 * @return string       The formatted date string.
	 */
	private function convert_date( $date ) {
		return strftime( '%B %e, %Y', strtotime( $date ) );
	}

	/**
	 * Convert Time. Converst the Time to a human readable date.
	 * @param  string $time The string to be formated.
	 * @return string       The formatted string.
	 */
	private function convert_time( $date ) {
		return strftime( '%l:%M%p', strtotime( $date ) );
	}

	private function remove_bad_events( $event_list ) {
		foreach ( $event_list as $eventIndex => $event ) {

			if ( ! $event['live'] ) {

				unset( $event_list[ $eventIndex ] );
			}

			$event_list = array_values( $event_list );
		}

		return $event_list;
	}

	/**
	 * Removes past dates and deactivated from an array of events.
	 * @param  array   $event_list     	   An array of events with dates.
	 * @param  boolean $remove_deactivated Pass false if you want to remove deactivated dates.
	 * @param  boolean $remove_past        Pass false if you want to remove past dates.
	 * @return array                       The modified event array with bad dates removed.
	 */
	private function remove_bad_dates( $event_list, $remove_deactivated = true, $remove_past = true ) {

		foreach ( $event_list as $event_index => $event ) {

			if ( ! isset($event['dates'] ) ) {
				continue;
			}

			foreach ( $event['dates'] as $date_index => $date ) {

				$remove_date = false;

				if ( $remove_past && $this->date_has_past( $date ) ) {
					$remove_date = true;
				}

				if ( $remove_deactivated && ! $this->date_is_live( $date ) ) {
					$remove_date = true;
				}

				if ( $remove_date ) {
					unset( $event['dates'][ $date_index ] );
				}
			}

			$event['dates'] = array_values( $event['dates'] );

			$event_list[ $event_index ] = $event;
		}

		return $event_list;
	}

	private function remove_bad_prices( $event_list ) {
		foreach ( $event_list as $event_index => $event ) {

			foreach ( $event['dates'] as $date_index => $date ) {

				foreach ( $date['prices'] as $priceIndex => $price ) {

					if ( $this->price_is_live( $price ) === false ) {
						unset( $date['prices'][ $priceIndex ] );
					}
				}

				$date['prices'] = array_values( $date['prices'] );

				$event['dates'][ $date_index ] = $date;
			}

			$event_list[ $event_index ] = $event;
		}

		return $event_list;
	}


	private function sort_prices( $event_list ) {
		$sort_method = get_option( '_bpt_price_sort' );

		foreach ( $event_list as $event_index => $event ) {

			foreach ( $event['dates'] as $date_index => $date ) {

				if ( $sort_method === 'alpha_asc' ) {
					$date['prices'] = $this->sort_by_key( $date['prices'], 'name', true );
				}

				if ( $sort_method === 'alpha_desc' ) {
					$date['prices'] = $this->sort_by_key( $date['prices'], 'name' );
				}

				if ( $sort_method === 'value_desc' ) {
					$date['prices'] = $this->sort_by_key( $date['prices'], 'value', true );
				}

				if ( $sort_method === 'value_asc' )  {
					$date['prices'] = $this->sort_by_key( $date['prices'], 'value' );
				}

				$event['dates'][ $date_index ] = $date;
			}

			$event_list[ $event_index ] = $event;
		}

		return $event_list;
	}

	private function sort_by_key( $array, $key, $reverse = false ) {

		//Loop through and get the values of our specified key
		foreach ( $array as $k => $v ) {
			$b[] = strtolower( $v[$key] );
		}

		if ( $reverse === false ) {

			asort( $b );

		} else {

			arsort( $b );

		}

		foreach ( $b as $k => $v ) {
			$c[] = $array[$k];
		}

		return $c;

	}

}