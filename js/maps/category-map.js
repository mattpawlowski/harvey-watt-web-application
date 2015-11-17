// JavaScript Document

$(document).ready(function() {
        
    
    var entityMap = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': '&quot;',
        "'": '&#39;',
        "/": '&#x2F;'
    };
    var time = 500;
    
    
    // Add From First Category    
    $('ul.map_category_list li:first').children('ul').children('li').each(function() {
        var ele = $(this);
        var address = $(ele).attr('data-address');
        var icon = "/images/display/marker.png";
        var id = $(ele).attr('data-id');
        var name = escapeString($(ele).attr('data-text'));
        
            console.log('Create Marker:');   
            console.log('- Address: '+address);
            console.log('- Icon: '+icon);
            console.log('- ID: '+id);
            console.log('- Name: '+name);
            setTimeout('codeAddress(\''+address+'\',\''+icon+'\',\''+name+'\',\''+id+'\')',time);
            time = time+500;
    });
    
    $('ul.map_category_list li:first a').addClass('active');

  function escapeString(string) {
    return String(string).replace(/[&<>"'\/]/g, function (s) {
      return entityMap[s];
    });
  }
  
  $('a.category-trigger').click(function() {
        var time = 500;
        
        $('a.category-trigger').removeClass('active');
        $(this).addClass('active');
      
        // Remove Current Markers
        $.each(markerArray, function( index, value ) {
            markerArray[index][2].setMap(null); 
        });
        
        $(this).siblings('ul').children('li').each(function() {
            var ele = $(this);
            var address = $(ele).attr('data-address');
            var icon = "/images/display/marker.png";
            var id = $(ele).attr('data-id');
            var name = escapeString($(ele).attr('data-text'));
            
                console.log('Create Marker:');   
                console.log('- Address: '+address);
                console.log('- Icon: '+icon);
                console.log('- ID: '+id);
                console.log('- Name: '+name);
                setTimeout('codeAddress(\''+address+'\',\''+icon+'\',\''+name+'\',\''+id+'\')',time);
                time = time+500;
        });
        
        addMidtown();
  });
  
  function addMidtown() {
    var address = '3101 Smith St Houston TX 77006';
      var myLatLng = geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
              myLatLng = results[0].geometry.location;
              addMidtownMarker(myLatLng);
          }
      });
  }
  
  function addMidtownMarker(myLatLng) {
      console.log('Making Midtown Icon');		
        
        var id = '0999';
		
		var marker = new google.maps.Marker({
			map: map,
			draggable: false,
            animation: google.maps.Animation.Fade,
			position: myLatLng,
			visible: true
		});
		
		var image = new google.maps.MarkerImage(
			'/images/display/marker-midtown.png',
			new google.maps.Size(120,34)
		);
		
		marker.setIcon(image);
		
		google.maps.event.addListener(marker, 'click', function() {
            var address = '3101 Smith St Houston TX 77006';
            var formatAddress = address.replace(' ','+');
            var url = 'https://www.google.com/maps/dir//'+formatAddress;
            window.open(url,'_blank');
		});
		
		return marker;
	}
    
    addMidtown();
});