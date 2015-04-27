<?php

require_once( plugin_dir_path( __FILE__ ).'../src/brown-paper-tickets-plugin.php');

use BrownPaperTickets\BPTFeed;
use BrownPaperTickets\BPTPlugin;

$menu_slug = BPTPlugin::get_menu_slug();
$plugin_slug    = BPTPlugin::get_plugin_slug();
$plugin_version = BPTPlugin::get_plugin_version();
?>
<h1>
	<img src="<?php echo esc_url( plugins_url( 'public//assets/img/bpt.png', dirname( __FILE__ ) ) )?>">
</h1>

<div class="wrap">
	<div class="bpt-welcome-panel">
		<div class="bpt-welcome-panel-content">
		</div>
		<div class="bpt-welcome-panel-content">
			<h2>Please be aware that this plugin is a beta release. You may encounter errors and bugs.</h2>
			<p>
				If you are having issues with your Client ID or your Developer ID, please email <a href="mailto:support@brownpapertickets.com">support@brownpapertickets.com</a>.
			</p>

			<h3>
				If you would like to request a new feature or if you have encountered a bug, please go <a target="_blank" href="https://github.com/BrownPaperTickets/brown-paper-tickets-wordpress/issues/new">here</a></span> to open up a new issue.
			</h3>
		</div>
		<span class="bpt-welcome-info-plugin-info">Plugin Info: <?php esc_html_e( $plugin_slug . ' v' . $plugin_version ); ?> - <a class="bpt-submit-issue" target="_blank" href="https://github.com/BrownPaperTickets/brown-paper-tickets-wordpress/issues/new">Submit Bug</a></span>
	</div>
	<nav id="<?php esc_attr_e( $menu_slug );?>">
		<ul>
			<li><a class="bpt-admin-tab" href="#usage">Usage</a></li>
			<li><a class="bpt-admin-tab" href="#account-setup">Account Setup</a></li>
			<li><a class="bpt-admin-tab" href="#general-settings">General Settings</a></li>
			<li><a class="bpt-admin-tab" href="#event-settings">Event List Settings</a></li>
			<li><a class="bpt-admin-tab" href="#calendar-settings">Calendar Settings</a></li>
			<li><a class="bpt-admin-tab" href="#appearance-settings">Appearance</a></li>
			<?php echo ( is_ssl() ? '<li><a class="bpt-admin-tab" href="#purchase-settings">Purchase Settings</a></li>' : '' ); ?>
			<li><a class="bpt-admin-tab" href="#help">Help</a></li>
			<li><a class="bpt-admin-tab" href="#credits">Credits</a></li>
			<!-- <li><a class="bpt-admin-tab" href="#debug">Debug</a></li> -->
		</ul>
	</nav>
	<form method="post" action="options.php">
	<?php settings_fields( $menu_slug ); ?>
	<div id="bpt-settings-wrapper">
		<div id="usage">
			<h1>Plugin Usage</h1>
			<p class="bpt-jumbotron">This plugin allows you to display your events within your wordpress posts or using a widget</p>
			<h2>Shortcodes</h2>
			<p>Simply place one of the shortcodes where you want it in a post or page.</p>
			<table>
				<tr>
					<th>Action</th>
					<th>Shortcode</th>
					<th>Description</th>
				</tr>
				<tr>
					<td>List all of your events:</td>
					<td><pre class="bpt-inline">[list_events]</pre></td>
					<td>This will display all of your events in a ticket widget format.</td>
				</tr>
				<tr>
					<td>List a single event:</td>
					<td><pre class="bpt-inline">[list_event event_id="EVENT_ID"]</pre></td>
					<td>This will display a single event. EVENT_ID is the ID of the event you wish to display.</td>
				</tr>
				<tr>
					<td>List another producer's events:</td>
					<td><pre class="bpt-inline">[list_event client_id="CLIENT_ID"]</pre></td>
					<td>This will display the events of the producer listed.</td>
				</tr>
				<tr>
					<td>Display Calendar in Page/Post:</td>
					<td><pre class="bpt-inline">[event_calendar client_id="CLIENT_ID"]</pre></td>
					<td>This will display the events of the producer listed. The Client ID is optional.</td>
				</tr>
<!-- 				<tr>
					<td><pre class="bpt-inline">[list-events-links]</pre></td>
					<td>This will simply generate a list of links to your events.</td>
				</tr> -->
			</table>
			<h2>Widgets</h2>
			<ul>
				<li>Calendar Widget. Display Events in a Calendar. Go to <a href="widgets.php">Widgets to enable.</a></li>

			</ul>
		</div>
		<div id="account-setup">
			<div>
				<?php do_settings_sections( $menu_slug . '_api' ); ?>
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</div>
		</div>
		<div id="general-settings">
			<div>
				<?php do_settings_sections( $menu_slug . '_general' ); ?>
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</div>
		</div>
		<div id="event-settings">
			<div>
				<?php do_settings_sections( $menu_slug . '_event' ); ?>
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</div>
		</div>
		<div id="calendar-settings">
			<div>
				<?php do_settings_sections( $menu_slug . '_calendar' ); ?>
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</div>
		</div>
		<div id="appearance-settings">
			<div>
				<?php do_settings_sections( $menu_slug . '_appearance' ); ?>
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</div>
		</div>
		<div id="purchase-settings">
			<div>
				<?php do_settings_sections( $menu_slug . '_purchase' ); ?>
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</div>
		</div>
		<div id="help">
			<div>
				<?php do_settings_sections( $menu_slug . '_help' ); ?>
			</div>
		</div>
		<div id="credits">
			<h3>Credits</h3>
			<p>This plugin makes use of Free Software</p>
			<div>
				<ul>
					<li><a href="http://www.jquery.com" target="blank">jQuery</a></li>
					<li><a href="http://underscorejs.org/" target="_blank">Underscore</a></li>
					<li><a href="http://kylestetz.github.io/CLNDR/" target="_blank">CLNDR.js</a></li>
					<li><a href="http://www.ractivejs.org/" target="_blank">Ractive.js</a></li>
					<li><a href="http://momentjs.com/" target="_blank">Moment.js</a></li>
				</ul>
			</div>
		</div>
		<div class="plugin-debug">

		</div>

	</div>
	</form>
</div>

<script type="text/ractive" id="bpt-welcome-panel-template">
	{{ #account }}
	<h1>Hi, {{ firstName }}</h1>
	{{ /account}}
	<div class="bpt-status-box">

	</div>
	{{ #request }}
		{{ #message }}
			<div class="bpt-message-box">
				<p class="{{ result === false ? 'bpt-error-message' : 'bpt-success-message' }} ">{{ message }} </p>
			</div>
		{{ /message}}
	{{ /request }}
</script>