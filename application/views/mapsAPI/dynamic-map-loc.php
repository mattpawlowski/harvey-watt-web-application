<script type="text/javascript">
// Setup
// ************************************************************************* //
// console.log('');
// console.log('----------------------------');
// console.log('Setting defaults...');
	var map;										// map object
    var geocoder                                   // geocoder object
	var mapOptions;									// map options
	var styles;										// map styles
    var markerArray = [];


// Map Options														  *complete
// ************************************************************************* //
	function setOptions() {
		// console.log('');
		// console.log('----------------------------');
		// console.log('Setting map options...');
		// create our map options array
		mapOptions = {
			zoom: <? echo $map_zoom; ?>,
			panControl: false,
			scrollwheel: false,
			zoomControl: true,
			zoomControlOptions: {
				style: google.maps.ZoomControlStyle.SMALL,
				position: google.maps.ControlPosition.LEFT_BOTTOM
			},
			scaleControl: true,
			streetViewControl: false,
			mapTypeControl: false,
			center: new google.maps.LatLng<? echo $map_center; ?>,
			mapTypeControlOptions: {
				mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
			}
		};
		
        <? if($map_styles == '') { ?>
		styles = [];
        <? } else {
        
        echo 'styles = '.$map_styles.';';
            
        } ?>
	}

// Map Object														  *complete
// ************************************************************************* //
	function initMap() {
		// console.log('');
		// console.log('----------------------------');
		// console.log('Initializing Map...');
		var styledMap = new google.maps.StyledMapType(styles, {name: "Creekside"});
		map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
		map.mapTypes.set('map_style', styledMap);
		map.setMapTypeId('map_style');
	}

// Geocode the address  											  *complete
// ************************************************************************* //
    function codeAddress(address,icon,name,id) {
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
              var latLng = results[0].geometry.location;
             var marker = addMarker(latLng,icon,id);
			 var infowindow = addInfoBubble('<div class="scrollFix"><span class="infobubble-text"><b style="font-size: 14px;">'+name+'</b><br><span style="font-size: 11px;">'+address+'<br><i>Click for Directions</i></span></span></div>');
             var x = [id, name, marker, infowindow, latLng];
			 markerArray.push(x);
          } else {
            console.log('Geocode was not successful for the following reason: ' + status);
          }
        });
    }
	

// Draws a marker on the map										  *complete
// ************************************************************************* //
	function addMarker(myLatLng,icon,id) {
		
		
		var marker = new google.maps.Marker({
			map: map,
			draggable: false,
            animation: google.maps.Animation.DROP,
			position: myLatLng,
			visible: true
		});
		
		var image = new google.maps.MarkerImage(
			icon,
			new google.maps.Size(20,26)
		);
		
		marker.setIcon(image);
		
		google.maps.event.addListener(marker, 'click', function() {
            var address = $('#location-'+id).attr('data-address');
            var formatAddress = address.replace(' ','+');
            var url = 'https://www.google.com/maps/dir//'+formatAddress;
            window.open(url,'_blank');
		});
		google.maps.event.addListener(marker, 'mouseover', function() {
            showName(id);
		});
		google.maps.event.addListener(marker, 'mouseout', function() {
            hideName(id);
		});
		
		return marker;
	}
	

// showName
// ************************************************************************* //
	function showName(id) {
		thisCom = getCommunity(id);
			thisCom.infowindow.open(map,thisCom.marker);
			stop();
	}
	

// Adds an InfoBubble to marker										  *complete
// ************************************************************************* //
	function hideName(id) {
		thisCom = getCommunity(id);
			thisCom.infowindow.close();
	}

// Gets all our city information									  *complete
// ************************************************************************* //
	function getCommunity(id) {
		var data = new Object;
		for( var i = 0, len = markerArray.length; i < len; i++) {
			if(markerArray[i][0] == id) {
				data.id 		= markerArray[i][0];
				data.name 		= markerArray[i][1];
				data.marker 	= markerArray[i][2];
				data.infowindow = markerArray[i][3];
				data.latlng 	= markerArray[i][4];
				break;
			} else {  }
		}
		
		return data;
	}
	

// Adds an InfoBubble to marker										  *complete
// ************************************************************************* //
	function addInfoBubble(content) {
		// console.log('Adding infoBubble...');
		// console.log('     [content] => ' + content);
		// console.log('');
		
		var infoBubble = new InfoBubble({
			map: map,
			content: content,
			hideCloseButton: true,
			backgroundColor: '#ffffff',
			borderRadius: 0,
			borderWidth: 0,
			minWidth: 100,
			maxWidth: 500,
            minHeight: 35,
            maxHeight: 80
		});
		
		
		return infoBubble;
	}
    
// Runs It all														  *complete
// ************************************************************************* //
	function initialize() {		 
		// console.log('');
		// console.log('----------------------------');
		// console.log('Initializing System...');
        geocoder = new google.maps.Geocoder();      // geocoding
		setOptions();								// load our map options
		initMap();									// initialize the map
	}
</script>