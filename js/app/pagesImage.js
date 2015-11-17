// JavaScript Document

$(document).ready(function() {
   
   $('.upload-new').click(function() {
        var pageId = $(this).attr('data-page');
        var imageId = $(this).attr('data-media');
        var imageSrc = $(this).attr('data-src');
        var imageName = $(this).attr('data-name');
        
        var html = '<form action="/admin/pages/all/imagesupdate/'+pageId+'" method="post" enctype="multipart/form-data">';
            html += '<input type="hidden" name="page_id" id="page_id" value="'+pageId+'">';
            html += '<input type="hidden" name="image_id" id="image_id" value="'+imageId+'">';
            html += '<input type="hidden" name="image_src" id="image_src" value="'+imageSrc+'">';
            html += '<input type="hidden" name="image_title" id="image_title" value="'+imageName+'">';
            html += '<table><tr><td>';
            html += '<input type="file" name="file" id="file" value="" class="input-full" />';
            html += '</td><td>';
            html += '<input type="submit" value="Save Changes" class="form-button">';
            html += '</td></tr>';
            html += '</table>';
            html += '</form>';
        
        fdcmsAlert('Upload New Image',html);
   });
   
   $('.image-edit').click(function() {
        var imageId = $(this).attr('data-id');
        var imageLink = $(this).attr('data-link');
        var imageTitle = $(this).attr('data-title');
        var pageId = $(this).attr('data-page');
        
        var html = '<form action="/admin/pages/all/imagesdetails/'+pageId+'" method="post" enctype="multipart/form-data">';
            html += '<input type="hidden" name="page_id" id="page_id" value="'+pageId+'">';
            html += '<input type="hidden" name="image_id" id="image_id" value="'+imageId+'">';
            html += '<input type="hidden" name="image_src" id="image_src" value="'+imageLink+'">';
            html += '<input type="hidden" name="image_title" id="image_title" value="'+imageTitle+'">';
            html += '<table>';
            html += '<tr>';
            html += '<td>';
            html += '<div class="form-row">';
            html += '<div class="input-wrapper" style="width: 250px;">';
            html += '<label for="image_title">Title</label>';
            html += '<input type="text" name="image_title" id="image_title" value="'+imageTitle+'" class="input-full" />';
            html += '</div>';
            html += '</div>';
            html += '</td>';
            html += '</tr>';
            html += '<tr>';
            html += '<td>';
            html += '<div class="form-row">';
            html += '<div class="input-wrapper" style="width: 250px;">';
            html += '<label for="image_link">Link</label>';
            html += '<input type="text" name="image_link" id="image_link" value="'+imageLink+'" class="input-full" />';
            html += '</div>';
            html += '</div>';
            html += '</td>';
            html += '</tr>';
            html += '<tr>';
            html += '<td align="center">';
            html += '<input type="submit" value="Save Changes" class="form-button">';
            html += '</td>';
            html += '</tr>';
            html += '</table>';
            html += '</form>';
        
        
        fdcmsAlert('Edit Details',html);
   });
    
});