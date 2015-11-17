// JavaScript Document
$(document).ready(function() {
    
    // Navigation (MAIN) dropouts
    $('li.parent-inactive').hover(
        function() { showSubcategory($(this)); },
        function() { hideSubcategory($(this)); }
    );    
    function showSubcategory(ele) {
        ele.addClass('hover');
        ele.has('ul').addClass('parent-hover');
        ele.children('ul').fadeIn(250);    
    }
    
    function hideSubcategory(ele) {
        ele.removeClass('hover');
        ele.has('ul').removeClass('parent-hover');
        ele.children('ul').fadeOut(250);
    }
    
    // This is the Function that hides our alerts
    $('.fdcms-screen').click(function() {
        fdcmsClose();
    });
    
    // Set up listeners for our help library
    // trigger on class 'help' reads for
    // data-subject = 'xxxxx'
    $(document.body).on('click','.help',function() {
        console.log('loading help interface');
        var title = 'FDCMS Reference Guide';
        var subject = $(this).attr('data-subject');
        console.log('looking for something: '+subject);
        
        $.ajax({
           url: '/fdcms/help',
           type: "POST",
           data: ({'subject':subject}),
           async: false,
           success: function(html) {
               fdcmsAlert(title,html);
               $('pre').each(function(i, e) { hljs.highlightBlock(e)});
           }
        });
    });
    
    // Check to see if there are extra stuffs we need to do
    $('#save-form').bind("click",function() {
        // Turn off the listener
        
        $('#save-form').unbind("click");
        
        $(this).attr('disabled','disabled').css('color','#ccc').css('cursor','progress');
        $(this).siblings('.loading').fadeIn(150);
        if (typeof convertData == 'function') { 
          convertData();
        }		
        $("#pageForm").submit();	
    });

});

// Shows our Alert
// Title = String for title
// Content = HTML for content
// Style = typeclass 'default','warning','success' are supported natively
function fdcmsAlert(title,content,style) {
    style = typeof style !== 'undefined' ? style : 'default';
    $('.fdcms-alert-title').html(title);
    $('.fdcms-alert-content').html(content);
    
    // Measure our width and height
    var x = $('.fdcms-alert-container').outerWidth();
    var y = $('.fdcms-alert-container').outerHeight();
    var mx = x/2;
    var my = x/2;
    
    $('.fdcms-alert-container').css('position','fixed');
    $('.fdcms-alert-container').css('margin-left','-'+mx+'px');
    $('.fdcms-alert-container').css('margin-top','-'+my+'px');
    $('.fdcms-alert-container').addClass(style);
    
    $('.fdcms-screen').fadeIn(250);
    setTimeout(function() { $('.fdcms-alert-container').fadeIn(250); }, 300);    
}

// Hide our Alert
function fdcmsClose() {
    $('.fdcms-alert-container').fadeOut(250);
    $('.fdcms-alert-container').removeClass('default').removeClass('warning').removeClass('success');
    setTimeout(function() { $('.fdcms-screen').fadeOut(250); }, 300); 
}