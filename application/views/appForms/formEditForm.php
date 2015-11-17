<form id="pageForm" name="pageForm" method="post" action="/admin/extras/forms/save/<? echo $form_id; ?>">
    <input type="hidden" name="form_id" id="form_id" value="<? echo $form_id; ?>">
	<div class="form-row">
    <div class="input-wrapper">
    <label for="form_name">Form Name</label>
    <input type="text" name="form_name" id="form_name" value="<? echo $form_name; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="form_subject">Subject</label>
    <input type="text" name="form_subject" id="form_subject" value="<? echo $form_subject; ?>" class="input-full">
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="form_to">To</label>
    <input type="text" name="form_to" id="form_to" value="<? echo $form_to; ?>" class="input-full">
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="form_from">From</label>
    <input type="text" name="form_from" id="form_from" value="<? echo $form_from; ?>" class="input-full">
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="form_cc">CC</label>
    <input type="text" name="form_cc" id="form_cc" value="<? echo $form_cc; ?>" class="input-full">
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="form_bcc">BCC</label>
    <input type="text" name="form_bcc" id="form_bcc" value="<? echo $form_bcc; ?>" class="input-full">
    </div>
    </div>
    
    <!-- --------------------- -->
</form>