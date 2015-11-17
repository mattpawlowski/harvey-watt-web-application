<? if($map_type == 'cat') { ?>
<!-- Category Add Options -->
<div class="content-container" style="width: 48%; float: left; padding: 0px;">
    <h3>Add Categories</h3>
    <div style="max-height: 525px; overflow: auto;" id="category-assignments">
    <? echo $categoryOptions; ?>
    </div>
</div>
<? } ?>

<? if($map_type == 'loc') { ?>
<!-- Location Add Options -->
<div class="content-container" style="width: 48%; float: left; padding: 0px;">
    <h3>Add Locations</h3>
    <div style="max-height: 525px; overflow: auto;" id="location-assignments">
    <? echo $locationOptions; ?>
    </div>
</div>
<? } ?>

<div class="content-container" style="width: 47%; float: right; padding: 0px;">
    <h3>Area Preview</h3>
    <div class="padding">
    <p class="small">Please pan and zoom the map below to your desired settings and click 'Save Changes' on the right. You can also choose which categories or markers appear on your map using the assignment switches below.</p>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<? echo $api_key; ?>&sensor=false"></script>
    <script type="text/javascript" src="/js/maps/infobubble.js"></script>
    <? echo $mapJS; ?>
    <div id="map_canvas" style="width: 100%; height: 400px; z-index: 99;"></div>
    <center><p class="small"><strong>Left Double-Click</strong>: Zoom In | <strong>Right Double-Click</strong>: Zoom Out</p>
    <? if($notes != '') { echo '<i>'.$notes.'</i>'; } ?>
    </center>
    <script type="text/javascript">initialize();</script>
    </div>
</div>

<form action="/admin/locations/maps/savesettings/<? echo $map_id; ?>" name="pageForm" id="pageForm" method="POST">
    <input type="hidden" name="map_zoom" id="map_zoom" value="<? echo $map_zoom; ?>">
    <input type="hidden" name="map_center" id="map_center" value="<? echo $map_center; ?>">
    <input type="hidden" name="map_categories" id="map_categories" value="<? echo $map_categories; ?>">
    <input type="hidden" name="map_locations" id="map_locations" value="<? echo $map_locations; ?>">
</form>


<script type="text/javascript">
    $(document).ready(function() {
        
        // Pull In Saved Stuff
        var time = 500;
        var categoryArray = $.makeArray('<? echo $map_categories; ?>');
        var locationArray = $.makeArray('<? echo $map_locations; ?>');
        console.log(locationArray);
        
       $('.marker-add-box').each(function() {
            var ele = $(this);
            var address = $(ele).attr('data-address');
            var icon = "/images/display/marker.png";
            var id = $(ele).attr('data-id');
            var name = $(ele).attr('data-text');
            
            if(this.checked) {         
                console.log(ele);   
                setTimeout('codeAddress(\''+address+'\',\''+icon+'\',\''+name+'\',\''+id+'\')',time);
                time = time+500;
            }
        });
       
       // Add a dev marker when checked
       $('.marker-add-box').change(function() {
             if(this.checked) {
                 // set a marker
                 codeAddress($(this).attr('data-address'),'/images/display/marker.png',$(this).attr('data-text'),$(this).attr('data-id')); 
             } else {
                // unmark that bitch 
                var idfind = $(this).attr('data-id');
                console.log('Looking for Marker Id: '+idfind);
                $.each(markerArray, function( index, value ) {
                     if(markerArray[index][0] == idfind) {
                         console.log(markerArray[index][0]);
                         markerArray[index][2].setMap(null);   
                     }
                });
             }
       });
       
       
        
    });
    
    function convertData() {        
        var zoom = map.getZoom();
        var center = map.getCenter();
        
        var categoryArray = [];
        $('div#category-assignments .marker-add-box').each(function() {
            if(this.checked) {
                categoryArray.push($(this).attr('data-id'));                
            }
        });
        
        var locationArray = [];
        $('div#location-assignments .marker-add-box').each(function() {
            if(this.checked) {
                locationArray.push($(this).attr('data-id'));                
            }
        });
        
        console.log('Zoom: ' + zoom);
        console.log('Center: ' + center);
        console.log('Categories: ' + categoryArray);
        console.log('Locations: ' + locationArray);
        
        $('#map_zoom').val(zoom);
        $('#map_center').val(center);
        $('#map_categories').val(categoryArray.join(","));
        $('#map_locations').val(locationArray.join(","));
        
        return true;
    }
</script>