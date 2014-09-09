<?php

require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-api.php');
require_once( plugin_dir_path( __FILE__ ).'../inc/brown-paper-tickets-plugin.php');

use BrownPaperTickets\BPTFeed;
use BrownPaperTickets\BPTPlugin;

$events = new BPTFeed;

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
			<!-- <li><a class="bpt-admin-tab" href="#purchase-settings">Purchase Settings</a></li> -->
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
		<div id="purchase-settings">
			<div>
			<h2>In a future release you will be able to enable sales via the plugin.</h2>
	<?php

if ( ! is_ssl() ) {

	?>
						<h3 class="error">Sorry, you must connect via SSL (HTTPS) in order to use this option.</h3>
						<p>
							Without SSL on your site, you would be enabling your ticket buyers to submit their Credit Card without any sort of security.
						</p>
						<p>
							You'll want to contact your web host or your web person in order to get SSL set up.
						</p>
	<?php      
} else {

	do_settings_sections( $menu_slug . '_purchase' );

	?>
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
	<?php
}
	?>
			</div>
		</div>
		<div id="help">
			<h1>Help</h1>
			<p>
				<h2>F.A.Q.</h2>
				<ul>
					<li>
						<h3>I've updated some of my event's on Brown Paper Tickets but the changes are not showing up in the plugin. Why is that?</h3>
						<p>
							You most likely have enabled the plugin the cache the data the plugin pulls in from Brown Paper Tickets. 
						</p>
						<p>
							There are a few ways to solve this:
							<ol>
								<li>You could wait for the cache to expire.</li>
								<li>You could delete the cache and force the plugin to refresh the data.</li>
							</ol>
						</p>
						<p>To delete the cache, simply go to the "General Settings" tab above and click "Delete Cache".</p>
					</li>
					<li>
						<h3>I am 100% certain that my developer ID and client ID are correct. What is going on?</h3>
						<p>It's possible that your client ID is not attached to your developer tools.</p>

						<p>To add your account:</p>

						<ol>
							<li>Go to <a target="_blank" href="https://www.brownpapertickets.com/developer/accounts.html">Authorized Accounts</a>.</li>
							<li>If your account is listed under "Current Account", click "Edit" and then "Delete Account".</li>
							<li>On the next screen, under "Add a Client" enter in your username and password, select the permissions you need and hit "Add Client Account".</li>
							<li>Your account should now be authorized.</li>
						</ol>

						<p>If you are still having issues, send an email to <a href="mailto:support@brownpaperticekts.com">support@brownpapertickets.com</a></p>
					</li>
					<li>
						<h3>My password protected prices are being displayed by the plugin, how do I prevent that?</h3>

						<p>Unfortunately, you cannot at this time.</p>
						<p>The ability to prevent specific prices from being displayed is a top priority for the next release</p>
					</li>
					<li>
						<h3>How can I customize the look and feel of the event list or the calendar?</h3>

						<p>
							At the moment, not easily. You could edit the event-list's style sheet directly (located plugin directory under 
							<pre class="bpt-inline">brown-paper-tickets/assets/css/event-list-short-code.css</pre>).
						</p>

						<p>The ability to easily add your custom style sheet is a top priority for the next release.</p>
					</li>
					<li>
				</ul>
			</p>
			<h3>Setup Wizard</h3>
			<ul>
				<li>Go To <a href="http://localhost/bptwp/wp-admin/admin.php?page=brown_paper_tickets_settings_setup_wizard">Setup Wizard</a></li>
			</ul>
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