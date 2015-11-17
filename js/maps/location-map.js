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
        
    $('.marker-add').each(function() {
        var ele = $(this);
        var address = $(ele).attr('data-address');
        var icon = "/images/display/marker.png";
        var id = $(ele).attr('data-id');
        var name = escapeString($(ele).attr('data-text'));
        
            console.log(ele);   
            setTimeout('codeAddress(\''+address+'\',\''+icon+'\',\''+name+'\',\''+id+'\')',time);
            time = time+500;
    });

  function escapeString(string) {
    return String(string).replace(/[&<>"'\/]/g, function (s) {
      return entityMap[s];
    });
  }
    
});