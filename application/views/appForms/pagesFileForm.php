<form id="pageForm" name="pageForm" method="post" action="/admin/pages/all/filessave/<? echo $page_id; ?>" enctype="multipart/form-data">

	<div class="form-row">
    <div class="input-wrapper">
    <label for="file">
    Upload New File:</label>
    <input type="file" name="file" id="file" value="" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="file_title">Title</label>
    <input type="text" name="file_title" id="file_title" value="" class="input-full" />
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
            if(($('#file_title').val()) == '') {
            $('#file_title').val(str);
            }
       });
    });
</script>