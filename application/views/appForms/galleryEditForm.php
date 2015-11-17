<form id="pageForm" name="pageForm" method="post" action="/admin/gallery/all/save/<? echo $gallery_id; ?>">
    <input type="hidden" name="gallery_id" id="gallery_id" value="<? echo $gallery_id; ?>">
	<div class="form-row">
    <div class="input-wrapper">
    <label for="gallery_name">Category Name</label>
    <input type="text" name="gallery_name" id="gallery_name" value="<? echo $gallery_name; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <input type="hidden" name="gallery_slug" id="gallery_slug" value="<? echo $gallery_slug; ?>" class="input-full">
    <label for="gallery_slug">Category Slug</label>
    <input type="text" name="gallery_slug_hidden" id="gallery_slug_hidden" value="<? echo $gallery_slug; ?>" class="input-full disabled" disabled="disabled">
    </div>
    </div>
    
    <!-- --------------------- -->
</form>

<script type="text/javascript">
$(document).ready(function() {
    var typingTimer;
    var doneTypingInterval = 800;
    
    $('#gallery_name').keyup(function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(buildURL, doneTypingInterval);        
    });
    
    $('#gallery_name').keydown(function() {
       clearTimeout(typingTimer);
    });
    
    $('#gallery_name').blur(function() {
       buildURL(); 
    });
});


function buildURL() {
    var request = $('#gallery_name').val();
    var rstr = request.replace(/[^a-zA-Z0-9 ]/g, "")
    var rstr = $.trim(rstr);
    var rstr = rstr.replace(/\s+/g, '-').toLowerCase();
    $('#gallery_slug_hidden').val(rstr);
}

function convertData() {
    $('#gallery_slug').val($('#gallery_slug_hidden').val());	   
}
</script>