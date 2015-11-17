<form id="pageForm" name="pageForm" method="post" action="/admin/locations/maps/savestyles/<? echo $map_id; ?>">
    <input type="hidden" name="map_id" id="map_id" value="<? echo $map_id; ?>">
	<div class="form-row">
    <div class="input-wrapper">
    <label for="map_name">Map Styles</label>
    <textarea name="map_styles" id="map_styles"><? echo $map_styles; ?></textarea>
    </div>
    </div>
</form>