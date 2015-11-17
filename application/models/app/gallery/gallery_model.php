<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery_model extends CI_Model {
    
    public $primary_table;
    public $path;
	
    
	// ----------------------------------- //
	// Initialize Our Primary Table
	// ----------------------------------- //
	public function initialize($table,$path) {
		$this->primary_table = $table;	
        $this->path = $path;
	}
    
    public function renderTable() {
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
    public function renderTableRows() {
        $rstr = '';
        
        $this->db->from($this->primary_table);
        $query = $this->db->get();
        $count = $query->num_rows();
        $result = $query->result();
        
        if($count > 0) {
        
        foreach($result as $rs) {
            
            $class = ' class="native"';  
            
            $rstr .= '<tr id="'.$rs->gallery_id.'">';
            
            // delete checkbox
            $rstr .= '<td width="20"'.$class.' valign="top">';
            $rstr .= '<input type="checkbox" name="'.$rs->gallery_id.'" value="'.$rs->gallery_id.'" id="box_'.$rs->gallery_id.'" class="del-box" data-id="'.$rs->gallery_id.'" data-post="'.$this->path.'/delete/'.$rs->gallery_id.'">';
            $rstr .= '</td>';
            
            $rstr .= '<td'.$class.'>';
            $rstr .= '<div class="options-hover">';
            $rstr .= '<b>'.$rs->gallery_name.'</b>';
            $rstr .= '<span class="row-options">';
            $rstr .= '<a href="'.$this->path.'/edit/'.$rs->gallery_id.'" class="edit-link">Edit</a>';
            $rstr .= ' | <a href="javascript:void(0)" class="delete-link delete-item" data-id="'.$rs->gallery_id.'" data-post="'.$this->path.'/delete/'.$rs->gallery_id.'">Delete</a>';
            $rstr .= '</span>';
            $rstr .= '</div>';
            $rstr .= '</td>';
            
            $rstr .= '</tr>';  
        }
        
        } else {
            $rstr .= '<tr><td>';
            $rstr .= '<div class="form-row"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>You haven\'t created any Photo Galleries for your website yet. Start by clicking "Create New Gallery" on the right.</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="add-menus"></div></div>';   
            $rstr .= '</td></tr>';
        }
        
        return $rstr;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function create() {
        
        $data = array(
            'gallery_id' => '0',
            'gallery_name' => 'My New Gallery',
            'gallery_slug' => 'my-new-gallery'
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/galleryEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function edit() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT * FROM fdcms_media_galleries WHERE gallery_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        $data = array(
            'gallery_id' => $row->gallery_id,
            'gallery_name' => $row->gallery_name,
            'gallery_slug' => $row->gallery_slug
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/galleryEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function save() {
        $id = $this->uri->segment(5);
        
        $data = array(
            'gallery_id' => $this->input->post('gallery_id'),
            'gallery_name' => $this->input->post('gallery_name'),
            'gallery_slug' => $this->input->post('gallery_slug')
        );
        
        
        if($id == 0) {          
            $this->db->insert('fdcms_media_galleries',$data);
			$return["id"] = $this->db->insert_id();
            $return["message"] = "The New Galery [ ".$data["gallery_name"]." ] was created successfully.";
        } else {
            // Saving an existing Page    
            $this->db->where('gallery_id',$id);
			$this->db->update('fdcms_media_galleries',$data);
            $return["id"] = $id;
            $return["message"] = "Your changes have been saved.";
        }
        
        return $return;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function upload() {
        
        $data["gallery_list"] = $this->renderGalleryList();
        
        $rstr = '';
        $rstr .= $this->load->view('appForms/galleryUploadForm', $data, true);
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function renderGalleryList() {
        $SQL = "SELECT gallery_id,gallery_name FROM fdcms_media_galleries";
        $query = $this->db->query($SQL);
        $result = $query->result();
        
        $rstr = '';
        $rstr .= '<select name="gallery_id" id="gallery_id">';
        foreach($result as $rs) {
            $rstr .= '<option value="'.$rs->gallery_id.'">'.$rs->gallery_name.'</option>';
        }
        $rstr .= '</select>';
        
        return $rstr;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function manage($id) {
        $SQL = "SELECT * FROM fdcms_media_images i JOIN fdcms_media_i2g i2g ON (i.image_id = i2g.image_id) WHERE i2g.gallery_id = '".$id."' ORDER BY image_sort_order ASC";
        $query = $this->db->query($SQL);
        $results = $query->result();
        
        $rstr = '';
        $rstr .= '<form action="/admin/gallery/all/edit/'.$id.'" method="GET" id="pageForm" name="pageForm"></form>';
        $rstr .= '<table class="sortable fdcms-table" width="100%" cellpadding="0" cellspacing="0">'; 
        
        if($query->num_rows() > 0) {
        foreach($results as $rs) {
            $rstr .= '<tr id="'.$rs->image_id.'">';
            
                $rstr .= '<td width="35" class="native">';
                $rstr .= '<div class="image-thumb">';
                $rstr .= '<img src="'.$rs->image_src.'">';
                $rstr .= '<a href="javascript: void(0);" class="upload-new" data-gallery="'.$id.'" data-media="'.$rs->image_id.'" data-src="'.$rs->image_src.'" data-name="'.$rs->image_title.'"></a>';
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
                $rstr .= '<a href="javascript: void(0);" class="form-button image-edit" data-id="'.$rs->image_id.'" data-title="'.$rs->image_title.'" data-link="'.$rs->image_link.'" data-gallery="'.$id.'" style="color: #a9e01c;">Edit</a>';
                $rstr .= '</td>';
                
                // Remove
                $rstr .= '<td class="native">';
                $rstr .= '<form action="/admin/gallery/all/imagesdelete" method="post">';
                $rstr .= '<input type="hidden" name="image_id" id="image_id" value="'.$rs->image_id.'">';
                $rstr .= '<input type="hidden" name="image_src" id="image_src" value="'.$rs->image_src.'">';
                $rstr .= '<input type="hidden" name="image_title" id="image_title" value="'.$rs->image_title.'">';
                $rstr .= '<input type="hidden" name="gallery_id" id="gallery_id" value="'.$rs->gallery_id.'">';
                $rstr .= '<input type="submit" value="Remove" class="form-submit">';
                $rstr .= '</form>';
                $rstr .= '</td>';
            
            $rstr .= '</tr>';    
        }         
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
                    $.post(\'/admin/gallery/all/imagesorder\', {order:newOrder});
                }
            }).disableSelection();
            </script>
        ';
        } else {
            $rstr .= '<div class="form-row"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>This page has no featured images - to add a new image, please use the form above.</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="add-videos"></div></div>';   
        }
        
        return $rstr;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function getGalleryName($id) {
        $SQL = "SELECT * FROM fdcms_media_galleries WHERE gallery_id = '".$id."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        return $row->gallery_name;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    function exit_status($str){
        echo json_encode(array('status'=>$str));
        exit;
    }

    
}

?>