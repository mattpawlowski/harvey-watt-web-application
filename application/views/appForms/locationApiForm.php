<script type="text/javascript">
    window.alert = function(message){
        clearTimeout(fdcmsTimeout);
        //fdcmsAlert('IMPORTANT',message,'warning');
        $('.status').removeClass('yellow');
        $('.status').addClass('bad');
        $('.status').html('API Key is Invalid - <a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_blank">More Information</a>');
    };
</script>
<form id="pageForm" name="pageForm" method="post" action="/admin/locations/api/save">
	<div class="form-row">
    <div class="input-wrapper">
    <label for="api_key">Google Maps v3 API Key</label>
    <input type="text" name="api_key" id="api_key" value="<? echo $api_key; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->
</form>

<? if($api_key == '') { ?>
    <p>To obtain or create your API Key:</p>
    <ol style="margin: 0; padding: 0 0 0 40px;">
        <li>Visit the APIs Console at <a href="https://code.google.com/apis/console" target="_blank">https://code.google.com/apis/console</a> and log in with your Google Account.</li>
        <li>Click the <strong>Services</strong> link from the left-hand menu.</li>
        <li>Activate the <strong>Google Maps API v3</strong> service.</li>
        <li>Click the <strong>API Access</strong> link from the left-hand menu. Your API key is available from the <strong>API Access</strong> page, in the <strong>Simple API Access</strong> section. Maps API applications use the <strong>Key for browser apps</strong>.</li>
    </ol>
    <img src="/images/app/core/api_console_key.jpg" style="padding: 2px; border: 1px solid #ccc; margin: 12px 0;">
<? } else { ?>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<? echo $api_key; ?>&sensor=TRUE"></script>
    <script type="text/javascript">
    if (typeof google === 'object' && typeof google.maps === 'object') {
        $('.content-container').before("<div class=\"yellow status\">Checking API Key...</div>");
        var fdcmsTimeout;
        fdcmsTimeout = setTimeout(function() { $('.status').removeClass('yellow'); $('.status').addClass('good'); $('.status').html('Connectivity Successful'); }, 8000);
    } else {
        $('.content-container').before("<div class=\"bad status\">Connection Issue - <a href=\"https://developers.google.com/maps/documentation/javascript/tutorial#api_key\" target=\"_blank\">More Information</a></div>");   
    }
    </script>

<? } ?>