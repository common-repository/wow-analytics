<?php
/*
Plugin Name: WOW Analytics Tracker
Plugin URI: http://wordpress.org/extend/plugins/wow-analytics/
Description: Inserts the WOW Analytics tracker into the footer of Wordpress pages
Version: 2.2
Author: WOW Analytics
Author URI: http://www.wowanalytics.co.uk
*/
/* Copyright 2012 WOW Analytics (email : support@wowanalytics.co.uk)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/
define('WOWANALYTICS_VERSION', '2.1.1');

require_once(dirname(__FILE__).'/includes/trackingcode.php');
require_once(dirname(__FILE__).'/install.php');


// only include the admin functionality if the user is an admin
if ( is_admin() ){
	require_once(dirname(__FILE__).'/admin.php');
}


/* Runs when plugin is activated */
register_activation_hook(__FILE__, 'wowanalytics_install');
//register_deactivation_hook(__FILE__, 'wowanalytics_deactivate');

// setup the options
add_action( 'plugins_loaded', 'wo_wowanalytics_plugin_setup' );


function wo_wowanalytics_plugin_setup(){
	add_action('wp_head', 'wow_wowanalytics_should_output_trackingcode');

}

function wowanalytics_deactivate(){
	//delete_option('wow_wowanalytics_options');
}

// Lets not do anything until init
add_action( 'init', 'wow_wowanalytics_test_head_footer_init' );
function wow_wowanalytics_test_head_footer_init() {
    // Hook in at admin_init to perform the check for wp_head and wp_footer
    //add_action( 'admin_init', 'check_head_footer' );

    // If test-head query var exists hook into wp_head
    if ( isset( $_GET['wow-wowanalytics-test-head'] ) )
        add_action( 'wp_head', 'wow_wowanalytics_test_head', 99999 ); // Some obscene priority, make sure we run last

    // If test-footer query var exists hook into wp_footer
    //if ( isset( $_GET['test-footer'] ) )
    //    add_action( 'wp_footer', 'wow_wowanalytics_test_footer', 99999 ); // Some obscene priority, make sure we run last
}

add_filter('plugin_action_links', 'wow_wowanalytics_plugin_action_links', 10, 2);

function wow_wowanalytics_plugin_action_links($links, $file) {
    static $this_plugin;

    // Absolute path to your specific plugin
    $my_plugin = 'wow-analytics/wow-analytics.php';

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin && is_plugin_active($my_plugin)) {
        // The "page" query string value must be equal to the slug
        // of the Settings admin page we defined earlier, which in
        // this case equals "myplugin-settings".
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=wow_wowanalytics">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

// Echo a string that we can search for later into the head of the document
// This should end up appearing directly before </head>
function wow_wowanalytics_test_head() {
    echo '<!--wp_head-->';
}

// Echo a string that we can search for later into the footer of the document
// This should end up appearing directly before </body>
function wow_wowanalytics_test_footer() {
    echo '<!--wp_footer-->';
}

function wowanalytics_admin_notice__warning() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p>Please note that the WOW Analytics Tracker plugin is no longer supported.
            Please update to the <a href="/wp-admin/plugin-install.php?tab=search&s=gatorleads">GatorLeads</a> plugin.</p>
    </div>
    <?php
}

add_action( 'admin_notices', 'wowanalytics_admin_notice__warning' );