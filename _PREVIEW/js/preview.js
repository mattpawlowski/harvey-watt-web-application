// JavaScript Document

var nav_height = 0;
var count = 1;
var max_count = 1;
var active = 1;
   
$(window).load(function() {
    
       
   // Get the navigation height
   nav_height = $('.navigation').outerHeight();
   $('ul#images').css('position','relative').css('top',nav_height+'px');
   
   // Add our order to each li element
   $('ul#images li').each(function() {
      if(count == 1) { $(this).addClass('active').css('display','block'); } else { $(this).css('display','none'); }
      $(this).attr('id',count); 
      count++;
   });   
   max_count = count - 1;
   console.log('Max Count = ' + max_count);
   
   setPageName(active); 
   
   
   // Get Rid of Loading Screen
   $('div.loader').fadeOut(500);
   
   
   // When we click next button
   $('a.next').click(function() {
       var next_target = active + 1;
       if(next_target > max_count) { next_target = 1; }
       console.log('next target = '+next_target);
       
       $('#'+active).removeClass('active').fadeOut(500);
       $('#'+next_target).addClass('active').fadeIn(500);
       setPageName(next_target);
       	   
       active = next_target;
       
       $(window).scrollTop(0);
   });
   
   
   // When we click previous button
   $('a.prev').click(function() {
       var next_target = active - 1;
       if(next_target < 1) { next_target = max_count; }
       console.log('next target = '+next_target);
       
       $('#'+active).removeClass('active').fadeOut(500);
       $('#'+next_target).addClass('active').fadeIn(500);
       setPageName(next_target);
       
       active = next_target;
       
       $(window).scrollTop(0);
   });  
   
    
});

function setPageName(target) {
    $('span.page-name').fadeOut(250);
    setTimeout(function() {
        var attr = $('#'+target).attr('data-name');
        if (typeof attr !== typeof undefined && attr !== false) {
            $('span.page-name').html(attr);
        } else {
            $('span.page-name').html('<img src="/images/logo.png">');    
        }
    }, 300);
    setTimeout(function() {
        $('span.page-name').fadeIn(250);
    },250);
    
}