<form action="" method="POST" id="pageForm" name="pageForm">
<div class="form-row">
    <div class="input-wrapper">
    <label for="gallery_id">Upload to Gallery:</label>
    <? echo $gallery_list; ?>
    </div>
</div>
</form>

<div id="refresh">
    <div id="dropbox"><span class="message">Drag and Drop images here to upload.<br /><i>(Click 'Done' on right when finished)</i></span></div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#gallery_id').change(function() {
            var html = '<div id="dropbox"><span class="message">Drag and Drop images here to upload.<br /><i>(Click \'Done\' on right when finished)</i></span></div>';
            $('#refresh').html(html);
            setupDropbox();
        });
        setupDropbox();
    });
    
    function convertData() {
        var galleryId = $('#gallery_id').val();
        $('#pageForm').attr('action','/admin/gallery/all/manage/'+galleryId);
        return true;
    }
</script>