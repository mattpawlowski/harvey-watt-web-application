<form id="pageForm" name="pageForm" method="post" action="/admin/locations/all/save/<? echo $location_id; ?>">
    <input type="hidden" name="location_id" id="location_id" value="<? echo $location_id; ?>">
	<div class="form-row">
    <div class="input-wrapper">
    <label for="location_name">Location Name</label>
    <input type="text" name="location_name" id="location_name" value="<? echo $location_name; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="location_summary">Location Summary</label>
    <textarea name="location_summary" id="location_summary"><? echo $location_summary; ?></textarea>
    </div>
    </div>
    
    <!-- --------------------- -->
	<div class="form-row">
    <div class="input-wrapper">
    <label for="location_street">Street Address</label>
    <input type="text" name="location_street" id="location_street" value="<? echo $location_street; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->
	<div class="form-row">
    <div class="input-wrapper">
    <label for="location_city">City</label>
    <input type="text" name="location_city" id="location_city" value="<? echo $location_city; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->
	<div class="form-row">
    <div class="input-wrapper">
    <label for="location_state">State</label>
    <input type="text" name="location_state" id="location_state" value="<? echo $location_state; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->
	<div class="form-row">
    <div class="input-wrapper">
    <label for="location_zip">Zip</label>
    <input type="text" name="location_zip" id="location_zip" value="<? echo $location_zip; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->
</form>