<?php

namespace BrownPaperTickets;

use BrownPaperTickets\BPTFeed;
use BrownPaperTickets\BPTPlugin;

class BPTCalendarWidget extends \WP_Widget {


	public function __construct( ) {


		parent::__construct(
			'_bpt_widget_calendar',
			__( 'Brown Paper Tickets Calendar Widget', 'brown-paper-tickets-locale' ),
			array( 'description', __( 'Simple widget to display events in a calendar.', 'brown-paper-tickets-locale' ) )
		);


		if ( is_active_widget( false, false, $this->id_base, true ) ) {

			

		}
	}


	public function form( $instance ) {

		$title        = self::get_title( $instance );
		$display_type = self::get_display_type( $instance );
		$client_id    = self::get_client_id( $instance );

		?>

			<p>
				<label for="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label> 
				<input class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
				
				<label for="<?php esc_attr_e( $this->get_field_id( 'display_type' ) ); ?>"><?php _e( 'Display:' ); ?></label> 
				<select class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'display_type' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'display_type' ) ); ?>">
					<option value="<?php esc_attr_e( 'all_events' ); ?>" <?php selected( $display_type, 'all_events' ); ?>><?php  _e( 'All Your Events' ); ?></option>
					<option value="<?php esc_attr_e( 'producers_events' ); ?>" <?php selected( $display_type, 'producers_event' ); ?>><?php  _e( 'Another Producer\'s Events' ); ?></option>
				</select>

				<label for="<?php esc_attr_e( $this->get_field_id( 'client_id' ) ); ?>"><?php _e( 'Client ID:' ); ?></label>
				<input class="widefat" id="<?php esc_attr_e( $this->get_field_id( 'client_id' ) ); ?>" name="<?php esc_attr_e( $this->get_field_name( 'client_id' ) ); ?>" type="text" value="<?php echo esc_attr( $client_id ); ?>">

			</p>
		<?php
	}

	public function widget( $args, $instance ) {
		$title        = apply_filters( 'widget_title', self::get_title( $instance ) );
		$display_type = self::get_display_type( $instance );
		$client_id    = self::get_client_id( $instance );
		$widget_id    = $args['widget_id'];
		$date_format  = get_option( '_bpt_date_format' );
		$time_format  = get_option( '_bpt_time_format' );


		if ( is_active_widget( false, false, $this->id_base, true ) || $args['widget_id'] === 'shortcode' ) {

			BPTPlugin::load_ajax_required();

			wp_enqueue_style( 'bpt_calendar_widget_css', plugins_url( 'public/assets/css/bpt-calendar-widget.css', dirname( __FILE__ ) ), false, VERSION );
			wp_enqueue_script( 'ractive_transitions_fade_js', plugins_url( 'public/assets/js/ractive-transitions-fade.js', dirname( __FILE__ ) ), array(), false, true );
			wp_enqueue_script( 'bpt_clndr_min_js', plugins_url( 'public/assets/js/clndr.min.js', dirname( __FILE__ ) ), array( 'underscore' ), false, true ); 
			

			if ( $display_type === 'producers_events' ) {
				wp_enqueue_script( 'bpt_calendar_widget_shortcode_js', plugins_url( 'public/assets/js/bpt-calendar-widget-shortcode.js', dirname( __FILE__ ) ), array( 'jquery' ), false, true );
				wp_localize_script(
					'bpt_calendar_widget_shortcode_js', 'bptCalendarWidgetShortcodeAjax', array(
						'ajaxurl'     => admin_url( 'admin-ajax.php' ),
						'bptNonce'    => wp_create_nonce( 'bpt-calendar-widget-nonce' ),
						'clientID'    => $client_id,
						'widgetID'    => $widget_id,
					)
				);

			} else {

				wp_enqueue_script( 'bpt_calendar_widget_js', plugins_url( 'public/assets/js/bpt-calendar-widget.js', dirname( __FILE__ ) ), array( 'jquery' ), false, true );
				wp_localize_script(
					'bpt_calendar_widget_js', 'bptCalendarWidgetAjax', array(
						'ajaxurl'     => admin_url( 'admin-ajax.php' ),
						'bptNonce'    => wp_create_nonce( 'bpt-calendar-widget-nonce' ),
						'widgetID'    => $widget_id,
					)
				);
			}
		}

		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}

		?>
			<div class="<?php esc_attr_e( ( $widget_id === 'shortcode' ? 'bpt-calendar-widget-shortcode' : 'bpt-calendar-widget' ) ); ?>" data-bpt-widget-id="<?php esc_attr_e( $widget_id ); ?>">
				
			</div>
			<script type="text/html" id="bpt-calendar-widget-calendar-template">
				<div class="bpt-calendar-widget-controls">
				    <div class="bpt-calendar-widget-controls-button">
				        <span class="bpt-calendar-widget-controls-previous-button">&laquo;</span>
				    </div>
				    <div class="bpt-calendar-widget-controls-month">
						<%= month %> <%= year %>
				    </div>
				    <div class="bpt-calendar-widget-controls-button">
				        <span class="bpt-calendar-widget-controls-next-button">&raquo;</span>
				    </div>
				</div>
				<table class="bpt-calendar-widget-table" border="0" cellspacing="0" cellpadding="0">
				    <thead>
				        <tr class="bpt-calendar-widget-header-days">
				            <% for(var i = 0; i < daysOfTheWeek.length; i++ ) { %>
				            <td class="header-day">
				            	<%= daysOfTheWeek[i] %>
				            </td>
				            <% } %>
				        </tr>
				    </thead>
				    <tbody>
				        <% for(var i = 0; i < numberOfRows; i++){ %>
				        <tr class="bpt-calendar-widget-week-row">
				            <% for(var j = 0; j < 7; j++){ %>
				                <% var d = j + i * 7; %>
				            <td class="<%= days[d].classes %>">
				                <div class="bpt-calendar-widget-day-contents">
				                    <%= days[d].day %>
				                </div></td>
				            <% } %>
				        </tr>
				        <% } %>
				    </tbody>
				</table>
			</script>

			<div id="<?php esc_attr_e( ( $widget_id === 'shortcode' ? 'bpt-calendar-widget-shortcode-event-view' : 'bpt-calendar-widget-event-view' ) ); ?>" data-bpt-widget-id="<?php esc_attr_e( $widget_id ); ?>">

			</div>
			<script type="text/html" id="bpt-calendar-widget-event-view-template">
				{{ #currentEvents }}
				<div class="bpt-calendar-widget-event-box" intro="slide" outro="fade">
					<h3>{{ title }}</h3>
					<div class="location">
						{{ city }}, {{ state }}
					</div>
					<div class="date">
						{{ formatDate( '<?php echo $date_format; ?>', date ) }}
					</div>
					<div class="description">
						{{ shortDescription }}
					</div>
					<div class="buy-tickets">
						<a href="http://www.brownpapertickets.com/event/{{ eventID }}?date={{ dateID }}" target="_blank">Buy Tickets</a>
					</div>
				</div>
				{{ /currentEvents}}

				{{ ^currentEvents }}

				{{ #monthsEvents }}

				{{ /monthsEvents}}

				{{ /currentEvents }}

			</script>
		<?php

		echo wp_kses_post( $args['after_widget'] );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title']        = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['display_type'] = ( ! empty( $new_instance['display_type'] ) ) ? strip_tags( $new_instance['display_type'] ) : '';
		$instance['client_id']    = ( ! empty( $new_instance['client_id'] ) ) ? strip_tags( $new_instance['client_id'] ) : '';

		return $instance;
	}


	private static function get_title( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {

			$title = $instance[ 'title' ];

		} else {

			$title = __( 'New title', 'brown-paper-tickets-locale' );
		}

		return $title;
	}

	private static function get_display_type( $instance ) {

		if ( isset( $instance['display_type'] ) ) {

			$display_type = $instance[ 'display_type' ];

		} else {

			$display_type = 'all_events';

		}

		return $display_type;
	}

	private static function get_client_id( $instance ) {
		if ( isset( $instance['client_id'] ) ) {

			$client_id = $instance[ 'client_id' ];

		} else {

			$client_id = null;

		}

		return $client_id;
	}


}

class BPTEventListWidget extends \WP_Widget {


	public function __construct() {
		parent::__construct(
			'_bpt_widget_event_list',
			__( 'Brown Paper Tickets Event List Widget', 'brown-paper-tickets-locale' ),
			array( 'description', __( 'Simple widget to display events in a calendar.', 'brown-paper-tickets-locale' ) )
		);
	}
}
