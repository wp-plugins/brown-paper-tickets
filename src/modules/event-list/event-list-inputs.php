<?php

/**
 * Brown Paper Tickets Account Settings Fields HTML
 *
 * Here lies the callbacks for the add_settings_fields() function.
 */
namespace BrownPaperTickets\Modules\EventList;

require_once( plugin_dir_path( __FILE__ ).'../../../src/brown-paper-tickets-plugin.php');

use BrownPaperTickets\BPTPlugin;
use BrownPaperTickets\BptWordpress as Utils;

class Inputs {

	public function show_dates() {
		?>
		<div class="show-dates-wrapper">
			<input id="show-dates-true" name="_bpt_show_dates" <?php esc_attr_e( Utils::is_selected( 'true', '_bpt_show_dates', 'checked' ) );?> value="true" type="radio" />
			<label for="show-dates-true">Yes</label>
			<input id="show-dates-false" name="_bpt_show_dates" <?php esc_attr_e( Utils::is_selected( 'false', '_bpt_show_dates', 'checked' ) ); ?> value="false" type="radio" />
			<label for="show-dates-false">No</label>
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						This option determines whether or not your event's dates will appear in your event listing.
					</p>
				</div>
			</div>
		</div>

		<?php
	}

	public function show_prices() {

		?>
		<div class="show-prices-wrapper">
			<input id="show-prices-true" name="_bpt_show_prices" <?php esc_attr_e( Utils::is_selected( 'true', '_bpt_show_prices', 'checked' ) );?> value="true" type="radio" />
			<label for="show-prices-true">Yes</label>
			<input id="show-prices-false" name="_bpt_show_prices" <?php esc_attr_e( Utils::is_selected( 'false', '_bpt_show_prices', 'checked' ) ); ?> value="false" type="radio" />
			<label for="show-prices-false">No</label>
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						This option determines whether or not your event's prices will appear in your event listing.
					</p>
				</div>
			</div>
		</div>

		<?php
	}

	public function show_end_time() {

		?>
		<div class="show-end-time-wrapper">
			<input id="show-end-time-true" name="_bpt_show_end_time" <?php esc_attr_e( Utils::is_selected( 'true', '_bpt_show_end_time', 'checked' ) );?> value="true" type="radio" />
			<label for="show-end-time-true">Yes</label>
			<input id="show-end-time-false" name="_bpt_show_end_time" <?php esc_attr_e( Utils::is_selected( 'false', '_bpt_show_end_time', 'checked' ) ); ?> value="false" type="radio" />
			<label for="show-end-time-false">No</label>
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						This option determines whether or not to show your event's end time.
					</p>
				</div>
			</div>
		</div>

		<?php
	}

