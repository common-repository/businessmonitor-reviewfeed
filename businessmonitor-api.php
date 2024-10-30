<?php
/*
Plugin Name: BusinessMonitor-API connector
Plugin URI: https://businessmonitor.nl/koppelingen-en-api/
Description: BusinessMonitor API connector
Version: 1.0.16
Author: Salesforce up to data b.v.
Author URI: https://businessmonitor.nl/contact

changelog
1.0.4 aligned all version numbers for all plugin parts
*/

# loads the config screen
if(!defined('BUSINESSMONITOR__PLUGIN_DIR'))
{
define( 'BUSINESSMONITOR__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

require_once( BUSINESSMONITOR__PLUGIN_DIR . 'businessmonitor-admin.php' );

function businessmonitor_hello() {
	$setting = get_option('BusinessMonitor_options');
	#check if api configuration exists
	if (empty($setting['BusinessMonitor_field_apikey']))
	{
		// if the api key is not configured display the message
		echo "<p id='businessmonitorInfo'><b>!WARNING!</b> BusinessMonitor plugin enabled, but not configured. <a href='admin.php?page=BusinessMonitor'>Configure plugin</a></p>";
	}
}

if (is_admin()) {
    // we are in admin mode
		// Now we set that function up to execute when the admin_notices action is called
		add_action( 'admin_notices', 'businessmonitor_hello' );
		// We add some css to highlight the notice
		add_action( 'admin_head', 'businessmonitor_css' );
}

// We need some CSS to position the paragraph
function businessmonitor_css() {
	$setting = get_option('BusinessMonitor_options');
	if (empty($setting['BusinessMonitor_field_apikey']))
	{
		echo "<style type='text/css'>
		#businessmonitorInfo {background-color:yellow;border:2px solid #000;color:#000;padding:5px;line-height:2em;}
		</style>";
	}
}
?>