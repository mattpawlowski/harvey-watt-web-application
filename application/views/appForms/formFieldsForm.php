<style type="text/css">
    ul#field_options_list { margin: 0; padding: 0; border: 0; border-radius: 0; }
    ul.tagit li.tagit-choice { color: #333; font-weight: 400; font-size: 12px; }
</style>

<form id="addForm" name="addForm" method="post" action="/admin/extras/forms/savefields/<? echo $form_id; ?>">
    <input type="hidden" name="form_id" id="form_id" value="<? echo $form_id; ?>">
    
	<div class="form-row type-row">
    <div class="input-wrapper">
    <label for="field_type">Field Type</label>
    <select name="field_type" id="field_type">
        <option value="text" selected="">Text</option>
        <option value="tel">Telephone</option>
        <option value="email">Email</option>
        <option value="date">Date</option>
        <option value="password">Password</option>
        <option value="textarea">Textarea</option>
        <option value="dropdown">Dropdown</option>
        <option value="radio">Radio</option>
        <option value="checkbox">Checkbox</option>
        <option value="captcha">CAPTCHA Security Field</option>
    </select>
    </div>
    </div>
    
    <!-- --------------------- -->
    
	<div class="form-row label-row">
    <div class="input-wrapper">
    <label for="field_label">Field Label</label>
    <input type="text" name="field_label" id="field_label" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->
    
	<div class="form-row options-row" style="display: none;">
    <div class="input-wrapper">
    <label for="field_options">Field Options <span class="small">( Press Enter to Seperate )</span></label>
    <ul id="field_options_list">
    </ul>
    </div>
    </div>
    
    <!-- --------------------- -->
    
	<div class="form-row req-row">
    <div class="input-wrapper">
    <label for="field_type">Field Required</label>
    <select name="field_required" id="field_required">
        <option value="0" selected="">No</option>
        <option value="1">Yes</option>
    </select>
    </div>
    </div>
    
    <!-- --------------------- -->
</form>

<script type="text/javascript">
    $(document).ready(function() {
        
        // Turn off what's required for diff form types
        $('#field_type').change(function() {
           var type = $('#field_type').val();
           console.log(type);
           if(type == 'dropdown' || type == 'radio' || type == 'checkbox') {
               $('.options-row').slideDown(250); 
               $('.label-row').slideDown(250);
               $('.req-row').slideDown(250);   
           } else if(type == 'captcha') {
               $('.options-row').slideUp(250);
               $('.label-row').slideUp(250);
               $('.req-row').slideUp(250);
           } else {
                $('.options-row').slideUp(250); 
               $('.label-row').slideDown(250);
               $('.req-row').slideDown(250);     
           }
        });
        
        // Init the options tagger
        $("#field_options_list").tagit({
               fieldName: "field_options",
               allowSpaces: true
        });
        
        // Add It
        $('.trigger-add-field').click(function() {  
            console.log('ADDING NEW FIELD');
                 
           var field_type = $('#field_type').val();
           var field_label = $('#field_label').val();
           var field_required = $('#field_required').val(); 
           var field_options = new Array;
           $('#field_options_list li').each(function() {
               if($(this).children('input').val() != '') { field_options.push($(this).children('input').val()); }
           });
               field_options.filter(Boolean);
               field_options_list = field_options.join(",");
               field_options = JSON.stringify(field_options);
           
           console.log('- Type: '+field_type);
           console.log('- Label: '+field_label);
           console.log('- Options: '+field_options);
           console.log('- Required: '+field_required);
           
           var append_html = '<li data-type="'+field_type+'" data-label="'+field_label+'" data-options="'+field_options+'" data-required="'+field_required+'">';
               append_html += '<div>';
               append_html += '<span class="field-actions"><a href="javascript: void(0);" class="remove-item"><img src="/images/app/icons/remove-circle.png"></a></span>';
               append_html += '<span class="field-required-'+field_required+'"></span>';
               append_html += '<span class="field-label">'+field_label+'</span>';
               append_html += '<br>';
               append_html += '<span class="field-type small">'+field_type+'</span>';
               if(field_options_list != '') { append_html += '<span class="field-options small">: '+field_options_list+'</span>'; }
               append_html += '<span class="clear"></span>';
               append_html += '</div>';
               append_html += '</li>';
           console.log(append_html);
           $('#form-items').append(append_html);
           init();
           resetAddForm();
        });
        
    });
    
    
        
    // Resets our add form
    function resetAddForm() {
        $('#field_type').val('text');
        $('#field_label').val('');
        $('li.tagit-choice').remove();
        $('#field_required').val('0');
        
        $('.options-row').slideUp(250); 
        $('.label-row').slideDown(250);
        $('.req-row').slideDown(250);  
    }
    
    
        
    function init() {
        $('a.remove-item').on("click",function() {
            console.log('remove item...');
            ele = $(this).parent('span').parent('div').parent('li')
            ele.slideUp(150);
            setTimeout(function() { ele.remove(); },200);
        });
                
        $('span.field-required-0').click(function() {
           $(this).removeClass('field-required-0');
           $(this).addClass('field-required-1');
           $(this).parent('div').parent('li').attr('data-required','1'); 
           init();
        });
        
        $('span.field-required-1').click(function() {
           $(this).removeClass('field-required-1');
           $(this).addClass('field-required-0');
           $(this).parent('div').parent('li').attr('data-required','0'); 
           init();
        });
    }
    
    
    function convertData() {
     
        var formId = $('#form_id').val();
        console.log('Saving Menu with ID: '+formId);
        
        // Get Rid of all that old obsolete dated crap that's there now
        console.log('Deleting Old Fields');
        $.ajax({
           url: '/admin/extras/forms/deletefields/'+formId,
           type: 'POST',
           async: false,
           sucess: function(response) {
                console.log('- '+response.responseText);   
           },
           error: function(response) {
                console.log('- ERROR: '+response.responseText);   
           }
        });
        
        // Build an array of our items, first
        fieldArr = new Array();
        $('ul#form-items li').each(function() {
                myItem = new Array();
                myItem['type'] = $(this).attr('data-type');
                myItem['label'] = $(this).attr('data-label');
                myItem['options'] = $(this).attr('data-options');
                myItem['required'] = $(this).attr('data-required');
                myItem['order'] = $(this).index();
                fieldArr.push(myItem);
        });
        console.log(fieldArr);
        
        
        // Build our awesome and Insert them
        var url = "/admin/extras/forms/savefields/"+formId;
        fieldArr.forEach(function(index) {
            // save each of our dealies
            var datastring = '';
                datastring += "type="+index['type']+"";
                datastring += "&label="+index['label']+"";
                datastring += "&options="+index['options']+"";
                datastring += "&required="+index['required']+"";
                datastring += "&order="+index['order']+"";    
                
                $.post(url,datastring,function(response){console.log(response.responseText);});
        });
        
        setTimeout(function() { window.location.href = "/admin/extras/forms/refreshfields/"+formId; }, (fieldArr.length * 500));
        
        return false;
        
    }
    
    
    
$(document).ready(function() {
    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };                
    $(".sortable").sortable({
        helper: fixHelper
    }).disableSelection();
    
    init();
});
</script>