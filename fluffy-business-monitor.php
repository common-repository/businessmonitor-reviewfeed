<?php
/**
 * Plugin Name: BusinessMonitor-ReviewfeedShortcodes
 * Plugin URI: https://businessmonitor.nl/technologie
 * Description: Plugin that adds reviews from BusinessMonitor to your website using shortcodes
 * Version: 1.0.16
 * Author: Salesforce up to data b.v.
 * Author URI: https://businessmonitor.nl/contact
 * Text Domain: BusinessMonitor
 */

require_once(__DIR__ . '/vendor/autoload.php');
$loader_instance = new \FluffyMedia\BusinessMonitor\Loader(__FILE__);