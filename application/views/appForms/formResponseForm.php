<form id="pageForm" name="pageForm" method="post" action="/admin/extras/forms/saveresponse/<? echo $form_id; ?>">
    <input type="hidden" name="form_id" id="form_id" value="<? echo $form_id; ?>">
	<div class="form-row">
    <div class="input-wrapper">
    <label for="form_response_action">Response Action</label>
    <select name="form_response_action" id="form_response_action">
        <option value="1" <? if($form_response_action == '1') { echo "SELECTED"; } ?>>Return User to Form page and show Response Message</option>
        <option value="2" <? if($form_response_action == '2') { echo "SELECTED"; } ?>>Forward User to URL</option>
    </select>
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row" id="message-row" style="display: none;">
    <div class="mce-wrapper">
    <label for="form_response_message">Message</label>
    <textarea name="form_response_message" id="form_response_message" class="editor"><? echo $form_response_message; ?></textarea>
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row" id="forward-row" style="display: none;">
    <div class="input-wrapper">
    <label for="form_response_forward">Forward to URL:</label>
    <input type="text" name="form_response_forward" id="form_response_forward" value="<? echo $form_response_forward; ?>" class="input-full">
    </div>
    </div>
    
    <!-- --------------------- -->
</form>

<script type="text/javascript">
    $(document).ready(function() {
       actionSwitch(); 
       setupWYSIWYG();
       
       $('#form_response_action').change(function() {
          actionSwitch(); 
       });
    });
    
    function actionSwitch() {
        var action = $('#form_response_action').val();
        
        if(action == '1') {
            $('#message-row').slideDown(250);
            $('#forward-row').slideUp(250);
        } else if(action == '2') {
            $('#message-row').slideUp(250);
            $('#forward-row').slideDown(250);
        } else {
            $('#form_response_action').val('1');
            actionSwitch();
        }
    }    
    
    
    function setupWYSIWYG() {
        tinymce.init({ selector: ".editor", menubar: false, plugins: [ "advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste" ], toolbar: "bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fullscreen code", autosave_ask_before_unload: false, max_height: 350, min_height: 160, height : 180, resize : false, relative_urls: false });
    }
</script>

