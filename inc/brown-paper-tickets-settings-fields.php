<?php

/**
 * Brown Paper Tickets Settings Fields HTML
 *
 * Here lies the callbacks for the add_settings_fields() function.
 */
namespace BrownPaperTickets;


require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-plugin.php');
require_once( plugin_dir_path( __FILE__ ).'../lib/bptWordpress.php');

use BrownPaperTickets\BPTPlugin;
use BrownPaperTickets\BPTWordpress;


class BPTSettingsFields {
	/**
	 * Settings Field Stuff
	 *
	 * I don't like putting the html to be rendered here. I must
	 * find a better way to do it.
	 */


	/**
	 * API Credential Fields
	 * @return [type] [description]
	 */
	public function get_developer_id_input() {
		?>
		<div class="dev-id-wrapper">
			<input name="_bpt_dev_id" value="<?php esc_attr_e( get_option( '_bpt_dev_id' ) );?>" type="text" placeholder="<?php esc_attr_e( 'Developer ID' ); ?>">
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						To access your developer ID, go here.
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	public function get_client_id_input() {
		?>
		<div class="client-id-wrapper">
			<input name="_bpt_client_id" value="<?php esc_attr_e( get_option( '_bpt_client_id' ) );?>" type="text" placeholder="<?php esc_attr_e( 'Client ID' ); ?>">
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ) ?>_help">
				<span>?</span>
				<div>
					<p>
						This is your Brown Paper Tickets username.
					</p>
				</div>
			</div>
		</div>
		<?php
	}



	public function get_cache_time_input() {
		$increment  = 1;
		$cache_time = get_option( '_bpt_cache_time' );
		$cache_unit = get_option( '_bpt_cache_unit' );

		?>
			<div class="cache-time-wrapper">
				<p>Enabling caching of your event data will increase page load times.</p>
				<p>By setting the time below, you will tell the plugin to save the event data to the database temporarily and to serve the event data from there, rather than having to pull it in through the Brown Paper Tickets API every page load</p>
				<label for="cache-time-increment">Cache Time</label>
				<select id="cache-time-increment" name="_bpt_cache_time">
					<option value="false" <?php esc_attr_e( selected( $cache_time, '0' ) );?>>Do Not Cache</option>
					<option value="0" <?php esc_attr_e( selected( $cache_time, '0' ) );?>>Cache Indefinitely</option>
		<?php
		while ( $increment < 50 ) {
						echo '<option value="' . esc_attr( $increment ) . '"' . esc_attr( selected( $cache_time, $increment ) ) . '>' . esc_attr( $increment ) . '</option>';
						$increment++;
					}
		?>
				</select>
				<label for="cache-time-unit">Cache Unit</label>
				<select id="cache-time-unit" name="_bpt_cache_unit">
					<option value="minutes" <?php selected( $cache_unit, 'minutes' ); ?>>Minutes</option>
					<option value="hours" <?php selected( $cache_unit, 'hours' ); ?>>Hours</option>
					<option value="days" <?php selected( $cache_unit, 'days' ); ?>>Days</option>
				</select>


				<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
					<span>?</span>
					<div>
						<p>Select the amount of time you would like to cache your event data.</p>
						<p>Setting this option will decrease the amount of time it takes for the data to load</p>
						<p></p>
					</div>
				</div>

				<div class="bpt-advanced-options">
					<button class="button-large button" id="bpt-delete-cache">Delete Cache</button>
					<img class="bpt-loading hidden" src="<?php echo esc_url( plugins_url( '/public/assets/img/loading.gif', dirname( __FILE__ ) ) ); ?>">
					<p class="bpt-success-message hidden"></p>
					<p class="bpt-error-message hidden"></p>
				</div>
			</div>
		<?php
	}
	/**
	 * Purchase Fields
	 */

	// public function get_allow_purchase_input() {

	// }

	/**
	 * Show Upcoming Dates in Calendar
	 */

	public function get_show_upcoming_events_calendar_input() {
		?>
		<div class="show-upcoming-dates-calendar-wrapper">
			<input id="show-upcoming-dates-calendar-true" name="_bpt_show_upcoming_events_calendar" <?php esc_attr_e( BptWordpress::is_selected( 'true', '_bpt_show_upcoming_events_calendar', 'checked' ) );?> value="true" type="radio" />
			<label for="show-upcoming-events-calendar-true">Yes</label>
			<input id="show-upcoming-events-calendar-false" name="_bpt_show_upcoming_events_calendar" <?php esc_attr_e( BptWordpress::is_selected( 'false', '_bpt_show_upcoming_events_calendar', 'checked' ) ); ?> value="false" type="radio" />
			<label for="show-upcoming-events-calendar-false">No</label>
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						If you would like to show upcoming events in the calendar, select yes.
					</p>
				</div>
			</div>
		</div>
		<?php
	}


	public function get_hidden_prices_input() {

		$hidden_prices = get_option( '_bpt_hidden_prices' );
		?>

		<div class="hidden-prices-wrapper">

		<?php if ( !$hidden_prices ) { ?>
			You have not hidden any prices.
		<?php return; }
				if ( array_key_exists( '', $hidden_prices ) ) {

					unset($hidden_prices['']);

					update_option('_bpt_hidden_prices_', $hidden_prices);

					$hidden_prices = get_option( '_bpt_hidden_prices' );
				}

		?>
			<table id="hidden-prices">
				<thead>
					<tr>
						<th>Event Name</th>
						<th>Price Name</th>
						<th>Event Id</th>
						<th>Price Id</th>
						<th>Display Price</th>
					</tr>
				</thead>
			<?php foreach ( $hidden_prices as $hidden_price ) {
			?>
				<tbody>
					<tr>
						<td><?php esc_html_e( $hidden_price['eventTitle'] ); ?></td>
						<td><?php esc_html_e( $hidden_price['priceName'] ); ?></td>
						<td><?php esc_html_e( $hidden_price['eventId'] ); ?></td>
						<td><?php esc_html_e( $hidden_price['priceId'] ); ?></td>
						<td>
							<a href="" class="bpt-unhide-price" data-price-id="<?php esc_html_e( $hidden_price['priceId'] ); ?>">
							Display Price</a>
						</td>
					</tr>
				</tbody>

			<?php } ?>
			</table>
		</div>
		<?php
	}

}