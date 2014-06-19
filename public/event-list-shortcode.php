<?php

/**
 * The ShortCode HTML Container.
 * Contents will be populated by javascript after successfull ajax call.
 */

/**
 * This file keeps the logic to a bare minimum. It simply checks for certain
 * options that need to be displayed at this level and displays it.
 *
 * For example, if the user has decided to show the full event description
 * by default. checks that variable and displays the necessary html.
 *
 *
 * @var [type]
 */
$_bpt_show_prices = get_option( '_bpt_show_prices' );
$_bpt_show_dates = get_option( '_bpt_show_dates' );
$_bpt_show_full_description = get_option( '_bpt_show_full_description' );
$_bpt_show_location_after_description = get_option( '_bpt_show_location_after_description' );
$_bpt_shipping_countries    = get_option( '_bpt_shipping_countries' );
$_bpt_shipping_methods = get_option( '_bpt_shipping_methods' );
$_bpt_currency = get_option( '_bpt_currency' );
$_bpt_date_format = esc_html( get_option( '_bpt_date_format' ) );
$_bpt_time_format = esc_html( get_option( '_bpt_time_format' ) );
$_bpt_show_end_time = get_option( '_bpt_show_end_time' );

//$_bpt_event_list_template = get_option( '_bpt_show_event_list_template' );

if ( $_bpt_date_format === 'custom' ) {
	$_bpt_date_format = esc_html( get_option( '_bpt_custom_date_format' ) );
}

if ( $_bpt_time_format === 'custom' ) {
	$_bpt_time_format = esc_html( get_option( '_bpt_custom_time_format' ) );
}

if ( $_bpt_currency === 'cad' ) {
	$_bpt_currency = 'CAD$ ';
}

if ( $_bpt_currency === 'usd' ) {
	$_bpt_currency = '$';
}

if ( $_bpt_currency === 'gbp' ) {
	$_bpt_currency = '£';
}

if ( $_bpt_currency === 'eur' ) {
	$_bpt_currency = '€';
}

$countries = array(
	'Afghanistan',
	'Aland Islands',
	'Albania',
	'Algeria',
	'American Samoa',
	'Andorra',
	'Angola',
	'Anguilla',
	'Antarctica',
	'Antigua And Barbuda',
	'Argentina',
	'Armenia',
	'Aruba',
	'Australia',
	'Austria',
	'Azerbaijan',
	'Azores',
	'Bahamas',
	'Bahrain',
	'Bangladesh',
	'Barbados',
	'Belarus',
	'Belgium',
	'Belize',
	'Benin',
	'Bermuda',
	'Bhutan',
	'Bolivia',
	'Bosnia And Herzegovina',
	'Botswana',
	'Bouvet Island',
	'Brazil',
	'British Indian Ocean Territory',
	'Brunei Darussalam',
	'Bulgaria',
	'Burkina Faso',
	'Burundi',
	'Cambodia',
	'Cameroon',
	'Canada',
	'Cape Verde',
	'Cayman Islands',
	'Central African Republic',
	'Chad',
	'Chile',
	'China',
	'Christmas Island',
	'Cocos (keeling) Islands',
	'Colombia',
	'Comoros',
	'Congo',
	'Congo, The Democratic Republic Of The',
	'Cook Islands',
	'Costa Rica',
	'Cote Divoire',
	'Croatia',
	'Cyprus',
	'Czech Republic',
	'Denmark',
	'Djibouti',
	'Dominica',
	'Dominican Republic',
	'Ecuador',
	'Egypt',
	'El Salvador',
	'Equatorial Guinea',
	'Eritrea',
	'Estonia',
	'Ethiopia',
	'Falkland Islands',
	'Faroe Islands',
	'Fiji',
	'Finland',
	'France',
	'French Guiana',
	'French Polynesia',
	'French Southern Territories',
	'Gabon',
	'Gambia',
	'Georgia',
	'Germany',
	'Ghana',
	'Gibraltar',
	'Greece',
	'Greenland',
	'Grenada',
	'Guadeloupe',
	'Guam',
	'Guatemala',
	'Guernsey',
	'Guinea',
	'Guinea-Bissau',
	'Guyana',
	'Haiti',
	'Heard Island And Mcdonald Islands',
	'Holy See',
	'Honduras',
	'Hong Kong',
	'Hungary',
	'Iceland',
	'India',
	'Indonesia',
	'Iraq',
	'Ireland',
	'Isle Of Man',
	'Israel',
	'Italy',
	'Jamaica',
	'Japan',
	'Jersey',
	'Jordan',
	'Kazakhstan',
	'Kenya',
	'Kiribati',
	'Korea, Republic Of',
	'Kosovo',
	'Kyrgyzstan',
	'Latvia',
	'Lebanon',
	'Lesotho',
	'Liberia',
	'Libyan Arab Jamahiriya',
	'Liechtenstein',
	'Lithuania',
	'Luxembourg',
	'Macao',
	'Macedonia, The Former Yugoslav Republic Of',
	'Madagascar',
	'Madeira',
	'Malawi',
	'Malaysia',
	'Maldives',
	'Mali',
	'Malta',
	'Marshall Islands',
	'Martinique',
	'Mauritania',
	'Mauritius',
	'Mayotte',
	'Mexico',
	'Micronesia, Federated States Of',
	'Moldova',
	'Monaco',
	'Mongolia',
	'Montenegro',
	'Montserrat',
	'Morocco',
	'Mozambique',
	'Myanmar',
	'Namibia',
	'Nauru',
	'Nepal',
	'Netherlands',
	'Netherlands Antilles',
	'New Caledonia',
	'New Zealand',
	'Nicaragua',
	'Niger',
	'Nigeria',
	'Niue',
	'Norfolk Island',
	'Northern Mariana Islands',
	'Norway',
	'Oman',
	'Pakistan',
	'Palau',
	'Palestinian Territory, Occupied',
	'Panama',
	'Papua New Guinea',
	'Paraguay',
	'Peru',
	'Philippines',
	'Pitcairn',
	'Poland',
	'Portugal',
	'Puerto Rico',
	'Qatar',
	'Réunion',
	'Romania',
	'Russian Federation',
	'Rwanda',
	'Saint Barthélemy',
	'Saint Helena',
	'Saint Kitts And Nevis',
	'Saint Lucia',
	'Saint Martin',
	'Saint Pierre And Miquelon',
	'Saint Vincent And The Grenadines',
	'Samoa',
	'San Marino',
	'Sao Tome And Principe',
	'Saudi Arabia',
	'Senegal',
	'Serbia',
	'Seychelles',
	'Sierra Leone',
	'Singapore',
	'Slovakia',
	'Slovenia',
	'Solomon Islands',
	'Somalia',
	'South Africa',
	'South Georgia And The South Sandwich Islands',
	'Spain',
	'Sri Lanka',
	'Suriname',
	'Svalbard And Jan Mayen',
	'Swaziland',
	'Sweden',
	'Switzerland',
	'Taiwan',
	'Tajikistan',
	'Tanzania, United Republic Of',
	'Thailand',
	'Timor-Leste',
	'Togo',
	'Tokelau',
	'Tonga',
	'Trinidad And Tobago',
	'Tunisia',
	'Turkey',
	'Turkmenistan',
	'Turks And Caicos Islands',
	'Tuvalu',
	'Uganda',
	'Ukraine',
	'United Arab Emirates',
	'United Kingdom',
	'United States',
	'United States Minor Outlying Islands',
	'Uruguay',
	'Uzbekistan',
	'Vanuatu',
	'Venezuela',
	'Vietnam',
	'Virgin Islands, British',
	'Virgin Islands, US',
	'Wallis And Futuna',
	'Western Sahara',
	'Yemen',
	'Zambia',
	'Zimbabwe',
);

