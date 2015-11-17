<form id="pageForm" name="pageForm" method="post" action="/admin/users/all/save/<? echo $user_id; ?>">
    <input type="hidden" name="user_id" id="user_id" value="<? echo $user_id; ?>">
	<div class="form-row">
    <div class="input-wrapper">
    <label for="user_fname">First Name</label>
    <input type="text" name="user_fname" id="user_fname" value="<? echo $user_fname; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="user_lname">Last Name</label>
    <input type="text" name="user_lname" id="user_lname" value="<? echo $user_lname; ?>" class="input-full">
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="user_email">Email Address</label>
    <input type="text" name="user_email" id="user_email" value="<? echo $user_email; ?>" class="input-full">
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <input type="hidden" name="user_password_hash" id="user_password_hash" value="<? echo $user_password; ?>">
    <label for="user_password">Reset Password</label>
    <input type="text" name="user_password" id="user_password" value="" class="input-full">
    </div>
    </div>
    
    <!-- --------------------- -->
</form>