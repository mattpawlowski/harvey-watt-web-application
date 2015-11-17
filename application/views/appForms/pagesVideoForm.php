<form id="pageForm" name="pageForm" method="post" action="/admin/pages/all/videossave/<? echo $page_id; ?>">
    <input type="hidden" name="page_id" id="page_id" value="<? echo $page_id; ?>">
    <input type="hidden" name="video_embed_id" id="video_embed_id" value="">
    <input type="hidden" name="video_embed_type" id="video_embed_type" value="">

	<div class="form-row">
    <div class="input-wrapper">
    <label for="video_url">
    Video URL  <img src="/images/app/icons/help.png" class="help-icon help" data-subject="video-url"></label>
    <input type="text" name="video_url" id="video_url" value="" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="video_title">Video Title</label>
    <input type="text" name="video_title" id="video_title" value="" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="video_desc">Video Description</label>
    <textarea type="text" name="video_desc" id="video_desc" style="height: 65px;"></textarea>
    </div>
    </div>
    
    <!-- --------------------- -->
</form>

<script type="text/javascript">
    $(document).ready(function() {
        
        var youtubeActive = 'false';
        var vimeoActive = 'false';
        
        // Activate YouTube Icon
        function activateYoutube(request) {
           $('#video_url').parent('.input-wrapper').css('background','url(/images/app/icons/youtube-color.jpg)').css('background-repeat','no-repeat').css('background-position','1px 34px').css('background-color','#FFFFFF');
           $('#video_url').val('       '+request);
        }
        
        // Activate Vimeo Icon
        function activateVimeo(request) {
           $('#video_url').parent('.input-wrapper').css('background','url(/images/app/icons/vimeo-color.jpg)').css('background-repeat','no-repeat').css('background-position','1px 34px').css('background-color','#FFFFFF');
           $('#video_url').val('       '+request);
        }
        
        // Parse YouTube ID
        function calcYoutubeId(url) {
			var videoID = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i)[1];			
			$("#video_embed_id").val(videoID);   
        }
        
        // Get YouTube Title 
        function getYoutubeTitle() {
            var id = $('#video_embed_id').val();
            var youTubeURL = 'http://gdata.youtube.com/feeds/api/videos/' + id + '?v=2&alt=json';
			var json = (function() {
				var json = null;
				$.ajax({
					'async': false,
					'global': false,
					'url': youTubeURL,
					'dataType': "json",
					'success': function(data) {
						json = data;
					}
				});
				return json;
			})();
			setTitle(json.entry.title.$t);
        }
        
        // Get YouTube Description
        function getYoutubeDesc() {
            var id = $('#video_embed_id').val();
            var youTubeURL = 'http://gdata.youtube.com/feeds/api/videos/' + id + '?v=2&alt=json';
			var json = (function() {
				var json = null;
				$.ajax({
					'async': false,
					'global': false,
					'url': youTubeURL,
					'dataType': "json",
					'success': function(data) {
						json = data;
					}
				});
				return json;
			})();
			setDesc(json.entry.media$group.media$description.$t);
        }
        
        // Parse Vimeo ID
        function calcVimeoId(url) {
            var regExp = /http:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;
			var match = url.match(regExp);			
			if (match){
				var videoID = match[2];
			} else {
				//alert("This doens't seem to be a valid VIMEO url");
			}	
			$("#video_embed_id").val(videoID);   
        }
        
        // Get Vimeo Title
        function getVimeoTitle() {
            var id = $('#video_embed_id').val();
            $.getJSON('http://www.vimeo.com/api/v2/video/' + id + '.json?callback=?', {format: "json"}, function(data) {
				var vimTitle = data[0].title;
				setTitle(vimTitle);
			});
        }
        
        // Get Vimeo Description
        function getVimeoDesc() {
            var id = $('#video_embed_id').val();
            $.getJSON('http://www.vimeo.com/api/v2/video/' + id + '.json?callback=?', {format: "json"}, function(data) {
				var vimDesc = data[0].description;
				setDesc(vimDesc);
			});
        }
        
        // Set Title Value
        function setTitle(str) {
            $('#video_title').val(str);   
        }
        
        // Set Title Value
        function setDesc(str) {
            $('#video_desc').val(str);   
        }
        
        // Set Video Type KEY
        function setType(str) {
            $('#video_embed_type').val(str);   
        }
        
        // Listen for changes in URL field
        $('#video_url').on('input',function() {
           var activeMatch = 'false';
           var request = $('#video_url').val().trim();
           
           // Check for Youtube Match
           var youtube = request.match(/youtube.com/g);
           if(youtube) { activateYoutube(request); calcYoutubeId(request); getYoutubeTitle(); getYoutubeDesc(); setType('youtube'); activeMatch = 'true'; }
           
           // Check for Vimeo
           var vimeo = request.match(/vimeo.com/g);
           if(vimeo) { activateVimeo(request); calcVimeoId(request); getVimeoTitle(); getVimeoDesc(); setType('vimeo'); activeMatch = 'true'; }
           
           // No active match = undo styles
           if(activeMatch == 'false') { $('#video_url').parent('.input-wrapper').removeAttr('style'); $('#video_url').val(request); }
        });
        
        
    });
</script>