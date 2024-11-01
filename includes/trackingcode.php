<?php

function wow_wowanalytics_should_output_trackingcode(){
 
    $options = get_option('wow_wowanalytics_options');
    $clientid_text = trim($options['clientid_text']);
    $has_clientid = !empty($clientid_text);
    
    if (!current_user_can('manage_options') && $has_clientid){
        wow_wowanalytics_output_trackingcode();
    }
    else{
        if(!current_user_can('manage_options')){
            wow_wowanalytics_output_trackingcode_noclient();
        }
        else{
            wow_wowanalytics_output_trackingcode_admin();
        }
    }
}


function wow_wowanalytics_output_trackingcode_admin(){
    $wowVersion =  constant( 'WOWANALYTICS_VERSION' );
    ?>
    <!-- WOW Async for Wordpress Tracking Code Admin User -->
    <!-- WOW Plugin Version <?php echo $wowVersion; ?> -->
<?php
}
function wow_wowanalytics_output_trackingcode_noclient(){

    $wowVersion =  constant( 'WOWANALYTICS_VERSION' );
    ?>
    <!-- WOW Async for Wordpress Tracking Code No Client Set -->
    <!-- WOW Plugin Version <?php echo $wowVersion; ?> -->
    <?php
}

function wow_wowanalytics_output_trackingcode(){
    // get the options
    $options = get_option('wow_wowanalytics_options');
    $clientid_text = trim($options['clientid_text']);
    $trackuser_bool = $options['trackuser_bool'];
    $tracklogedinUser_bool = $options['tracklogedinUser_bool'];
	$trackdownloads_bool = $options['track_downloads_bool'];
    $wowVersion =  constant( 'WOWANALYTICS_VERSION' );

?>
    <!-- WOW Async for Wordpress Tracking Code Start -->
    <!-- WOW Plugin Version <?php echo $wowVersion; ?> -->
    <!-- tracked loged in user <?php echo $tracklogedinUser_bool; ?> -->
    <script data-cfasync='false' type='text/javascript'>
        var _wow = _wow || [];
        (function () {
            try{
                _wow.push(['setClientId', '<?php echo $clientid_text; ?>']);
                <?php
	            if(!$trackuser_bool){?>
                _wow.push(['disableUserTracking']);
                <?php
                }
                if($trackdownloads_bool){?>
                _wow.push(['enableDownloadTracking']);
                <?php }

                if($tracklogedinUser_bool){
                    global $current_user;
                    get_currentuserinfo();
                    ?>
                   var _wowEmail = '<?php echo $current_user->user_email; ?>'
                if(_wowEmail === ''){
                    _wow.push(['trackPageView']);
                }else{
                    var _wowUrl = document.location.href;
                    var targetPattern = new RegExp('#.*');
                    _wowUrl = _wowUrl.replace(targetPattern, '');


                    var _wowTitle = document.title;

                    if(_wowUrl.indexOf('?') > 0)
                    {
                        _wowUrl += '&_em=' + _wowEmail;
                    } else {
                        _wowUrl += '?_em=' + _wowEmail;
                    }

                    _wow.push(['trackPageView', _wowTitle, _wowUrl]);
                }
                <?php }
                else { ?>
                _wow.push(['trackPageView']);

                <?php } ?>
                var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
                g.type = 'text/javascript'; g.defer = true; g.async = true;
                g.src = '//t.wowanalytics.co.uk/Scripts/tracker.js';
                s.parentNode.insertBefore(g, s);
            }catch(err){}})();
    </script>
    <!-- WOW Async Tracking Code End -->

<?php
}
?>