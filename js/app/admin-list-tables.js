// JavaScript Document
$(document).ready(function() {
   
    // Check and Uncheck all Functions
    $('a.check-all').click(function() {
       $('.del-box').prop('checked',true); 
        highlightRow();
    });
    $('a.uncheck-all').click(function() {
       $('.del-box').prop('checked',false); 
        highlightRow();
    });
    $('input[type=checkbox]').change(function() {
        highlightRow();
    });
    
    // Row Option Show/Hide
    $('div.options-hover').hover(
        function() { $(this).children('.row-options').css('visibility','visible'); },
        function() { $(this).children('.row-options').css('visibility','hidden'); }
    );
    
    // Listen for delete Buttons (quick delete)
    $('.delete-item').click(function() {
        var url = $(this).attr('data-post');
        var id = $(this).attr('data-id');
        deleteItem(url,id);
    });
    
    // Listen for delete Buttons (quick delete)
    $('.activate-item, .suspend-item').click(function() {
        var url = $(this).attr('data-post');
        var id = $(this).attr('data-id');
        refreshItem(url,id);
    });
    
    $('.delete-selected').click(function() {
        var sList = getChecked();
        console.log(sList);
        for(var i=0; i<sList.length; i++) {
            var ele = $('#box_'+sList[i]);
            var url = ele.attr('data-post');
            var id = sList[i];
            deleteItem(url,id); 
        }
    });
    
    function highlightRow() {
       $('input[type=checkbox]').each(function() {
           var tarEle = $(this).parent('td'); 
           var tarEle2 = tarEle.siblings('td');
           if(this.checked) {
               tarEle.css('background-color','#F3BAC0');
               tarEle2.css('background-color','#F3BAC0');
               tarEle.css('border-bottom','1px solid #F3BAC0');
               tarEle2.css('border-bottom','1px solid #F3BAC0');
               tarEle.css('color','#C00');
               tarEle2.css('color','#C00');
           } else {
               tarEle.css('background-color','#FFF'); 
               tarEle2.css('background-color','#FFF'); 
               tarEle.css('border-bottom','1px solid #e5e5e5');
               tarEle2.css('border-bottom','1px solid #e5e5e5');
               tarEle.css('color','#000');
               tarEle2.css('color','#000');   
           }
       });
    }
    
    function deleteItem(url,id) {
        $.ajax({
            url: url,
            type: "POST",
            data: ({"id":id}),
            async: false,
            success: function(data) {
                $(this).css('background-color','red');
                var pageRow = $('tr#'+id);	
                pageRow.fadeOut(500, function() { $(this).remove();});
                console.log(data);
            }
        });   
    }
    
    function refreshItem(url,id) {
        $.ajax({
            url: url,
            type: "POST",
            data: ({"id":id}),
            async: false,
            success: function(data) {
                window.location.href='/admin/users/all';
            }
        });   
    }
    
    function getChecked() {
	var sList = new Array();
	$('input[type=checkbox]').each(function () {
		if(this.checked) {
		var sThisVal = $(this).attr('data-id');
		sList.push(sThisVal);
		}
	});
	return sList;
}
    
});