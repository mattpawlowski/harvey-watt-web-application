<?
if(isset($image_src)) {
echo '<div class="current-image">';
echo '<img src="'.$image_src.'">';
echo '</div>';    
}
?>


<form id="pageForm" name="pageForm" method="post" action="/admin/pages/all/imagessave/<? echo $page_id; ?>" enctype="multipart/form-data">

	<div class="form-row">
    <div class="input-wrapper">
    <label for="file">
    Upload New Image:</label>
    <input type="file" name="file" id="file" value="" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="image_title">Title</label>
    <input type="text" name="image_title" id="image_title" value="<? if(isset($image_title)) { echo $image_title; } ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="image_link">Link</label>
    <input type="text" name="image_link" id="image_link" value="<? if(isset($image_link)) { echo $image_link; } ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->
</form>

<script type="text/javascript">
    $(document).ready(function() {
       // For dev purposes
       $('#file').change(function() {
            var str = $(this).val();
            var str = str.match(/[^\/\\]+$/);
            if(($('#image_title').val()) == '') {
            $('#image_title').val(str);
            }
       });
       
    });
    
    function convertData() {     
        // Make sure out links are local
        var baseURL = '<?php echo BASE_URL(); ?>'; 
        var str = $('#image_link').val();
        str = str.replace(''+baseURL+'','/');
        $('#image_link').val(str);   
    }
</script>