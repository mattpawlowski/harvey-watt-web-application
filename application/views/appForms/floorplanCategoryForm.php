<form id="pageForm" name="pageForm" method="post" action="/admin/floorplans/categories/save/<? echo $category_id; ?>">
    <input type="hidden" name="category_id" id="category_id" value="<? echo $category_id; ?>">
	<div class="form-row">
    <div class="input-wrapper">
    <label for="category_name">Category Name</label>
    <input type="text" name="category_name" id="category_name" value="<? echo $category_name; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <input type="hidden" name="category_slug" id="category_slug" value="<? echo $category_slug; ?>" class="input-full">
    <label for="category_slug">Category Slug</label>
    <input type="text" name="category_slug_hidden" id="category_slug_hidden" value="<? echo $category_slug; ?>" class="input-full disabled" disabled="disabled">
    </div>
    </div>
    
    <!-- --------------------- -->
</form>

<script type="text/javascript">
$(document).ready(function() {
    var typingTimer;
    var doneTypingInterval = 800;
    
    $('#category_name').keyup(function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(buildURL, doneTypingInterval);        
    });
    
    $('#category_name').keydown(function() {
       clearTimeout(typingTimer);
    });
    
    $('#category_name').blur(function() {
       buildURL(); 
    });
});


function buildURL() {
    var request = $('#category_name').val();
    var rstr = request.replace(/[^a-zA-Z0-9 ]/g, "")
    var rstr = $.trim(rstr);
    var rstr = rstr.replace(/\s+/g, '-').toLowerCase();
    $('#category_slug_hidden').val(rstr);
}

function convertData() {
    $('#category_slug').val($('#category_slug_hidden').val());	   
}
</script>