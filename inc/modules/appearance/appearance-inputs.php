<?php

/**
 * Brown Paper Tickets Account Settings Fields HTML
 *
 * Here lies the callbacks for the add_settings_fields() function.
 */
namespace BrownPaperTickets\Modules\Appearance;

require_once( plugin_dir_path( __FILE__ ).'../../../inc/brown-paper-tickets-plugin.php');

use BrownPaperTickets\BPTPlugin;

class Inputs {
	public static function section() {
		?>
		<div class="bpt-appearance">
			<p>Customize the appearance of the event widget and the calendar.</p>
			<a href="#" id="bpt-appearance-show-event-list-help">Show Event List Help</a>
			<br />
			<a href="#" id="bpt-appearance-show-calendar-help">Show Calendar Help</a>
			<div id="bpt-appearance-help">
				<div id="bpt-appearance-help-event-list">
					<h3>Event List</h3>
					<h4>Selectors</h4>
					<table>
						<thead>
							<tr>
								<th>selector</th>
								<th>description</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><code>h2.bpt-event-title</code></td>
								<td>The title of the event</td>
							</tr>
							<tr>
								<td><code>div.bpt-event-location</code></td>
								<td>The event location box.</td>
							</tr>
							<tr>
								<td><code>div.bpt-event-short-description</code></td>
								<td>The short description box.</td>
							</tr>
							<tr>
								<td><code>div.bpt-event-full-description</code></td>
								<td>The full description box.</td>
							</tr>
							<tr>
								<td><code>select.bpt-date-select</code></td>
								<td>The date selection dropdown.</td>
							</tr>
							<tr>
								<td><code>table.bpt-event-list-prices</code></td>
								<td>The price listing table.</td>
							</tr>
							<tr>
								<td><code>td.bpt-price-name</code></td>
								<td>The price's name.</td>
							</tr>
							<tr>
								<td><code>td.bpt-price-value</code></td>
								<td>The price's value.</td>
							</tr>
							<tr>
								<td><code>select.bpt-shipping-qty</code></td>
								<td>The price quantity drop down.</td>
							</tr>
							<tr>
								<td><code>label.bpt-shipping-method</code></td>
								<td>The delivery method label.</td>
							</tr>
							<tr>
								<td><code>select.bpt-shipping-method</code></td>
								<td>The delivery method dropdown.</td>
							</tr>
							<tr>
								<td><code>label.bpt-shipping-country</code></td>
								<td>The delivery country label.</td>
							</tr>
							<tr>
								<td><code>select.bpt-shipping-country</code></td>
								<td>The delivery country dropdown.</td>
							</tr>
							<tr>
								<td><code>div.bpt-add-to-cart</code></td>
								<td>The add to cart box.</td>
							</tr>
							<tr>
								<td><code>span.bpt-cc-logos</code></td>
								<td>The credit card logo section.</td>
							</tr>
							<tr>
								<td><code>button.bpt-submit</code></td>
								<td>The "Add to Cart" button.</td>
							</tr>
							<tr>
								<td><code>div.bpt-powered-by</code></td>
								<td>The powered by box.</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="bpt-appearance-help-calendar">
					<h3>Calendar</h3>
					<p>
					Because the calendar can be placed either as a shortcode or a widget,
					it will be wrapped in either a div with the class <code>bpt-calendar-shortcode</code>
					or <code>bpt-calendar-widget</code>.
					</p>
					<p>Use of the following selectors will change the
					style of both unless the wrapper is specified. Eg. <code>div.bpt-calendar-shortcode div.bpt-calendar-widget-controls</code>
					</p>
					<h4>Selectors</h4>
					<table>
						<thead>
							<tr>
								<th>selector</th>
								<th>description</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><code>div.bpt-calendar-widget</code> or <code>div.bpt-calendar-shortcode</code></td>
								<td>The entire calendar container.</td>
							</tr>
							<tr>
								<td><code>div.bpt-calendar-widget-controls</code></td>
								<td>The calendar controls wrapper.</td>
							</tr>
							<tr>
								<td><code>div.bpt-calendar-widget-controls-month</code></td>
								<td>The calendar controls month.</td>
							</tr>
							<tr>
								<td><code>table.bpt-calendar-widget-table</code></td>
								<td>The calendar table.</td>
							</tr>
							<tr>
								<td><code>tr.bpt-calendar-widget-header-days</code></td>
								<td>The calendar's days header</td>
							</tr>
							<tr>
								<td><code>td.bpt-calendar-widget-header-day</code></td>
								<td>Individual day in the calendar day header.</td>
							</tr>
							<tr>
								<td><code>tr.bpt-calendar-widget-week-row</code></td>
								<td>The week rows.</td>
							</tr>
							<tr>
								<td><code>div.bpt-calendar-widget-event-view</code></td>
								<td>The event details box.</td>
							</tr>
							<tr>
								<td><code>div.bpt-calendar-widget-event-view h1</code></td>
								<td>The event details header (eg "Upcoming Events"...)</td>
							</tr>
							<tr>
								<td><code>div.bpt-calendar-widget-event-view-event</code></td>
								<td>The individual event details box.</td>
							</tr>
							<tr>
								<td><code>div.bpt-calendar-widget-event-view-location</code></td>
								<td>The event's location.</td>
							</tr>
							<tr>
								<td><code>div.bpt-calendar-widget-event-view-date</code></td>
								<td>The event's date</td>
							</tr>
							<tr>
								<td><code>div.bpt-calendar-widget-event-view-buy-tickets</code></td>
								<td>The event's buy tickets link.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php
	}