?>

<div class="bpt-loading-<?php esc_attr_e( $post->ID );?> hidden">
	Loading Events
	<br />
	<img src="<?php echo esc_url( plugins_url( 'public/assets/img/loading.gif', dirname( __FILE__ ) ) ); ?>">
</div>

<div id="bpt-event-list-<?php esc_attr_e( $post->ID );?>">


</div>
<script type="text/html" id="bpt-event-template">

{{ #bptError }}
	<div intro="slide" class="bpt-event bpt-default-theme">
	<h2>Sorry, an error has occured while loading events.</h2>
	<p>{{ error }}</p>
{{ /bptError }}

{{ #bptEvents }}
	{{ ^.error }}
	<div intro="slide" class="bpt-event bpt-default-theme">
		<h2 class="bpt-event-title">{{ title }}</h2>
		

		<?php if ( $_bpt_show_location_after_description === 'false' ) { ?>

			<div class="bpt-event-location">
				<div class="address1">{{ address1 }}</div>
				<div class="address2">{{ address2 }}</div>
				<div>
					<span class="city">{{ city }}</span>, <span class="state">{{ state }}</span> <span class="zip">{{ zip }}</span>
				</div>
			</div>

		<?php } ?>

		<div class="bpt-event-short-description">
			<p>
				{{ shortDescription }}
				<br />
			</p>
		</div>

		<?php if ( $_bpt_show_full_description === 'false' ) { ?>
		<p>
			<a href="#" class="bpt-show-full-description" on-click="showFullDescription">Show Full Description</a>
		</p>
		<div class="bpt-event-full-description hidden">
			<p>{{{ unescapeHTML(fullDescription) }}}</p>
		</div>

		<?php } else { ?>

		<div class="bpt-event-full-description">
			<p>{{{ unescapeHTML(fullDescription) }}}</p>
		</div>

		<?php }

	if ( $_bpt_show_location_after_description === 'true' ) { ?>

			<div class="bpt-event-location">
				<div class="address1">{{ address1 }}</div>
				<div class="address2">{{ address2 }}</div>
				<div>
					<span class="city">{{ city }}</span>, <span class="state">{{ state }}</span> <span class="zip">{{ zip }}</span>
				</div>
			</div>

		<?php }

	if ( $_bpt_show_dates === 'true' ) { ?>
			<form method ="post" class="add-to-cart" action="http://www.brownpapertickets.com/addtocart.html" target="_blank">
				<input type="hidden" name="event_id" value="{{ id }}" />
				<div class="event-dates">
					<label for="dates-{{ id }}">Select a Date:</label>
					<select class="bpt-date-select" id="dates-{{ id }}" value="{{ .selectedDate }}">
					{{ #dates }}
						<option class="event-date" value="{{ . }}" >
							{{ formatDate( '<?php esc_attr_e( $_bpt_date_format ); ?>', dateStart ) }}
							{{ formatTime( '<?php esc_attr_e( $_bpt_time_format ); ?>', timeStart ) }}
							<?php echo ( $_bpt_show_end_time === 'true' ? 'to {{ formatTime( \'' . $_bpt_time_format . '\', timeEnd ) }}' : '' ); ?>
						</option>
					{{ /dates }}
					</select>
				</div>
				<fieldset>
				{{ #selectedDate }}
					<input name="date_id" value="{{ id }}" type="hidden">
					<table id="price-list-{{ id }}">
					<tr>
						<th>Price Name</th>
						<th>Price Value</th>
						<th>Quantity</th>
					</tr>
					{{ #prices }}
					<tr>
						<td>{{ name }}</td>
						<td>{{ formatPrice(value, '<?php esc_attr_e( $_bpt_currency ); ?>' ) }}</td>
						<td>
							<select class="bpt-shipping-qty" name="price_{{ id }}">

		<?php
		$shipping_incr = 0;

		while ( $shipping_incr <= 50 ) {
			echo '<option value="' . $shipping_incr . '">' . $shipping_incr . '</option>';
			$shipping_incr++;
		}
		?>
							</select>
						</td>
					</tr>
					{{ /}}
					</table>
					<div class="shipping-info">
						<label for="shipping_{{ id }}">Delivery Method</label>
						<select class="bpt-shipping-method" id="shipping_{{ id }}" name="shipping_{{ id }}">
		<?php
		foreach ( $_bpt_shipping_methods as $shipping_method ) {
			switch ( $shipping_method ) {
				case 'print_at_home':
					echo '<option value="5">Print-At-Home (No Additional Fee)</option>';
					break;

				case 'will_call':
					echo '<option value="4">Will-Call (No additional fee!)</option>';
					break;

				case 'physical':
					echo '<option value="1">Physical Tickets - USPS 1st Class (No additional fee!)</option>';
					echo '<option value="2">Physical Tickets - USPS Priority Mail ($5.05)</option>';
					echo '<option value="3">Physical Tickets - USPS Express Mail ($18.11)</option>';
					break;
			}
		}
		?>
						</select>
						<br />
						<label class="bpt-shipping-country-label" for="country-id-{{ id }}">Delivery Country</label>
						<select class="bpt-shipping-country" id="country-id-{{   id }}" name="country_id">
		<?php
								$country_incr = 1;
		foreach ( $countries as $country ) {

			if ( $country === 'Azores' ) {
				echo '<option value="243"' . ($country === get_option( '_bpt_shipping_countries' ) ? 'selected' : '' ) . '>' . $country . '</option>';

				continue;
			}

				echo '<option value="' . $country_incr . '"' . ($country === get_option( '_bpt_shipping_countries' ) ? 'selected' : '' ) . '>' . $country . '</option>';
				$country_incr++;
		}
		?>
						</select>
					</div>
				{{ / }}
				</fieldset>
				<div class="bpt-event-footer">
					<div class="bpt-add-to-cart">
						<button class="bpt-submit" type="submit">Add to Cart</button>
						<span class="bpt-cc-logos">
							<img src="<?php echo esc_url( plugins_url( 'public/assets/img/visa_icon.png', __DIR__ ) ); ?>" />
							<img src="<?php echo esc_url( plugins_url( 'public/assets/img/mc_icon.png', __DIR__ ) ); ?>" />
							<img src="<?php echo esc_url( plugins_url( 'public/assets/img/discover_icon.png', __DIR__ ) ); ?>" />
							<img src="<?php echo esc_url( plugins_url( 'public/assets/img/amex_icon.png', __DIR__ ) ); ?>" />
						</span>
					</div>
				</div>
			</form>
		<?php } ?>
		<div class="bpt-powered-by">
			<a href="http://www.brownpapertickets.com/event/{{ id }}" target="_blank"><span>View Event on </span><img src="<?php echo esc_url( plugins_url( 'public/assets/img/bpt-footer-logo.png', __DIR__ ) ); ?>" /></a>
		<div>
	</div>
	{{ /.error }}
{{ /bptEvents }}
</script>
