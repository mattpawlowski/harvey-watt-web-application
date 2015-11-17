<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Locations_model extends CI_Model {
    
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
            
            $rstr .= '<tr id="'.$rs->location_id.'">';
            
            // delete checkbox
            $rstr .= '<td width="20"'.$class.' valign="top">';
            $rstr .= '<input type="checkbox" name="'.$rs->location_id.'" value="'.$rs->location_id.'" id="box_'.$rs->location_id.'" class="del-box" data-id="'.$rs->location_id.'" data-post="'.$this->path.'/delete/'.$rs->location_id.'">';
            $rstr .= '</td>';
            
            $rstr .= '<td'.$class.'>';
            $rstr .= '<div class="options-hover">';
            $rstr .= '<b>'.$rs->location_name.'</b> - <i>'.$rs->location_street.' '.$rs->location_city.', '.$rs->location_state.' '.$rs->location_zip.'</i>';
            $rstr .= '<span class="row-options">';
            $rstr .= '<a href="'.$this->path.'/edit/'.$rs->location_id.'" class="edit-link">Edit</a>';
            $rstr .= ' | <a href="javascript:void(0)" class="delete-link delete-item" data-id="'.$rs->location_id.'" data-post="'.$this->path.'/delete/'.$rs->location_id.'">Delete</a>';
            $rstr .= '</span>';
            $rstr .= '</div>';
            $rstr .= '</td>';
            
            $rstr .= '</tr>';  
        }
        
        } else {
            $rstr .= '<tr><td>';
            $rstr .= '<div class="form-row"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>You haven\'t created any Locations to your website yet. Start by clicking "Create New Location" on the right.</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="add-menus"></div></div>';   
            $rstr .= '</td></tr>';
        }
        
        return $rstr;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function create() {
        
        $data = array(
            'location_id' => '0',
            'location_name' => '',
            'location_summary' => '',
            'location_street' => '',
            'location_city' => '',
            'location_state' => '',
            'location_zip' => '',
            'sort_order' => '0'
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/locationEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function edit() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT * FROM fdcms_locations WHERE location_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        $data = array(
            'location_id' => $row->location_id,
            'location_name' => $row->location_name,
            'location_summary' => $row->location_summary,
            'location_street' => $row->location_street,
            'location_city' => $row->location_city,
            'location_state' => $row->location_state,
            'location_zip' => $row->location_zip,
            'sort_order' => $row->sort_order
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/locationEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function save() {
        $id = $this->uri->segment(5);
        
        $data = array(
            'location_id' => $this->input->post('location_id'),
            'location_name' => $this->input->post('location_name'),
            'location_summary' => $this->input->post('location_summary'),
            'location_street' => $this->input->post('location_street'),
            'location_city' => $this->input->post('location_city'),
            'location_state' => $this->input->post('location_state'),
            'location_zip' => $this->input->post('location_zip'),
            'sort_order' => $this->input->post('sort_order')
        );
        
        
        if($id == 0) {          
            $this->db->insert('fdcms_locations',$data);
			$return["id"] = $this->db->insert_id();
            $return["message"] = "The New Location [ ".$data["location_name"]." ] was created successfully.";
        } else {
            // Saving an existing Page    
            $this->db->where('location_id',$id);
			$this->db->update('fdcms_locations',$data); 
            $return["id"] = $id;
            $return["message"] = "Your changes have been saved.";
        }
        
        return $return;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function getLocationName($id) {
        $SQL = "SELECT * FROM fdcms_locations WHERE location_id = '".$id."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        return $row->location_name;
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function assign() {
        $id = $this->uri->segment(5);
        $rstr = '';
        
        $rstr .= '<input type="hidden" name="location_id" id="location_id" value="'.$id.'">';
        
        // Get our Assignments
        $SQL = "SELECT * FROM fdcms_locations l JOIN fdcms_locations_l2c l2c ON( l.location_id = l2c.location_id ) JOIN fdcms_locations_categories lc ON (l2c.category_id = lc.category_id)  WHERE l2c.location_id = '".$id."'";
        $query = $this->db->query($SQL);
        $results = $query->result();        
        $contained = array();  
            $rstr .= '<div class="sortable-list" style="float: right;">';        
            $rstr .= '<h3>Assigned To</h3>';
            $rstr .= '<div class="sortable-menu">';
            $rstr .= '<ul id="sortable1" class="connectedSortable box-ul">';    
            foreach($results as $rs) {
                $contained[] = $rs->category_id;
                $rstr .= '<li class="ui-state-default box-li" data-id="'.$rs->category_id.'">'.$rs->category_name.'</li>';              
            }
            $rstr .= '</ul>';
            $rstr .= '</div>';
            $rstr .= '</div>';
        
        // Get our list of unassigned
        $SQL2 = "SELECT * FROM fdcms_locations_categories";
        if(!empty($contained)) { $SQL2 .= " WHERE category_id NOT IN (".implode(",",$contained).")"; }
        $query2 = $this->db->query($SQL2);
        $results2 = $query2->result();
        $unassigned = array();
            $rstr .= '<div class="sortable-list" style="float: left;">';        
            $rstr .= '<h3>Available Categories</h3>';
            $rstr .= '<div class="sortable-menu">';
            $rstr .= '<ul id="sortable2" class="connectedSortable box-ul">';   
            foreach($results2 as $rs) {
                $unassigned[] = $rs->category_id;
                $rstr .= '<li class="ui-state-default box-li" data-id="'.$rs->category_id.'">'.$rs->category_name.'</li>';              
            }
            $rstr .= '</ul>';
            $rstr .= '</div>';
            $rstr .= '</div>';
            
            $rstr .= '<div class="clear"></div>';
            
            $rstr .= '
            <script>
            $(function() {
                $( "#sortable1, #sortable2" ).sortable({
                  connectWith: ".connectedSortable"
                }).disableSelection();
            });
            </script>
            ';
        
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
			'name' => 'Location Info',
			'link' => '/admin/locations/all/edit/'.$id.'',
			'icon' => 'edit-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Assignments',
			'link' => '/admin/locations/all/assign/'.$id.'',
			'icon' => 'widget-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
        
        return $toolboxArray;
    }
    
    
    
    
}

?>