	public function event_list() {
		$options = get_option( '_bpt_event_list_style' );

		$use_style = ( isset( $options['use_style'] ) ? $options['use_style'] : false );

		$custom_css = ( isset( $options['custom_css'] ) ? $options['custom_css'] : '' );

		?>
		<div class="event-list-appearance-wrapper">
			<p>
				<label for="bpt-event-list-use-style">Use Custom Style</label>
				<input id="bpt-event-list-use-style" class="checkbox" name="_bpt_event_list_style[use_style]" type="checkbox" value="true" <?php esc_attr_e( ($use_style ? 'checked' : '' ) ); ?> >
				<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
					<span>?</span>
					<div>
						<p>
							If you would like to use a custom style, check this box.
						</p>
					</div>
				</div>
			</p>
			<div id="bpt-event-list-css">
				<label for="bpt-event-list-css-box">Custom CSS</label>
				<textarea id="bpt-event-list-css-box" name="_bpt_event_list_style[custom_css]"><?php esc_html_e( $custom_css ); ?></textarea>
			</div>
		</div>
		<?php
	}

	public function calendar() {
		$calendar_options = get_option( '_bpt_calendar_style' );

		if ( isset( $calendar_options['use_style'] ) && isset( $calendar_options['custom_css'] ) ) {
			$use_calendar_style = $calendar_options['use_style'];
			$calendar_css = $calendar_options['custom_css'];
		} else {
			$use_calendar_style = false;
			$calendar_css = '';
		}
		?>
		<div class="event-list-appearance-wrapper">
			<p>
				<label for="bpt-calendar-use-style">Use Custom Style</label>
				<input id="bpt-calendar-use-style" class="checkbox" name="_bpt_calendar_style[use_style]" type="checkbox" value="true" <?php esc_attr_e( ($use_calendar_style ? 'checked' : '' ) ); ?> >
				<div class="<?php esc_attr_e( BPTPlugin::get_menu_slug() ); ?>_help">
					<span>?</span>
					<div>
						<p>
							If you would like to use a custom style, check this box.
						</p>
					</div>
				</div>
			</p>
			<div id="bpt-calendar-css" >
				<label for="bpt-calendar-css-box">Custom CSS</label>
				<textarea id="bpt-calendar-css-box" name="_bpt_calendar_style[custom_css]"><?php esc_html_e( $calendar_css ); ?></textarea>
			</div>
		</div>
		<?php
	}
}