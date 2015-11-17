<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forms_model extends CI_Model {
    
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
	// Initialize Our Primary Table
	// ----------------------------------- //
    function renderFormsTable() {
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
            
            $rstr .= '<tr id="'.$rs->form_id.'">';
            
            // delete checkbox
            $rstr .= '<td width="20"'.$class.' valign="top">';
            $rstr .= '<input type="checkbox" name="'.$rs->form_id.'" value="'.$rs->form_id.'" id="box_'.$rs->form_id.'" class="del-box" data-id="'.$rs->form_id.'" data-post="'.$this->path.'/delete/'.$rs->form_id.'">';
            $rstr .= '</td>';
            
            $rstr .= '<td'.$class.'>';
            $rstr .= '<div class="options-hover">';
            $rstr .= '<b>'.$rs->form_name.'</b>';
            $rstr .= '<span class="row-options">';
            $rstr .= '<a href="'.$this->path.'/edit/'.$rs->form_id.'" class="edit-link">Edit</a>';
            $rstr .= ' | <a href="javascript:void(0)" class="delete-link delete-item" data-id="'.$rs->form_id.'" data-post="'.$this->path.'/delete/'.$rs->form_id.'">Delete</a>';
            $rstr .= '</span>';
            $rstr .= '</div>';
            $rstr .= '</td>';
            
            $rstr .= '</tr>';  
        }
        
        } else {
            $rstr .= '<tr><td>';
            $rstr .= '<div class="form-row"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>You haven\'t created any Menus for your website yet. Start by clicking "Create New Menu" on the right.</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="add-menus"></div></div>';   
            $rstr .= '</td></tr>';
        }
        
        return $rstr;
    }
    
    

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function create() {
        
        $data = array(
            'form_id' => '0',
            'form_name' => 'My New Form',
            'form_to' => '',
            'form_from' => 'noreply@'.$_SERVER['HTTP_HOST'],
            'form_cc' => '',
            'form_bcc' => '',
            'form_subject' => ''
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/formEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function edit() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT * FROM fdcms_forms WHERE form_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        $data = array(
            'form_id' => $row->form_id,
            'form_name' => $row->form_name,
            'form_to' => $row->form_to,
            'form_from' => $row->form_from,
            'form_cc' => $row->form_cc,
            'form_bcc' => $row->form_bcc,
            'form_subject' => $row->form_subject
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/formEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function save() {
        $id = $this->uri->segment(5);
        $data = array(
            'form_name' => $this->input->post('form_name'),
            'form_to' => $this->input->post('form_to'),
            'form_from' => $this->input->post('form_from'),
            'form_cc' => $this->input->post('form_cc'),
            'form_bcc' => $this->input->post('form_bcc'),
            'form_subject' => $this->input->post('form_subject')
        );
        
        
        if($id == 0) {
            // Creating a new menu    
            $this->db->insert('fdcms_forms',$data);
			$return["id"] = $this->db->insert_id();
            $return["message"] = "Your new Form [ ".$data["form_name"]." ] was created successfully.";
        } else {
            // Update an existing menu
            $this->db->where('form_id',$id);
			$this->db->update('fdcms_forms',$data);           
            $return["id"] = $id;
            $return["message"] = "Your changes have been saved.";
        }
        
        return $return;
        
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function fields() {
        $id = $this->uri->segment(5);
        $data = array(
            'form_id' => $id
        );
        $rstr = '';
        
        $rstr .= '<input type="hidden" name="menu_id" id="menu_id" value="'.$id.'">';
        
        $rstr .= '<div class="sortable-list" style="float: left;">';      
        $rstr .= '<h3>Add New Field</h3>';
        $rstr .= '<div class="sortable-menu">';
        $rstr .= $this->load->view('appForms/formFieldsForm',$data,true);
        $rstr .= '</div>';
        $rstr .= '<div class="menu-pages-add">';
        $rstr .= '<input type="button" value="Add Field" class="form-button-small trigger-add-field">';
        $rstr .= '</div>';
        $rstr .= '</div>';
        
        $rstr .= '<div class="sortable-list">';        
            $rstr .= '<h3>My Form</h3>';
            $rstr .= '<div class="sortable-menu">';
            $rstr .= '<ul id="form-items" class="sortable">';
            $rstr .= $this->renderCurrentFields($id);
            $rstr .= '</ul>';
            $rstr .= '</div>';
        $rstr .= '</div>';
        
        $rstr .= '<div class="clear"></div>';
        
        return $rstr;   
    }
    
    public function renderCurrentFields($id) {
        $SQL = "SELECT * FROM fdcms_forms_fields f JOIN fdcms_forms_f2f f2f ON (f2f.field_id = f.field_id) WHERE f2f.form_id = '".$id."' ORDER BY sort_order ASC";
        $query = $this->db->query($SQL);
        $result = $query->result();
        
        $rstr = '';
        
        foreach($result as $rs) {
            $rstr .= '<li data-type="'.$rs->field_type.'" data-label="'.$rs->field_label.'" data-options="'.$rs->field_options.'" data-required="'.$rs->field_required.'"><div><span class="field-actions"><a href="javascript: void(0);" class="remove-item"><img src="/images/app/icons/remove-circle.png"></a></span><span class="field-required-'.$rs->field_required.'"></span><span class="field-label">'.$rs->field_label.'</span><br><span class="field-type small">'.$rs->field_type.'</span><span class="clear"></span></div></li>';
        }
        
        return $rstr;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function getFormName($id) {
        $SQL = "SELECT * FROM fdcms_forms WHERE form_id = '".$id."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        return $row->form_name;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function response() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT form_id,form_response_action,form_response_message,form_response_forward FROM fdcms_forms WHERE form_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        $data = array(
            'form_id' => $row->form_id,
            'form_response_action' => $row->form_response_action,
            'form_response_message' => $row->form_response_message,
            'form_response_forward' => $row->form_response_forward
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/formResponseForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function saveresponse() {
        $id = $this->uri->segment(5);
        $data = array(
            'form_response_action' => $this->input->post('form_response_action'),
            'form_response_message' => $this->input->post('form_response_message'),
            'form_response_forward' => $this->input->post('form_response_forward')
        );
        
        // Update an existing menu
        $this->db->where('form_id',$id);
        $this->db->update('fdcms_forms',$data);           
        $return["id"] = $id;
        $return["message"] = "Your changes have been saved.";
        
        return $return;
        
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
			'name' => 'Form Settings',
			'link' => '/admin/extras/forms/edit/'.$id.'',
			'icon' => 'send-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Fields',
			'link' => '/admin/extras/forms/fields/'.$id.'',
			'icon' => 'edit-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Response Options',
			'link' => '/admin/extras/forms/response/'.$id.'',
			'icon' => 'widget-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Autoreply',
			'link' => '/admin/extras/forms/autoreply/'.$id.'',
			'icon' => 'reply-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
        
        return $toolboxArray;
    }
    
}
?>