// JavaScript Document

$(document).ready(function() {
    
    $('.trigger-add-link').click(function() {
       var linkURL = $('#menu_add_url').val();
       var linkText = $('#menu_add_text').val();
       
       $('#menu_add_url').val('');
       $('#menu_add_text').val('');
       
       addLink(linkURL,linkText,0);
       
       removeWarning();
    });
    
    
    $('.trigger-add-pages').click(function() {
        // Add Checked pages
	    $('input[type=checkbox]').each(function () { 
            if(this.checked) {
                var linkURL = $(this).attr('data-url');
                var linkText = $(this).attr('data-text');
                var linkPageId = $(this).attr('data-pageid');
                addLink(linkURL,linkText,linkPageId);
                removeWarning();
                $(this).prop('checked',false); 
            }
        });
    });
    
    function removeWarning() {
        $('.warning-text').slideUp(200);
        $('.warning-text').remove();
    }
    
    function addLink(linkURL,linkText,linkPageId) {
       var maxId = getMaxId();
       var html = '<li id="'+maxId+'" data-url="'+linkURL+'" data-text="'+linkText+'" data-pageid="'+linkPageId+'"><div><span class="discolse"></span><span>'+linkText+'</span><span class="menu-remove"></span></div></li>';
       $('ol.sortable').append(html); 
       init();  
    }
    
    function getMaxId() {
        var maxId = 0;
        $('.sortable-menu li').each(function() {
            var check = parseInt($(this).attr('id'));
            if(check>maxId) { maxId = check; }
        });
        
        var maxId = maxId + 1;
        
        return maxId;
    }
    
    init();
    
});


    function init() {
        // Remove items
        $('span.menu-remove').click(function() {
            var target = $(this).parent('div').parent('li');
           target.slideUp(250)
           setTimeout(function() { target.remove(); console.log('removed'); }, 300);
        });
    }
   
    function convertData() {
        var menuId = $('#menu_id').val();
        console.log('Saving Menu with ID: '+menuId);
        
        // Get Rid of all that old obsolete dated crap that's there now
        $.ajax({
           url: '/admin/extras/menus/deleteitems/'+menuId,
           async: false,
           sucess: function() {
                console.log('old items removed');   
           }
        });
     
        // Build an array of our items, first
        menuArr = new Array();
        $('ol.sortable li').each(function() {
                myItem = new Array();
                myItem['id'] = $(this).attr('id');
                myItem['url'] = $(this).attr('data-url');
                myItem['text'] = $(this).attr('data-text');
                myItem['page'] = $(this).attr('data-pageid');
                if($(this).parents('li').length) {
                    myItem['parent'] = $(this).parents('li').attr('id');
                } else {
                    myItem['parent'] = '0';
                }
                myItem['order'] = $(this).index();
                
                menuArr.push(myItem);
        });
        console.log(menuArr);
        
        
        // Build our awesome and Insert them
        var url = "/admin/extras/menus/saveitems/"+menuId;
        menuArr.forEach(function(index) {
            // save each of our dealies
            var datastring = '';
                datastring += "id="+index['id']+"";
                datastring += "&url="+encodeURIComponent(index['url'])+"";
                datastring += "&text="+index['text']+"";
                datastring += "&page="+index['page']+"";
                datastring += "&parent="+index['parent']+"";
                datastring += "&order="+index['order']+"";            
                console.log(datastring);
                
                $.post(url,datastring,function(response){console.log(response.responseText);});
        });
        
        setTimeout(function() { window.location.href = "/admin/extras/menus/refreshitems/"+menuId; }, (menuArr.length * 500));
        
        return false;
        
    }