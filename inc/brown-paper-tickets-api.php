<?php

namespace BrownPaperTickets;

require_once( plugin_dir_path( __FILE__ ).'/../lib/BptAPI/vendor/autoload.php' );
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
	 * to get the events of.
	 * @param  boolean $dates Get prices? Default is false.
	 * @param  boolean $prices Get Prices? Default is false.
	 * @return json              The JSON string of the event Data.
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

	/** Takes the client_id and event_id and returns events **/

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
		$_bpt_dates                = get_option( '_bpt_show_dates' );
		$_bpt_prices               = get_option( '_bpt_show_prices' );
		$_bpt_show_past_dates      = get_option( '_bpt_show_past_dates' );
		$_bpt_show_sold_out_dates  = get_option( '_bpt_show_sold_out_dates' );
		$_bpt_show_sold_out_prices = get_option( '_bpt_show_sold_out_prices' );

		$_bpt_events = new EventInfo( $this->dev_id );

		if ( $event_id ) {
			$client_id = null;
			$event_id  = explode( ' ', $event_id );
			$events    = array();

			foreach ( $event_id as $id ) {
				$events[] = $_bpt_events->getEvents( $client_id, $id, $_bpt_dates, $_bpt_prices );
			}

			foreach ( $events as $event ) {
				$_bpt_eventList[] = $event[0];
			}
		}

		if ( ! $event_id ) {
			$_bpt_eventList = $_bpt_events->getEvents( $client_id, $event_id, $_bpt_dates, $_bpt_prices );
		}


		if ( isset( $_bpt_eventList['error'] ) ) {
			return json_encode( $_bpt_eventList );
		}

		$_bpt_eventList = $this->remove_bad_events( $_bpt_eventList );

		$_bpt_eventList = $this->sort_prices( $_bpt_eventList );

		if ( $_bpt_dates === 'true' && $_bpt_show_past_dates === 'false' ) {
			$_bpt_eventList = $this->remove_bad_dates( $_bpt_eventList );
		}

		if ( $_bpt_prices === 'true ' && $_bpt_show_sold_out_prices === 'false' ) {
			$_bpt_eventList = $this->remove_bad_prices( $_bpt_eventList );   
		}
		
		return json_encode( $_bpt_eventList );

	}

	public function get_json_account() {

		$_bpt_account = new AccountInfo( $this->dev_id );

		return json_encode( $_bpt_account->getAccount( $this->client_id ) );
	}


	/**
	 * Simple Get Account Call for testing that the settings are correct.
	 * $dev_id and $client_id must be passed to the function.
	 */

	public function bpt_setup_wizard_test( $dev_id, $client_id ) {

		$_bpt_account = new AccountInfo( $dev_id );
		$_bpt_event   = new EventInfo( $dev_id );

		$response = array(
			'account' => $_bpt_account->getAccount( $client_id ),
			'events'  => $_bpt_event->getEvents( $client_id ),
		);

		$response['events'] = $this->remove_bad_events( $response['events'] );

		return json_encode( $response );
	}

	/**
	 * Event Methods
	 */
	
	public function get_event_count() {
		$events = new EventInfo( $this->dev_id );
		return count( $events->getEvents( $this->client_id ) );
	}
	/**
	 * Date Methods
	 * 
	 */
	public function date_has_past( $date ) {

		if ( strtotime( $date['dateStart'] ) < time() ) {
			return true;
		}
		return false;
	}

	public function date_is_live( $date ) {

		if ( $date['live'] === false ) {
			return false;
		}

		return true;
	}

	public function date_is_sold_out( $date ) {

		if ( $this->date_has_past( $date ) === true && strtotime( $date['dateStart'] ) >= time() ) {
			return false;
		}

		return true;
	}

	/**
	 * Price Methods
	 */

	public function price_is_live( $price ) {

		if ( $price['live'] === false ) {
			return false;
		}

		return true;
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
	public function convert_date( $date ) {
		return strftime( '%B %e, %Y', strtotime( $date ) );
	}

	/**
	 * Convert Time. Converst the Time to a human readable date.
	 * @param  string $time The string to be formated.
	 * @return string       The formatted string.
	 */
	public function convert_time( $date ) {
		return strftime( '%l:%M%p', strtotime( $date ) );
	}

	protected function remove_bad_events( $_bpt_eventList ) {
		foreach ( $_bpt_eventList as $eventIndex => $event ) {

			if ( ! $event['live'] ) {

				unset( $_bpt_eventList[$eventIndex] );
			}

			$_bpt_eventList = array_values( $_bpt_eventList );
		}

		return $_bpt_eventList;
	}

	protected function remove_bad_dates( $_bpt_eventList ) {

		foreach ( $_bpt_eventList as $eventIndex => $event ) {

			foreach ( $event['dates'] as $dateIndex => $date ) {
				
				if ( $this->date_has_past( $date ) || ! $this->date_is_live( $date ) ) {

					unset( $event['dates'][$dateIndex] );
				}
			}

			$event['dates'] = array_values( $event['dates'] );

			$_bpt_eventList[$eventIndex] = $event;
		}

		return $_bpt_eventList;
	}

	protected function remove_bad_prices( $_bpt_eventList ) {
		foreach ( $eventList as $eventIndex => $event ) {

			foreach ( $event['dates'] as $dateIndex => $date ) {
				
				foreach ( $date['prices'] as $priceIndex => $price ) {

					if ( $this->price_is_live( $price ) === false ) {
						unset( $date['prices'][$priceIndex] );
					}
				}
				
				$date['prices'] = array_values( $date['prices'] );

				$event['dates'][$dateIndex] = $date;
			}

			$_bpt_eventList[$eventIndex] = $event;
		}

		return $_bpt_eventList;
	}

	protected function sort_prices( $_bpt_eventList ) {
		$sort_method = get_option( '_bpt_price_sort' );

		foreach ( $_bpt_eventList as $eventIndex => $event ) {

			foreach ( $event['dates'] as $dateIndex => $date ) {
				
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

				$event['dates'][$dateIndex] = $date;
			}

			$_bpt_eventList[$eventIndex] = $event;
		}

		return $_bpt_eventList;
	}

	protected function sort_by_key( $array, $key, $reverse = false ) {

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