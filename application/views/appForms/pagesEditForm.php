<form id="pageForm" name="pageForm" method="post" action="/admin/pages/all/save/<? echo $page_id; ?>">
    <input type="hidden" name="page_id" id="page_id" value="<? echo $page_id; ?>">

	<div class="form-row">
    <div class="input-wrapper">
    <label for="page_name">Page Name</label>
    <input type="text" name="page_name" id="page_name" value="<? echo $page_name; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="page_subtitle">Subtitle</label>
    <input type="text" name="page_subtitle" id="page_subtitle" value="<? echo $page_subtitle; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="page_parent">Page Layout</label>
    <? echo $layout_select; ?>
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="page_parent">Parent</label>
    <? 
    if($page_index == '1') {
        echo '<input type="hidden" name="page_parent" id="page_parent" value="0">';
        echo '<select name="page_parent_dis" id="page_parent" disabled><option value="0">--NONE--</option></select>';   
    } else {
        echo $parent_select; 
    }
    ?>
    </div>
    </div>
    
    <!-- --------------------- -->
    
    <div class="form-row">
    <div class="input-wrapper">
    <label for="page_url_disabled">URL (Generated Automatically)</label>
    <? if($page_index == '1') { ?>
    <input type="hidden" name="page_url" id="page_url" value="/" />
    <input type="text" name="page_url_disabled" id="page_url_disabled" value="/" disabled class="input-full disabled" />
    <? } else { ?>
    <input type="hidden" name="page_url" id="page_url" value="<? echo $page_url; ?>" />
    <input type="text" name="page_url_disabled" id="page_url_disabled" value="<? echo $page_url; ?>" disabled class="input-full disabled" />
    <? } ?>
    </div>
    </div>
    
    <!-- --------------------- -->
    
    <input type="hidden" name="page_content" id="page_content" value='<? echo $page_content; ?>'>
    <div class="content-blocks">
	<? echo $content_blocks; ?>
    </div>
    <!-- --------------------- -->
</form>

<script type="text/javascript">

    $(window).load(function() {
        setupWYSIWYG();
        //createContent();
    });

    // Our typing timer etc and functions
    // for building our url to be awesome-sauce
    // Boosh, Kakow!
    var typingTimer;
    var doneTypingInterval = 800;
    
    <? if($page_index != '1') { ?>
    $('#page_name').keyup(function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(buildURL, doneTypingInterval);        
    });
    
    $('#page_name').keydown(function() {
       clearTimeout(typingTimer); 
    });
    
    $('#page_name').blur(function() {
       buildURL(); 
    });
    
    $('#page_parent').change(function() {
       buildURL(); 
    });
    <? } ?>
    
    
    // This actually builds the url for us
    // only allows numbers letters and spaces
    // converts spaces to hyphens
    function buildURL() {
    var request = $('#page_name').val();
    var rstr = request.replace(/[^a-zA-Z0-9 ]/g, "")
    var rstr = $.trim(rstr);
    var rstr = rstr.replace(/\s+/g, '-').toLowerCase();
       
       var pRequest = $('#page_parent').val();
       if(pRequest != '0') {
        var ajaxReturn = $.ajax({
              url: '/admin/pages/all/get_url/'+pRequest,
              type: 'POST',
              async: false,
              success: function(html) {
                 return html;
              }
         });
        $('#page_url_disabled').val(ajaxReturn.responseText + '/' + rstr);
       } else {
        $('#page_url_disabled').val('/' + rstr);
       }
       
    }
    
    // Propagate our warning to users
    // if they change the layout template
    // that they could lose content
    var currentLayout;
    
    $('#page_layout').on("focus",function() {
        currentLayout = $(this).val();
        console.log(currentLayout);  
    }).change(function() {
        var title = 'Warning: Changing Your Layout Template';
        var content = '<p>Changing your layout template <b>will</b> result in a <b>LOSS OF CONTENT</b>. Different layout templates can have different content blocks and editable regions, and due to this, will not be able to transport any current content. <i>We would encourage you to copy any current content into a document on your system before continuing.</i> Would you still like to proceed?</p><p><input type="button" value="Cancel" class="cancel-layout" onclick="cancelLayout();"><input type="button" value="Yes, I want to Change the Layout Template" class="approve" onclick="approveLayout();"></p>';
        
        fdcmsAlert(title,content,'warning');   
    });
    
    $('.fdcms-screen').on("click",function() {
       cancelLayout(); 
    });
    
    
    function cancelLayout() {
        console.log(window.currentLayout);
        $('#page_layout').val(window.currentLayout);
        fdcmsClose();
    }
    
    function approveLayout() {
        var layout = $('#page_layout').val();
        $.ajax({
            url: '/admin/pages/all/updateBlocks',
            type: "POST",
            async: false,
            data: ({'layout':layout}),
            success: function(html) {
                $('.content-blocks').slideUp(250);
                $('.content-blocks').html(html);
                $('.content-blocks').slideDown(250);
                setupWYSIWYG();
                fdcmsClose();
            }
        });
    }
    
    function setupWYSIWYG() {
        tinymce.init({ selector: ".editor", menubar: false, plugins: [ "advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste jbimages" ], toolbar: "bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | fullscreen code", autosave_ask_before_unload: false, max_height: 450, min_height: 160, height : 180, setup : createContent(), resize : false, relative_urls: false });
    }
    
    function createContent() {
        var pageContent = $('#page_content').val();
        try {
            pageContent = $.parseJSON(pageContent);
           
            $.each(pageContent,function(i, item) {
                var content = decodeURIComponent(unescape(item));
                var elementID = i;
                console.log('content: '+content);
                console.log('id: '+elementID);
                $('#'+elementID).val(content);
            });
        }
        catch(err) {
            $('.editor').val('<i>Please enter content here</i>'); 
        }
    }
    
    // Convert data takes all of our dynamic content blocks and builds them into a jSon String
    // It's super-duper
    function convertData() {
        console.log('Converting Page Data for dB Storage...');
        var jsonString = '{';			
        $(".editor").each(function() {				
            var id = $(this).attr("id");
            var thisContent = tinyMCE.get(id).getContent();			
            jsonString = jsonString + '"' + id + '"';
            jsonString = jsonString + ':';
            jsonString = jsonString + '"' + escape(thisContent) + '",';				
        });			
        jsonString = jsonString + '}';			
        var final = jsonString.replace('\,\}','\}');		
        $('#page_content').val(final);
        $('#page_url').val($('#page_url_disabled').val());	
	};
</script>