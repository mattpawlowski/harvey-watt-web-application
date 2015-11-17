<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maps_model extends CI_Model {
    
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
            
            $rstr .= '<tr id="'.$rs->map_id.'">';
            
            // delete checkbox
            $rstr .= '<td width="20"'.$class.' valign="top">';
            $rstr .= '<input type="checkbox" name="'.$rs->map_id.'" value="'.$rs->map_id.'" id="box_'.$rs->map_id.'" class="del-box" data-id="'.$rs->map_id.'" data-post="'.$this->path.'/delete/'.$rs->map_id.'">';
            $rstr .= '</td>';
            
            $rstr .= '<td'.$class.'>';
            $rstr .= '<div class="options-hover">';
            $rstr .= '<b>'.$rs->map_name.'</b>';
            $rstr .= '<span class="row-options">';
            $rstr .= '<a href="'.$this->path.'/edit/'.$rs->map_id.'" class="edit-link">Edit</a>';
            $rstr .= ' | <a href="javascript:void(0)" class="delete-link delete-item" data-id="'.$rs->map_id.'" data-post="'.$this->path.'/delete/'.$rs->map_id.'">Delete</a>';
            $rstr .= '</span>';
            $rstr .= '</div>';
            $rstr .= '</td>';
            
            $rstr .= '</tr>';  
        }
        
        } else {
            $rstr .= '<tr><td>';
            $rstr .= '<div class="form-row"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>You haven\'t created any Maps for your website yet. Start by clicking "Create New Map" on the right.</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="add-menus"></div></div>';   
            $rstr .= '</td></tr>';
        }
        
        return $rstr;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function create() {
        
        $data = array(
            'map_id' => '0',
            'map_name' => 'My New Map',
            'map_type' => '',
            'map_slug' => 'my-new-map'
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/locationMapsForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function edit() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT * FROM fdcms_locations_maps WHERE map_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        $data = array(
            'map_id' => $row->map_id,
            'map_name' => $row->map_name,
            'map_type' => $row->map_type,
            'map_slug' => $row->map_slug
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/locationMapsForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function save() {
        $id = $this->uri->segment(5);
        
        $data = array(
            'map_id' => $this->input->post('map_id'),
            'map_name' => $this->input->post('map_name'),
            'map_type' => $this->input->post('map_type'),
            'map_slug' => $this->input->post('map_slug')
        );
        
        
        if($id == 0) {          
            $this->db->insert('fdcms_locations_maps',$data);
			$return["id"] = $this->db->insert_id();
            $return["message"] = "The New Mapy [ ".$data["map_name"]." ] was created successfully.";
        } else {
            // Saving an existing Page    
            $this->db->where('map_id',$id);
			$this->db->update('fdcms_locations_maps',$data);
            $return["id"] = $id;
            $return["message"] = "Your changes have been saved.";
        }
        
        return $return;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function styles() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT * FROM fdcms_locations_maps WHERE map_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        $data = array(
            'map_id' => $row->map_id,
            'map_styles' => $row->map_styles
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/locationMapStylesForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function savestyles() {
        $id = $this->uri->segment(5);
        
        $data = array(
            'map_styles' => $this->input->post('map_styles')
        );
      
        // Saving an existing Page    
        $this->db->where('map_id',$id);
        $this->db->update('fdcms_locations_maps',$data);
        $return["id"] = $id;
        $return["message"] = "Your changes have been saved.";
        
        return $return;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function settings() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT * FROM fdcms_locations_maps WHERE map_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        $data = array(
            'map_id' => $row->map_id,
            'map_zoom' => $row->map_zoom,
            'map_center' => $row->map_center,
            'map_styles' => $row->map_styles,
            'map_type' => $row->map_type,
        );
        
        $SQL2 = "SELECT * FROM fdcms_locations_api WHERE setting_key = 'api_key' LIMIT 1";
        $query2 = $this->db->query($SQL2);
        $row2 = $query2->row();
        
            $data["api_key"] = $row2->setting_value; 
        if($data["map_type"] == 'cat') {
            $data["mapJS"] = $this->load->view('mapsAPI/dynamic-map-cat', $data, true);
        } else if($data["map_type"] == 'loc') {
            $data["mapJS"] = $this->load->view('mapsAPI/dynamic-map-loc', $data, true);
        }
        
        $SQL3 = "SELECT * FROM fdcms_locations_l2m WHERE map_id = '".$id."'";
        $query3 = $this->db->query($SQL3);
        $results3 = $query3->result();
        
        $locationArray = array();
        foreach($results3 as $location) {
            $locationArray[] = $location->location_id;
        }
        
        $SQL4 = "SELECT * FROM fdcms_locations_c2m WHERE map_id = '".$id."'";
        $query4 = $this->db->query($SQL4);
        $results4 = $query4->result();
        
        $categoryArray = array();
        foreach($results4 as $category) {
            $categoryArray[] = $category->category_id;
        }
        
        $data["map_locations"] = implode(",", $locationArray);
        $data["map_categories"] = implode(",", $categoryArray);
        
        if($data["map_type"] == 'cat') {
            $data["categoryOptions"] = $this->renderCategoryOptions($id,$categoryArray);
            $data["locationOptions"] = '<!-- NO LOCATION OPTIONS -->';
            $data["notes"] = '* Category Markers can be toggled on Front End View';
        } else if($data["map_type"] == 'loc') {
            $data["categoryOptions"] = '<!-- NO CATEGORY OPTIONS -->';
            $data["locationOptions"] = $this->renderLocationOptions($id,$locationArray);
            $data["notes"] = '';
        } else {
            $data["categoryOptions"] = '<!-- NO CATEGORY OPTIONS -->';
            $data["locationOptions"] = '<!-- NO LOCATION OPTIONS -->';
            $data["notes"] = '';
        }
        
        $rstr = '';
        $rstr .= $this->load->view('appForms/locationMapSettingsForm', $data, true);
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function savesettings() {
        $id = $this->uri->segment(5);
        
        $data = array(
            'map_zoom' => $this->input->post('map_zoom'),
            'map_center' => $this->input->post('map_center')
        );
        
        // Saving an existing Page    
        $this->db->where('map_id',$id);
        $this->db->update('fdcms_locations_maps',$data);
        $return["id"] = $id;
        $return["message"] = "Your changes have been saved.";
        
        // Save our location assignments (first delete them all for this map)
        $this->db->where('map_id',$id);
        $this->db->delete('fdcms_locations_l2m');
        $locations = explode(",",$this->input->post('map_locations'));
        foreach($locations as $location) {
            $data = array( 'location_id' => $location, 'map_id' => $id );
            $this->db->insert('fdcms_locations_l2m',$data);
        }
        
        // Save our category assignments (first delete them all for this map)
        $this->db->where('map_id',$id);
        $this->db->delete('fdcms_locations_c2m');
        $categories = explode(",",$this->input->post('map_categories'));
        foreach($categories as $cateogory) {
            $data = array( 'category_id' => $cateogory, 'map_id' => $id );
            $this->db->insert('fdcms_locations_c2m',$data);
        }
        
        return $return;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function renderCategoryOptions($id,$categoryArray) {
        $SQL = "SELECT * FROM fdcms_locations_categories";
        $query = $this->db->query($SQL);
        $result = $query->result();
        
        $rstr = '';
        
        foreach($result as $rs) {
            if(in_array($rs->category_id,$categoryArray)) { $checked = ' CHECKED'; } else { $checked = ''; }
            
            $rstr .= '<div class="menu-page-option">';
            $rstr .= '<input type="checkbox" id="location-'.$rs->category_id.'" class="marker-add-box" data-id="'.$rs->category_id.'" data-text="'.$rs->category_name.'"'.$checked.'>';
            $rstr .= $rs->category_name;
            $rstr .= '</div>';      
        }
        
        return $rstr;
        
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function renderLocationOptions($id,$locationArray) {
        $SQL = "SELECT * FROM fdcms_locations";
        $query = $this->db->query($SQL);
        $result = $query->result();
        
        $rstr = '';
        
        foreach($result as $rs) {
            if(in_array($rs->location_id,$locationArray)) { $checked = ' CHECKED'; } else { $checked = ''; }
            
            $rstr .= '<div class="menu-page-option">';
            $rstr .= '<input type="checkbox" id="location-'.$rs->location_id.'" class="marker-add-box" data-id="'.$rs->location_id.'" data-text="'.$rs->location_name.'" data-address="'.$rs->location_street.' '.$rs->location_city.' '.$rs->location_state.' '.$rs->location_zip.'"'.$checked.'>';
            $rstr .= $rs->location_name;
            $rstr .= '</div>';      
        }
        
        return $rstr;
        
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function getCategoryName($id) {
        $SQL = "SELECT * FROM fdcms_locations_maps WHERE map_id = '".$id."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        return $row->map_name;
    }
    
    
    
    
}

?>