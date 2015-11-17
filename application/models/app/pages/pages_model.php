<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages_model extends CI_Model {
    
    public $primary_table;
    public $path;
	
    
	// ----------------------------------- //
	// Initialize Our Primary Table
	// ----------------------------------- //
	public function initialize($table,$path) {
		$this->primary_table = $table;	
        $this->path = $path;
	}

	// ----------------------------------- //
	// Load all of our pages in a sortable
    // table
	// ----------------------------------- //
    public function renderSortableTable() {
        $rstr = '';
        
        $rstr .= '<table class="sortable fdcms-table" width="100%" cellpadding="0" cellspacing="0">';
        
        $rstr .= $this->renderTableRows();
        
        $rstr .= '</table>';
        
        $rstr .= '
            <script>
			var fixHelper = function(e, ui) {
				ui.children().each(function() {
					$(this).width($(this).width());
				});
				return ui;
			};
			
			$(".sortable tbody").sortable({
				helper: fixHelper,
				update: function(event, ui) {
					var newOrder = $(this).sortable(\'toArray\').toString();
					$.post(\'/admin/pages/all/order\', {order:newOrder});
				}
			}).disableSelection();
			</script>
        ';
        
        return $rstr;
    }

	// ----------------------------------- //
	// Load all of our pages in a sortable
    // table
	// ----------------------------------- //
    public function renderRecurseTable() {
        $rstr = '';
        
        $rstr .= '<table class="fdcms-table" width="100%" cellpadding="0" cellspacing="0">';
        
        $rstr .= $this->renderTableRows();
        
        $rstr .= '<tr>';
        $rstr .= '<td colspan="2">';
        $rstr .= '<img src="/images/app/core/arrow_ltr.png"> <a href="javascript: void(0);" class="check-all edit-link">Check</a> / <a href="javascript: void(0);" class="uncheck-all delete-link">Uncheck</a> All';
        $rstr .= '<a href="javascript:void(0);" class="delete delete-selected" data-post="'.$this->path.'/delete">Delete Selected</a>';
        $rstr .= '</td>';
        $rstr .= '</tr>';
        
        $rstr .= '</table>';
        
        return $rstr;
    }

	// ----------------------------------- //
	// Row output for pages table
	// ----------------------------------- //
    public function renderTableRows($parent = 0,$level = 0) {
        $rstr = '';
        
        $this->db->where("page_parent",$parent);
        $this->db->from($this->primary_table);
        $this->db->order_by("sort_order", "asc");
        $query = $this->db->get();
        $result = $query->result();
        
        foreach($result as $rs) {
            
            $class = ' class="native"';
            if($level == 0) { $style = ' style="font-weight: 700;"'; } else { $style = ''; }
            
            $i = 1;
            $tabIn = '';
            while($i <= $level) {
                $tabIn .= '&mdash;'; 
                $i++;  
            }
            
            $optionStyle = ' style="margin-left: '.(($level * 12)+4).'px"';
            
            $rstr .= '<tr id="'.$rs->page_id.'">';
            
            // delete checkbox
            $rstr .= '<td width="20"'.$class.$style.' valign="top">';
            if($rs->page_locked != '1') {
                $rstr .= '<input type="checkbox" name="'.$rs->page_id.'" value="'.$rs->page_id.'" id="box_'.$rs->page_id.'" class="del-box" data-id="'.$rs->page_id.'" data-post="'.$this->path.'/all/delete/'.$rs->page_id.'">';
            } else {
                $rstr .= '<img src="/images/app/icons/locked.png">';	
            }
            $rstr .= '</td>';
            
            $rstr .= '<td'.$class.$style.'>';
            $rstr .= '<div class="options-hover">';
            $rstr .= $tabIn.' '.$rs->page_name;
            $rstr .= '<span class="row-options"'.$optionStyle.'>';
            $rstr .= '<a href="'.$this->path.'/all/edit/'.$rs->page_id.'" class="edit-link">Edit</a>';
            if($rs->page_locked != '1') {
                $rstr .= ' | <a href="'.$this->path.'/all/duplicate/'.$rs->page_id.'" class="duplicate-link duplicate-item">Duplicate</a>';
                $rstr .= ' | <a href="javascript:void(0)" class="delete-link delete-item" data-id="'.$rs->page_id.'" data-post="'.$this->path.'/all/delete/'.$rs->page_id.'">Delete</a>';
            }
            $rstr .= '</span>';
            $rstr .= '</div>';
            $rstr .= '</td>';
            
            $rstr .= '</tr>';  
            
            // Check for child pages
            $rstr .= $this->renderTableRows($rs->page_id,($level+1));
             
        }
        
        return $rstr;
    }

	// ----------------------------------- //
	// Special Toolbox for Home Page
	// ----------------------------------- //
    public function renderHomeToolbox() {
        $rstr .= '';
        
        return $rstr;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function create() {
        
        $data = array(
            'page_id' => '0',
            'page_index' => '0',
            'page_name' => 'My New Page',
            'page_subtitle' => '',
            'page_url' => '/my-new-page',
            'page_content' => '',
            'action' => 'create',
            'parent_select' => $this->renderPageSelect(0),
	        'layout_select' => $this->renderPageLayout(0),
			'content_blocks' => $this->renderContentBlocks('layout.php')
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/pagesEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function edit() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT * FROM fdcms_pages WHERE page_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        
        
        $data = array(
            'page_id' => $row->page_id,
            'page_index' => $row->page_index,
            'page_name' => $row->page_name,
            'page_subtitle' => $row->page_subtitle,
            'page_url' => $row->page_url,
            'page_content' => $row->page_content,
            'action' => 'edit',
            'parent_select' => $this->renderPageSelect($row->page_id),
	        'layout_select' => $this->renderPageLayout($row->page_id),
			'content_blocks' => $this->renderContentBlocks($row->page_layout)
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/pagesEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function save() {
        $id = $this->uri->segment(5);
        $data = array(
            'page_id' => $this->input->post('page_id'),
            'page_name' => $this->input->post('page_name'),
            'page_subtitle' => $this->input->post('page_subtitle'),
            'page_layout' => $this->input->post('page_layout'),
            'page_parent' => $this->input->post('page_parent'),
            'page_url' => $this->input->post('page_url'),
            'page_content' => $this->input->post('page_content'),
        );
        
        
        if($id == 0) {
            // Creating a New Page
            $data["page_meta_title"] = $this->input->post('page_name');
            $data["page_meta_desc"] = '';
            
            $this->db->insert('fdcms_pages',$data);
			$return["id"] = $this->db->insert_id();
            $return["message"] = "Your new page was created successfully.";
            
            // Put the URL in the url_rewrites table
            $data = array( 'url' => $this->input->post('page_url'), 'page_id' => $return["id"] );
            $this->db->insert('fdcms_url_rewrites',$data);
        } else {
            // Saving an existing Page    
            $this->db->where('page_id',$id);
			$this->db->update('fdcms_pages',$data);
            
            // Update the url_rewrites table
            $data = array( 'url' => $this->input->post('page_url'), 'page_id' => $id );
            $this->db->where('page_id',$id);
			$this->db->update('fdcms_url_rewrites',$data);            
            $return["id"] = $id;
            $return["message"] = "Your changes have been saved.";
        }
        
        return $return;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function meta() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT page_id,page_meta_title,page_meta_desc,page_meta_canonical,page_url FROM fdcms_pages WHERE page_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        
        
        $data = array(
            'page_id' => $row->page_id,
            'page_meta_title' => $row->page_meta_title,
            'page_meta_desc' => $row->page_meta_desc,
            'page_meta_canonical' => $row->page_meta_canonical,
            'page_url' => $row->page_url,
            'action' => 'edit'
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/pagesMetaForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function metaSave() {
        $id = $this->uri->segment(5);
        $data = array(
            'page_meta_title' => $this->input->post('page_meta_title'),
            'page_meta_desc' => $this->input->post('page_meta_desc'),
            'page_meta_canonical' => $this->input->post('page_meta_canonical')
        );
        
            // Saving an existing Page    
            $this->db->where('page_id',$id);
			$this->db->update('fdcms_pages',$data);
                    
            $return["id"] = $id;
            $return["message"] = "Your changes have been saved.";
        
        return $return;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function videos() {
        $id = $this->uri->segment(5);
        
        $rstr = '';
            
        $data["page_id"] = $id;
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/pagesVideoForm', $data, true);
        $rstr .= '</div>';
            
        
        $rstr .= '<table class="sortable fdcms-table" width="100%" cellpadding="0" cellspacing="0">';         
        $rstr .= $this->renderVideoRows($id);            
        $rstr .= '</table>';
        
        $rstr .= '
            <script>
            var fixHelper = function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            };                
            $(".sortable tbody").sortable({
                helper: fixHelper,
                update: function(event, ui) {
                    var newOrder = $(this).sortable(\'toArray\').toString();
                    $.post(\'/admin/pages/all/videosorder\', {order:newOrder});
                }
            }).disableSelection();
            </script>
        ';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function renderVideoRows($id) {
        $SQL = "SELECT * FROM fdcms_media_videos v JOIN fdcms_media_v2p v2p ON (v.video_id = v2p.video_id) WHERE v2p.page_id = '".$id."' ORDER BY video_sort_order ASC";
        $query = $this->db->query($SQL);
        $results = $query->result();
        
        $rstr = '';
        
        if($query->num_rows() > 0) {
        foreach($results as $rs) {
            $rstr .= '<tr id="'.$rs->video_id.'">';
            
                // Thumbnail
                $rstr .= '<td width="100" class="native">';
                $rstr .= '<div class="vid-thumb">';
                if($rs->video_embed_type == 'youtube') {
                    $rstr .= '<img src="http://i1.ytimg.com/vi/'.$rs->video_embed_id.'/mqdefault.jpg" style="width: 100%; height: auto;">';
                } else if($rs->video_embed_type == 'vimeo') {
                    $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$rs->video_embed_id.".php"));
                    $image = $hash[0]['thumbnail_medium'];
                    $rstr .= '<img src="'.$image.'" style="width: 100%; height: auto;">';
                } else {
                    $rstr .= '<i>Unknown</i>';
                }
                $rstr .= '</div>';
                $rstr .= '</td>';
                
                // Title
                $rstr .= '<td class="native">';
                $rstr .= '<h3>'.$rs->video_title.'</h3>';                
                
                if($rs->video_embed_type == 'youtube') {
                    $rstr .= '<a href="http://www.youtube.com/watch?v='.$rs->video_embed_id.'" target="_blank">http://www.youtube.com/watch?v='.$rs->video_embed_id.'</a>';
                } else if($rs->video_embed_type == 'vimeo') {
                    $rstr .= '<a href="http://www.vimeo.com/'.$rs->video_embed_id.'" target="_blank">http://www.vimeo.com/'.$rs->video_embed_id.'</a>';
                } else {
                    $rstr .= '<i>Unknown</i>';	
                }
                $rstr .= '</td>';
                
                // Remove
                $rstr .= '<td class="native">';
                $rstr .= '<form action="/admin/pages/all/videosdelete" method="post">';
                $rstr .= '<input type="hidden" name="video_id" id="video_id" value="'.$rs->video_id.'">';
                $rstr .= '<input type="hidden" name="video_title" id="video_title" value="'.$rs->video_title.'">';
                $rstr .= '<input type="hidden" name="page_id" id="page_id" value="'.$rs->page_id.'">';
                $rstr .= '<input type="submit" value="Remove" class="form-submit">';
                $rstr .= '</form>';
                $rstr .= '</td>';
            
            $rstr .= '</tr>';    
        }
        } else {
            $rstr .= '<div class="form-row"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>This page has no featured videos - to add a video, please use the form above.</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="add-videos"></div></div>';   
        }
        
        return $rstr;
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function videosSave() {
        $id = $this->uri->segment(5);
        $data = array(
            'video_embed_id' => $this->input->post('video_embed_id'),
            'video_embed_type' => $this->input->post('video_embed_type'),
            'video_title' => $this->input->post('video_title'),
            'video_desc' => $this->input->post('video_desc')
        );
        
            $this->db->insert('fdcms_media_videos',$data);
            $insertId = $this->db->insert_id();
            
        $data = array(
            'page_id' => $this->input->post('page_id'),
            'video_id' => $insertId
        );
        
            $this->db->insert('fdcms_media_v2p',$data);
                    
            $return["id"] = $this->input->post('page_id');
            $return["message"] = "Your video [<b>".$this->input->post('video_title')."</b>] was successfully added.";
        
        return $return;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function files() {
        $id = $this->uri->segment(5);
        
        $rstr = '';
            
        $data["page_id"] = $id;
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/pagesFileForm', $data, true);
        $rstr .= '</div>';
            
        
        $rstr .= '<table class="sortable fdcms-table" width="100%" cellpadding="0" cellspacing="0">';         
        $rstr .= $this->renderFileRows($id);            
        $rstr .= '</table>';
        
        $rstr .= '
            <script>
            var fixHelper = function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            };                
            $(".sortable tbody").sortable({
                helper: fixHelper,
                update: function(event, ui) {
                    var newOrder = $(this).sortable(\'toArray\').toString();
                    $.post(\'/admin/pages/all/filesorder\', {order:newOrder});
                }
            }).disableSelection();
            </script>
        ';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function renderFileRows($id) {
        $SQL = "SELECT * FROM fdcms_media_files f JOIN fdcms_media_f2p f2p ON (f.file_id = f2p.file_id) JOIN fdcms_icons i ON (f.file_type = i.icon_key) WHERE f2p.page_id = '".$id."' ORDER BY file_sort_order ASC";
        $query = $this->db->query($SQL);
        $results = $query->result();
        
        $rstr = '';
        
        if($query->num_rows() > 0) {
        foreach($results as $rs) {
            $rstr .= '<tr id="'.$rs->file_id.'">';
            
                $rstr .= '<td width="35" class="native">';
                $rstr .= '<img src="'.$rs->icon_src.'">';
                $rstr .= '</td>';
                
                // Title
                $rstr .= '<td class="native">';
                $rstr .= '<h3>'.$rs->file_title.'</h3>';              
                
                    $rstr .= '<a href="'.$rs->file_src.'" target="_blank">'.$rs->file_src.'</a>';
                $rstr .= '</td>';
                
                // Remove
                $rstr .= '<td class="native">';
                $rstr .= '<form action="/admin/pages/all/filesdelete" method="post">';
                $rstr .= '<input type="hidden" name="file_id" id="file_id" value="'.$rs->file_id.'">';
                $rstr .= '<input type="hidden" name="file_src" id="file_src" value="'.$rs->file_src.'">';
                $rstr .= '<input type="hidden" name="file_title" id="file_title" value="'.$rs->file_title.'">';
                $rstr .= '<input type="hidden" name="page_id" id="page_id" value="'.$rs->page_id.'">';
                $rstr .= '<input type="submit" value="Remove" class="form-submit">';
                $rstr .= '</form>';
                $rstr .= '</td>';
            
            $rstr .= '</tr>';    
        }
        } else {
            $rstr .= '<div class="form-row"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>This page has no attached files - to add a new file, please use the form above.</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="add-videos"></div></div>';   
        }
        
        return $rstr;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function images() {
        $id = $this->uri->segment(5);
        
        $rstr = '';
            
        $data["page_id"] = $id;
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/pagesImageForm', $data, true);
        $rstr .= '</div>';
            
        
        $rstr .= '<table class="sortable fdcms-table" width="100%" cellpadding="0" cellspacing="0">';         
        $rstr .= $this->renderImageRows($id);            
        $rstr .= '</table>';
        
        $rstr .= '
            <script>
            var fixHelper = function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            };                
            $(".sortable tbody").sortable({
                helper: fixHelper,
                update: function(event, ui) {
                    var newOrder = $(this).sortable(\'toArray\').toString();
                    $.post(\'/admin/pages/all/imagesorder\', {order:newOrder});
                }
            }).disableSelection();
            </script>
        ';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function renderImageRows($id) {
        $SQL = "SELECT * FROM fdcms_media_images i JOIN fdcms_media_i2p i2p ON (i.image_id = i2p.image_id) WHERE i2p.page_id = '".$id."' ORDER BY image_sort_order ASC";
        $query = $this->db->query($SQL);
        $results = $query->result();
        
        $rstr = '';
        
        if($query->num_rows() > 0) {
        foreach($results as $rs) {
            $rstr .= '<tr id="'.$rs->image_id.'">';
            
                $rstr .= '<td width="35" class="native">';
                $rstr .= '<div class="image-thumb">';
                $rstr .= '<img src="'.$rs->image_src.'">';
                $rstr .= '<a href="javascript: void(0);" class="upload-new" data-page="'.$id.'" data-media="'.$rs->image_id.'" data-src="'.$rs->image_src.'" data-name="'.$rs->image_title.'"></a>';
                $rstr .= '</div>';
                $rstr .= '</td>';
                
                // Title
                $rstr .= '<td class="native">';
                $rstr .= '<h3>'.$rs->image_title.'</h3>';              
                
                if($rs->image_link != '') {
                    $rstr .= '<a href="'.$rs->image_link.'" target="_blank"><b>Links To:</b> '.$rs->image_link.'</a>';
                }
                
                $rstr .= '</td>';
                
                $rstr .= '<td class="native">';
                $rstr .= '<a href="jvaascript: void(0);" class="form-button image-edit" data-id="'.$rs->image_id.'" data-title="'.$rs->image_title.'" data-link="'.$rs->image_link.'" data-page="'.$id.'" style="color: #a9e01c;">Edit</a>';
                $rstr .= '</td>';
                
                // Remove
                $rstr .= '<td class="native">';
                $rstr .= '<form action="/admin/pages/all/imagesdelete" method="post">';
                $rstr .= '<input type="hidden" name="image_id" id="image_id" value="'.$rs->image_id.'">';
                $rstr .= '<input type="hidden" name="image_src" id="image_src" value="'.$rs->image_src.'">';
                $rstr .= '<input type="hidden" name="image_title" id="image_title" value="'.$rs->image_title.'">';
                $rstr .= '<input type="hidden" name="page_id" id="page_id" value="'.$rs->page_id.'">';
                $rstr .= '<input type="submit" value="Remove" class="form-submit">';
                $rstr .= '</form>';
                $rstr .= '</td>';
            
            $rstr .= '</tr>';    
        }
        } else {
            $rstr .= '<div class="form-row"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>This page has no featured images - to add a new image, please use the form above.</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="add-videos"></div></div>';   
        }
        
        return $rstr;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function editToolbox($id) {
        
        $toolboxArray = array();
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Page Content',
			'link' => '/admin/pages/all/edit/'.$id.'',
			'icon' => 'edit-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Page SEO',
			'link' => '/admin/pages/all/meta/'.$id.'',
			'icon' => 'seo-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Featured Images',
			'link' => '/admin/pages/all/images/'.$id.'',
			'icon' => 'image-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Featured Videos',
			'link' => '/admin/pages/all/videos/'.$id.'',
			'icon' => 'video-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Attach Files',
			'link' => '/admin/pages/all/files/'.$id.'',
			'icon' => 'attach-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Assign Widgets',
			'link' => '/admin/pages/all/widgets/'.$id.'',
			'icon' => 'widget-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
        
        return $toolboxArray;
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function getPageName($id) {
        $SQL = "SELECT page_name FROM fdcms_pages WHERE page_id = '".$id."'";   
        $query = $this->db->query($SQL);
        $result = $query->row();
        
        return $result->page_name;
    }
	
	// ------------------------------------- //
	// Page | Add | Render parent select
	// ------------------------------------- //
	public function renderPageSelect($id) {
		$page_parent_id = '0';
			
		if($id != '') {
			// get page parent
			$SQL = "SELECT page_id, page_parent FROM ".$this->primary_table." WHERE page_id = '$id'";	
			$query = $this->db->query($SQL);
			
			if($query->num_rows() > 0) {
				foreach($query->result() as $rs) {
					$page_parent_id = $rs->page_parent;	
				}
			}
		}
		
		if($page_parent_id == '0') {
			$none_selected = "SELECTED";
		} else {
			$none_selected = "";	
		}
		
		$rstr = '';	
		$rstr .= '<select name="page_parent" id="page_parent">';
		$rstr .= '<option value="0" '.$none_selected.'>--NONE--</option>';		
		$rstr .= $this->recursePageSelect($page_parent_id, $id, 0, 0);
		$rstr .= '</select>';
		
		return $rstr;			
	}
	
	// ------------------------------------- //
	// Page | Add | Recurse parent select
	// ------------------------------------- //
	public function recursePageSelect($activeParent = 0, $id = 0, $parent = 0, $level = 0) {
		$rstr = '';
		$nextLevel = $level + 1;
		
		$SQL = "SELECT * FROM ".$this->primary_table." WHERE page_parent = '".$parent."' AND (page_id != '1' AND page_id != '".$id."') ORDER BY sort_order ASC";
		$query = $this->db->query($SQL);
				
		if($query->num_rows() > 0) {
			foreach($query->result() as $rs) {
				$pad = '';
				$i = 0;
				while($i<$level) {
					$pad .= '&mdash;';
					$i++;	
				}
				
				if($rs->page_id == $activeParent) {
					$selected = "SELECTED";	
				} else {
					$selected = "";
				}
				
				$rstr .= '<option value="'.$rs->page_id.'" '.$selected.'>';
				$rstr .= $pad.''.$rs->page_name;
				$rstr .= '</option>';
				
				$rstr .= $this->recursePageSelect($activeParent, $id, $rs->page_id, $nextLevel);
			}
		}
	
		return $rstr;
	}
	
	// ------------------------------------- //
	// Page | Add | Render layouts
	// ------------------------------------- //
	public function renderPageLayout($id) {
		// Set Default Layout
		$selected_layout = 'layout.php';
		
		// Get Current Layout (if exists)
		if($id != '') {
			$SQL = "SELECT page_id, page_layout FROM ".$this->primary_table." WHERE page_id = '".$id."'";
			$query = $this->db->query($SQL);
			
			if($query->num_rows() > 0) {
				foreach($query->result() as $rs) {
					$selected_layout = $rs->page_layout;	
				} // end foreach
			} // endif
		} // endif
		
		// Get All the Possible Layouts
		$layoutArray = $this->getTemplates();
		
		$rstr = '';
		$rstr .= '<select name="page_layout" id="page_layout">';
		
			if($selected_layout == 'layout.php') { $selected = 'SELECTED'; } else { $selected = ''; }
			$rstr .= '<option value="layout.php" '.$selected.'>Default</option>';
		
			foreach($layoutArray as $key => $value) {
				if( is_array($value) ) {
					$templateFile = $value["file"];
					$templateName = $value["template"];
					
					if($selected_layout == $templateFile) { $selected = 'SELECTED'; } else { $selected = ''; }
					
					$rstr .= '<option value="'.$templateFile.'" '.$selected.'>';
					$rstr .= $templateName;
					$rstr .= '</option>';
				} // endif
			} // end foreach
		$rstr .= '</select>';
		
		return $rstr;
		
	}
	
	// ------------------------------------- //
	// Page | Add | Sniff out template files
	// ------------------------------------- //
	public function getTemplates() {
		// Load our helpers
		$this->load->helper('directory');
		$this->load->helper('file');
		
		// Build our Template Array
		$templateArray = array();
		
		$map = directory_map('./application/views/display', FALSE, FALSE);
		
		// Loop through files in our display/views
		foreach($map as $key => $value) {
			$fileNamePattern 	= '/\blayout-\b/';
			$fileName 			= (string)$value;
			
			// Check for layout file
			if(preg_match($fileNamePattern, $fileName, $x)) {
				$fileContents = read_file('./application/views/display/'.$fileName);
				$regex = '#Template Name: (.*)#';
				
				if(preg_match($regex, $fileContents, $match)) {
					$pushArray["file"] = $fileName;
					$pushArray["template"] = $match[1];
					array_push($templateArray, $pushArray);
				} // endif
			} // endif
		} // end foreach
		
		// return array
		return $templateArray;
	}
	
	// ------------------------------------- //
	// Page | Add | Render WYSIWYGs
	// ------------------------------------- //
	public function renderContentBlocks($template) {
	
		//Get The active page layout and determine blocks
		$blockArray = $this->getLayoutBlocks($template);
		
		$rstr = '';
        
        if($blockArray == NULL) {
            $rstr .= '<div class="form-row"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>No Content Blocks Found in Layout File at <b>/application/views/display/'.$template.'</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="content-blocks"></div></div>';
        } else {
		
            foreach($blockArray as $key => $value) {
                
                $variable = str_replace(' ','_',$value);
                $name = $value;
                
                $rstr .= '<div class="form-row">';
                $rstr .= '<div class="mce-wrapper">';
                $rstr .= '<label for="'.$variable.'">'.$name.'</label>';
                $rstr .= '<textarea id="'.$variable.'" name="'.$variable.'" class="editor">';
                $rstr .= '<i>Please enter content here</i>';
                $rstr .= '</textarea>';
                $rstr .= '</div>';
                $rstr .= '</div>';	
                //$rstr .= '$pContent_'.$variable.'';	
                $rstr .= '<!-- --------------------- -->';
            }
        
        }
				
		
		return $rstr;
		
	}
	
	// ------------------------------------- //
	// Page | Add | Sniff out content blocks
	// ------------------------------------- //
	public function getLayoutBlocks($template) {
	
		$this->load->helper('file');
						
			$fileContents = read_file('./application/views/display/'.$template);
			//$fileContents = 'This is a test $content["Primary"]; ... this is a test $content["Secondary"];';
			
			$regex = '/html_block\(\"(.*?)\"\);/i';
			
			if(preg_match_all($regex, $fileContents, $match)) {
				$return = $match[1];	
			} else {
				// no matches found
                $return = NULL;
			}
		
		return $return;
	}
    
    
    
    public function duplicate($page_id) {
        $SQL = "INSERT INTO fdcms_pages(page_index,page_locked,page_layout,page_name,page_subtitle,page_url,page_parent,page_content,page_meta_title,page_meta_desc,page_meta_canonical,sort_order) (SELECT page_index,page_locked,page_layout,CONCAT(page_name,' (COPY)'),page_subtitle,CONCAT(page_url,'-copy'),page_parent,page_content,page_meta_title,page_meta_desc,page_meta_canonical,sort_order FROM fdcms_pages WHERE page_id = '".$page_id."')";
        $query = $this->db->query($SQL);
        if($query) {        
            $return["success"] = 'true';
            $return["message"] = "Your page was successfully copied.";
        } else {
            $return["success"] = 'false';
            $return["message"] = "We were unable to copy that page.";
        }
        
        return $return;
    }
    
}