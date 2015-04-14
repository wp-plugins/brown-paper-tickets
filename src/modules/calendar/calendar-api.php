<?php

namespace BrownPaperTickets\Modules\Calendar;

use BrownPaperTickets\BptWordpress as Utilities;

require_once( Utilities::plugin_root_dir() . 'src/modules/bpt-module-api.php' );

class Api extends \BrownPaperTickets\Modules\ModuleApi {

	/**
	 * Returns an array of a specific producers events formatted for the CLNDR.
	 * @param  string  $client_id The Client ID of the producer you wish
	 *                            to get the events of.
	 * @param  boolean $dates     Get prices? Default is false.
	 * @param  boolean $prices    Get Prices? Default is false.
	 * @return json               The JSON string of the event Data.
	 */
	public function get_events( $client_id = null, $dates = true, $prices = false ) {

		$dev_id = get_option( '_bpt_dev_id' );

		if ( ! $this->dev_id ) {
			return array( 'success' => false, 'error' => 'Unable to fetch events.' );
		}

		if ( isset( $_POST['clientID'] ) &&  $_POST['clientID'] !== '' ) {
			$client_id = $_POST['clientID'];
		}

		$events = new \BrownPaperTickets\APIv2\EventInfo( $this->dev_id );

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

		return $clndr_format;
	}
}