	public function date_format() {
			$date_formats = array(
				'DD-MM-YYYY' => '28-01-2016',
				'MM-DD-YYYY' => '28 January, 2016',
				'MM-DD-YYYY' => '01-28-2016',
				'MMMM Do, YYYY' => 'January 28th, 2016',
				'MMMM Do, YYYY' => 'January 28th, 2016',
				'ddd MMM Do, YYYY' => 'Thu Jan 28th, 2016',
				'dddd MMM Do, YYYY' => 'Thursday January 28th, 2016',
				'custom' => 'Custom Format',
			);
		?>
		<div class="date-format-wrapper">
			<select id="date-format" name="_bpt_date_format">
		<?php

		foreach ( $date_formats as $format => $description ) {
			?>
				<option value="<?php esc_attr_e( $format ); ?>" <?php esc_attr_e( Utils::is_selected( $format, '_bpt_date_format', 'selected' ) ); ?> ><?php esc_html_e( $description ); ?></option>
		<?php

			}
		?>
			</select>
			<input class="hidden" id="custom-date-format-input" name="_bpt_custom_date_format" type="text" value="<?php esc_attr_e( get_option( '_bpt_custom_date_format' ) ); ?>" />
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						This option will determine the format that your event's dates will appear in.
					</p>
					<p>
						Choose from a set of predefined options or set your own
					</p>
					<p>
						If you set a custom option, see <a href="http://momentjs.com/docs/#/displaying/format/" target="_blank">Moment.js documentation</a> for all of the potential parameters.
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	public function sort_events() {
		$sort_by = array(
			'Default' => 'default',
			'Chronological' => 'chrono',
			'Reverse Chronological' => 'reverse',
		);
		?>
		<select id="sort-events" name="_bpt_sort_events">
		<?php foreach ( $sort_by as $name => $value ) {
			?>
			<option value="<?php esc_attr_e( $value ); ?>" <?php esc_attr_e( Utils::is_selected( $value, '_bpt_sort_events', 'selected' ) ); ?>>
				<?php esc_attr_e( $name ) ?>
			</option>
			<?php
		} ?>
		</select>
		<?php
	}

	public function time_format() {
			$time_formats = array(
				'HH:mm' => '24:30',
				'hh:mm A' => '12:30 PM',
				'hh:mm a' => '12:30 pm',
				'custom' => 'Custom Format',
			);
		?>
		<div class="time-format-wrapper">
			<select id="time-format" name="_bpt_time_format">
		<?php

		foreach ( $time_formats as $format => $description ) {
			?>
				<option value="<?php esc_attr_e( $format ); ?>" <?php esc_attr_e( Utils::is_selected( $format, '_bpt_time_format', 'selected' ) ); ?> ><?php esc_attr_e( $description ); ?></option>
		<?php

			}
		?>
			</select>
			<input class="hidden" id="custom-time-format-input" name="_bpt_custom_time_format" type="text" value="<?php echo esc_attr_e( get_option( '_bpt_custom_time_format' ) ); ?>" />
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						This option determines the format you wish your dates to appear in.
					</p>
					<p>
						If you set a custom option, see <a href="http://momentjs.com/docs/#/displaying/format/" target="_blank">Moment.js documentation</a> for all of the potential parameters.
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	public function shipping_methods() {
		?>
		<div class="shipping-methods-wrapper">
			<label for="print-at-home">Print at Home</label>
			<input id="print-at-home" value="print_at_home" name="_bpt_shipping_methods[]"  type="checkbox" <?php esc_attr_e( Utils::is_selected( 'print_at_home', '_bpt_shipping_methods', 'checked' ) );?>/>

			<label for="will-call">Will-Call</label>
			<input id="will-call" value="will_call" name="_bpt_shipping_methods[]"  type="checkbox" <?php esc_attr_e( Utils::is_selected( 'will_call', '_bpt_shipping_methods', 'checked' ) );?>/>

			<label for="physical">Physical</label>
			<input id="physical" value="physical" name="_bpt_shipping_methods[]"  type="checkbox" <?php esc_attr_e( Utils::is_selected( 'physical', '_bpt_shipping_methods', 'checked' ) );?>/>

			<label for="mobile">Mobile</label>
			<input id="mobile" value="mobile" name="_bpt_shipping_methods[]"  type="checkbox" <?php esc_attr_e( Utils::is_selected( 'mobile', '_bpt_shipping_methods', 'checked' ) );?>/>

			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						<h4>This plugin has no way to determine which shipping options are available for your events.</h3>
					</p>
					<p>
						<h4>You must ensure that the options you select here are actually enabled on your event</h3>
					</p>
					<p>
						Select the shipping methods you wish to display for your events.
						<ul>
							<li>Print at Home - This method allows ticket buyers to print their tickets at home. No Fee</li>
							<li>Will Call - This method allows the ticket buyer to pick up their tickets at the box office prior to the show. No fee</li>
							<li>Physical - This method will allow physical tickets to be shipped to the ticket buyer, fulfilled by Brown Paper Tickets. Fee. </li>
							<li>Mobile - This method will send the user a text message with their ticket purchase allowing producers who use the Brown Paper Tickets Mobile Scanner App to scan tickets at the door.</li>
						</ul>
					</p>
				</div>
			</div>

		</div>
		<?php
	}

	public function shipping_countries() {

			$countries = Utils::get_country_list();
		?>
		<div class="shipping-countries-wrapper">
			<label for="united-states">Default Country</label>
			<select name="_bpt_shipping_countries">

		<?php
		foreach ( $countries as $country ) {
						echo '<option value="' . esc_attr( $country ) . '"' . esc_attr( Utils::is_selected( $country, '_bpt_shipping_countries', 'selected' ) ) . '>' . esc_html( $country ) . '</option>';
					}
				?>

			</select>

			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
					   The countries you wish to allow shipping to and from.
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	public function currency() {
		?>
		<div class="currency-wrapper">
			<select id="currency" name="_bpt_currency">
				<option value="usd" <?php esc_attr_e( Utils::is_selected( 'usd', '_bpt_currency', 'selected' ) );?>>USD $</option>
				<option value="cad" <?php esc_attr_e( Utils::is_selected( 'cad', '_bpt_currency', 'selected' ) );?>>CAD $</option>
				<option value="gbp" <?php esc_attr_e( Utils::is_selected( 'gbp', '_bpt_currency', 'selected' ) );?>>GBP £</option>
				<option value="eur" <?php esc_attr_e( Utils::is_selected( 'eur', '_bpt_currency', 'selected' ) );?>>EUR €</option>
			</select>

			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
					   The your event's prices should be displayed in.
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	public function price_sort() {
		?>
		<div class="price-sort-wrapper">
			<select id="price-sort" name="_bpt_price_sort">
				<option value="alpha_asc" <?php esc_attr_e( Utils::is_selected( 'alpha_asc', '_bpt_price_sort', 'selected' ) );?>>Alphabetical</option>
				<option value="value_asc" <?php esc_attr_e( Utils::is_selected( 'value_asc', '_bpt_price_sort', 'selected' ) );?>>Price Value - Low to High</option>
				<option value="value_desc" <?php esc_attr_e( Utils::is_selected( 'value_desc', '_bpt_price_sort', 'selected' ) );?>>Price Value - High to Low</option>
			</select>

			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
					   The order by which you wish to display prices.
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	public function show_full_description() {

		?>
		<div class="show-full-description-wrapper">
			<input id="show-full-description-true" name="_bpt_show_full_description" <?php esc_attr_e( Utils::is_selected( 'true', '_bpt_show_full_description', 'checked' ) );?> value="true" type="radio" />
			<label for="show-full-description-true">Yes</label>
			<input id="show-full-description-false" name="_bpt_show_full_description" <?php esc_attr_e( Utils::is_selected( 'false', '_bpt_show_full_description', 'checked' ) ); ?> value="false" type="radio" />
			<label for="show-full-description-false">No</label>
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						This option determines whether or not to show the full description by default.
					</p>
				</div>
			</div>
		</div>

		<?php
	}

	public function show_location_after_description() {

		?>
		<div class="show-location-after-description-wrapper">
			<input id="show-location-after-description-true" name="_bpt_show_location_after_description" <?php esc_attr_e( Utils::is_selected( 'true', '_bpt_show_location_after_description', 'checked' ) );?> value="true" type="radio" />
			<label for="show-location-after-description-true">Yes</label>
			<input id="show-location-after-description-false" name="_bpt_show_location_after_description" <?php esc_attr_e( Utils::is_selected( 'false', '_bpt_show_location_after_description', 'checked' ) ); ?> value="false" type="radio" />
			<label for="show-location-after-description-false">No</label>
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						This option determines whether or not to show location after the description, rather than before it.
					</p>
				</div>
			</div>
		</div>

		<?php
	}

	public function show_past_dates() {

		?>
		<div class="show-past-dates-wrapper">
			<input id="show-past-dates-true" name="_bpt_show_past_dates" <?php esc_attr_e( Utils::is_selected( 'true', '_bpt_show_past_dates', 'checked' ) );?> value="true" type="radio" />
			<label for="show-past-dates-true">Yes</label>
			<input id="show-past-dates-false" name="_bpt_show_past_dates" <?php esc_attr_e( Utils::is_selected( 'false', '_bpt_show_past_dates', 'checked' ) ); ?> value="false" type="radio" />
			<label for="show-past-dates-false">No</label>
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						If you would like to show past dates, select yes.
					</p>
				</div>
			</div>
		</div>

		<?php
	}

	public function show_sold_out_dates() {

		?>
		<div class="show-sold-out-dates-wrapper">
			<input id="show-sold-out-dates-true" name="_bpt_show_sold_out_dates" <?php esc_attr_e( Utils::is_selected( 'true', '_bpt_show_sold_out_dates', 'checked' ) );?> value="true" type="radio" />
			<label for="show-sold-out-dates-true">Yes</label>
			<input id="show-sold-out-dates-false" name="_bpt_show_sold_out_dates" <?php esc_attr_e( Utils::is_selected( 'false', '_bpt_show_sold_out_dates', 'checked' ) ); ?> value="false" type="radio" />
			<label for="show-sold-out-dates-false">No</label>
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						If you would like to show sold out dates, select yes.
					</p>
				</div>
			</div>
		</div>

		<?php
	}

	public function show_sold_out_prices() {

		?>
		<div class="show-sold-out-prices-wrapper">
			<input id="show-sold-out-prices-true" name="_bpt_show_sold_out_prices" <?php esc_attr_e( Utils::is_selected( 'true', '_bpt_show_sold_out_prices', 'checked' ) );?> value="true" type="radio" />
			<label for="show-sold-out-prices-true">Yes</label>
			<input id="show-sold-out-prices-false" name="_bpt_show_sold_out_prices" <?php esc_attr_e( Utils::is_selected( 'false', '_bpt_show_sold_out_prices', 'checked' ) ); ?> value="false" type="radio" />
			<label for="show-sold-out-prices-false">No</label>
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						If you would like to show sold out prices, select yes.
					</p>
				</div>
			</div>
		</div>

		<?php
	}

	public function include_service_fee() {
		?>

		<div class="include-service-fee-wrapper">
			<input id="include-service-fee-true" name="_bpt_include_service_fee" <?php esc_attr_e( Utils::is_selected( 'true', '_bpt_include_service_fee', 'checked' ) ); ?> value="true" type="radio" />
			<label for="include-service-fee-true">Yes</label>
			<input id="include-service-fee-true" name="_bpt_include_service_fee" <?php esc_attr_e( Utils::is_selected( 'false', '_bpt_include_service_fee', 'checked' ) ); ?> value="false" type="radio" />
			<label for="include-service-fee-true">No</label>
			<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
				<span>?</span>
				<div>
					<p>
						If you would like to include the Brown Paper Tickets service fees in your price total, select true.
					</p>
				</div>
			</div>
			<p class="bpt-help">This will override any individual price's service fee setting.</p>
		</div>
		<?php
	}

	public function hidden_prices() {

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
