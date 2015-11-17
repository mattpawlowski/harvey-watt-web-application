<form id="pageForm" name="pageForm" method="post" action="/admin/floorplans/all/save/<? echo $floorplan_id; ?>" enctype="multipart/form-data">
    <input type="hidden" name="floorplan_id" id="floorplan_id" value="<? echo $floorplan_id; ?>">
	<div class="form-row">
    <div class="input-wrapper">
    <label for="floorplan_name">Floorplan Name</label>
    <input type="text" name="floorplan_name" id="floorplan_name" value="<? echo $floorplan_name; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->
    
	<div class="form-row">
    <div class="input-wrapper">
    <label for="floorplan_price">Monthly Price</label>
    <input type="text" name="floorplan_price" id="floorplan_price" value="<? echo $floorplan_price; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="floorplan_br">Bedrooms</label>
    <select name="floorplan_br" id="floorplan_br">
        <option value="99" <? if($floorplan_br == '99') { echo 'SELECTED'; } ?>>Efficiency</option>
        <option value="0" <? if($floorplan_br == '0') { echo 'SELECTED'; } ?>>Studio</option>
        <option value="1" <? if($floorplan_br == '1') { echo 'SELECTED'; } ?>>One Bedroom</option>
        <option value="2" <? if($floorplan_br == '2') { echo 'SELECTED'; } ?>>Two Bedrooms</option>
        <option value="3" <? if($floorplan_br == '3') { echo 'SELECTED'; } ?>>Three Bedrooms</option>
        <option value="4" <? if($floorplan_br == '4') { echo 'SELECTED'; } ?>>Four Bedrooms</option>
        <option value="5" <? if($floorplan_br == '5') { echo 'SELECTED'; } ?>>Five Bedrooms</option>
    </select>
    </div>
    </div>
    
    <!-- --------------------- -->
	<div class="form-row">
    <div class="input-wrapper">
    <label for="floorplan_ba">Bathrooms</label>
    <select name="floorplan_ba" id="floorplan_ba">
        <option value="99" <? if($floorplan_ba == '99') { echo 'SELECTED'; } ?>>Efficiency</option>
        <option value="0" <? if($floorplan_ba == '0') { echo 'SELECTED'; } ?>>Studio</option>
        <option value="1" <? if($floorplan_ba == '1') { echo 'SELECTED'; } ?>>One Bathroom</option>
        <option value="2" <? if($floorplan_ba == '2') { echo 'SELECTED'; } ?>>Two Bathrooms</option>
        <option value="3" <? if($floorplan_ba == '3') { echo 'SELECTED'; } ?>>Three Bathrooms</option>
        <option value="4" <? if($floorplan_ba == '4') { echo 'SELECTED'; } ?>>Four Bathrooms</option>
        <option value="5" <? if($floorplan_ba == '5') { echo 'SELECTED'; } ?>>Five Bathrooms</option>
    </select>
    </div>
    </div>
    
    <!-- --------------------- -->
    
	<div class="form-row">
    <div class="input-wrapper">
    <label for="floorplan_sf">Square Footage</label>
    <input type="text" name="floorplan_sf" id="floorplan_sf" value="<? echo $floorplan_sf; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->
    
	<div class="form-row">
    <div class="input-wrapper">
    <label for="floorplan_image">Image <span class="small">(480 x 490 Pixels)</span></label>
    <? if($floorplan_image != '') { echo '<img src="'.$floorplan_image.'" style="float: left; margin-right: 18px;" width="150">'; } else { } ?>
    <input type="file" name="file" id="file" class="input-full" />
    <div class="clear"></div>
    </div>
    </div>
    
    <!-- --------------------- -->
</form>