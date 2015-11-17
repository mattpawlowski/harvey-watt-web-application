<form id="pageForm" name="pageForm" method="post" action="/admin/pages/all/metasave/<? echo $page_id; ?>">
    <input type="hidden" name="page_id" id="page_id" value="<? echo $page_id; ?>">

	<div class="form-row">
    <div class="input-wrapper">
    <label for="page_meta_title">Meta Title <img src="/images/app/icons/help.png" class="help-icon help" data-subject="meta-title"></label>
    <input type="text" name="page_meta_title" id="page_meta_title" value="<? echo $page_meta_title; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="page_meta_desc">Meta Desc <img src="/images/app/icons/help.png" class="help-icon help" data-subject="meta-desc"></label>
    <textarea type="text" name="page_meta_desc" id="page_meta_desc" style="height: 65px;"><? echo $page_meta_desc; ?></textarea>
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="page_meta_desc">Advanced: Canonical URLs <span class="small">( Comma Seperated )</span> <img src="/images/app/icons/help.png" class="help-icon help" data-subject="meta-canonical"></label>
    <textarea type="text" name="page_meta_canonical" id="page_meta_canonical" style="height: 65px;"><? echo $page_meta_canonical; ?></textarea>
    </div>
    </div>
    
    <!-- --------------------- -->
        
    <div class="google-preview">
        <img src="/images/app/core/google-preview.png">
        <div class="google-text">
            <div class="google-title"><a href="javascript: void(0);" class="google-title-link">Test test</a></div>
            <div class="google-url"><? echo base_url().substr($page_url,1); ?></div>
            <div class="google-desc">blah blah blah blah blah</div>
        </div>
        <div class="clear"></div>
    </div>
    
    <!-- --------------------- -->
</form>

<script type="text/javascript">
    $(document).ready(function() {
       
       // Set initial values
       setupPreview();
       
       // Listen for stuff
       $('#page_meta_title').keyup(function() {
          setupPreview(); 
       });
       
       $('#page_meta_desc').keyup(function() {
          setupPreview(); 
       });
        
    });
    
    function setupPreview() {
        var titleStr = $('#page_meta_title').val();
        var descStr = $('#page_meta_desc').val(); 
        
        if(titleStr.length > 55) { 
            titleStr = titleStr.substring(0,55)+' ...';
        }
        if(descStr.length > 155) { 
            descStr = descStr.substring(0,155)+' ...';
        }
        
        $('a.google-title-link').html(titleStr);
        $('div.google-desc').html(descStr);
        
    }
</script>