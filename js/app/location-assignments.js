// JavaScript Document

function convertData() {
    var locationId = $('#location_id').val();
        
    // Get Rid of all that old obsolete dated crap that's there now
    console.log('delete current');
    $.ajax({
       url: '/admin/locations/all/deleteassignments/'+locationId,
       async: false,
       sucess: function() {
            console.log('old items removed');   
       }
    });
    
    // Build our new Assignment List
    var assignments = new Array();
    $('#sortable1 li').each(function() {
        assignments.push($(this).attr('data-id'));
    });
    
    // Build our awesome and Insert them
        var url = "/admin/locations/all/saveassignments/"+locationId;
        assignments.forEach(function(index) {
            // save each of our dealies
            var datastring = '';
                datastring += "id="+index;        
                console.log(url+datastring);
                
                $.post(url,datastring,function(response){console.log(response.responseText);});
        });
        
        setTimeout(function() { window.location.href = "/admin/locations/all/refreshassignemnts/"+locationId; }, (assignments.length * 500));
}