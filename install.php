<?php
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


function wowanalytics_install() {

    if ( version_compare( get_bloginfo( 'version' ), '4.0', '<' ) ) {
        deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
        exit('This plugin requires WordPress 4.0 or greater');
    }

    if( is_plugin_active('gatorleads/gatorleads.php') ){
        deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
        exit('GatorLeads plugin is installed');
    }

    $wowVersion =  constant( 'WOWANALYTICS_VERSION' );

    // get the previous version if available
    $wowOptions = get_option('wow_wowanalytics_options');

    // is this a clean install
    if(!$wowOptions)
    {
        // this is a clean install so we need to create the default options

        $wowOptions = array(
            'clientid_text' => '',
            'trackuser_bool' => true,
            'track_downloads_bool' => true,
            'track_download_extensions' => '',
            'version' => $wowVersion,
            'tracklogedinUser_bool' => false
        );

        add_option('wow_wowanalytics_options', $wowOptions);
    }
    else
    {
        // this is an upgrade
        if (isset($wowOptions['version']))
        {
            $prev_version = $wowOptions['version'];
            if($prev_version == null){
                $prev_version = '1.1.0';
            }
        }
        else
        {
            $prev_version = '1.1.0';
        }

        // this is a switch to plan for future versions
        switch($prev_version)
        {
            case '2.0.6':
            case '2.0.5':
            case '2.0.4':
            case '2.0.3':
            case '2.0.2':
            case '2.0.1':
            case '2.0.0':
                $wowOptions['tracklogedinUser'] = false;
                $wowOptions['version'] = $wowVersion;
                update_option( 'wow_wowanalytics_options', $wowOptions ) ;

                break;
            case '1.1.0':
                $wowOptions2 = array(
                    'clientid_text' => $wowOptions['clientid_text'],
                    'trackuser_bool' => $wowOptions['trackuser_bool'],
                    'track_downloads_bool' => $wowOptions['track_downloads_bool'],
                    'track_download_extensions' => $wowOptions['track_download_extensions'],
                    'version' => $wowVersion,
                    'tracklogedinUser_bool' => false
                );
                update_option( 'wow_wowanalytics_options', $wowOptions2 );

                break;
            default:
                $wowOptions['version'] = $wowVersion;
                update_option( 'wow_wowanalytics_options', $wowOptions ) ;
                break;
        }



    }
}


