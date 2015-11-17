<form id="pageForm" name="pageForm" method="post" action="/admin/locations/maps/save/<? echo $map_id; ?>">
    <input type="hidden" name="map_id" id="map_id" value="<? echo $map_id; ?>">
	<div class="form-row">
    <div class="input-wrapper">
    <label for="map_name">Map Name</label>
    <input type="text" name="map_name" id="map_name" value="<? echo $map_name; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="map_type">Map Type</label>
    <select name="map_type" id="map_type">
        <option value="loc" <? if($map_type == 'loc') { echo "SELECTED"; } ?>>Location Map</option>
        <option value="cat" <? if($map_type == 'cat') { echo "SELECTED"; } ?>>Category Map</option>
    </select>
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <input type="hidden" name="map_slug" id="map_slug" value="<? echo $map_slug; ?>" class="input-full">
    <label for="map_slug">Map Slug</label>
    <input type="text" name="map_slug_hidden" id="map_slug_hidden" value="<? echo $map_slug; ?>" class="input-full disabled" disabled="disabled">
    </div>
    </div>
    
    <!-- --------------------- -->
</form>

<script type="text/javascript">
$(document).ready(function() {
    var typingTimer;
    var doneTypingInterval = 800;
    
    $('#map_name').keyup(function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(buildURL, doneTypingInterval);        
    });
    
    $('#map_name').keydown(function() {
       clearTimeout(typingTimer);
    });
    
    $('#map_name').blur(function() {
       buildURL(); 
    });
});


function buildURL() {
    var request = $('#map_name').val();
    var rstr = request.replace(/[^a-zA-Z0-9 ]/g, "")
    var rstr = $.trim(rstr);
    var rstr = rstr.replace(/\s+/g, '-').toLowerCase();
    $('#map_slug_hidden').val(rstr);
}

function convertData() {
    $('#map_slug').val($('#map_slug_hidden').val());	   
}
</script>