<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {
    
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
    function renderUserTable() {
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
            
            if($rs->user_active == 1) {
                $status = '<span class="active">Active</span>';   
            } else {
                $status = '<span class="suspended">Suspended</span>';   
            }
            
            $class = ' class="native"'; 
            
            $rstr .= '<tr id="'.$rs->user_id.'">';
            
            // delete checkbox
            $rstr .= '<td width="20"'.$class.' valign="top">';
            $rstr .= '<input type="checkbox" name="'.$rs->user_id.'" value="'.$rs->user_id.'" id="box_'.$rs->user_id.'" class="del-box" data-id="'.$rs->user_id.'" data-post="'.$this->path.'/delete/'.$rs->user_id.'">';
            $rstr .= '</td>';
            
            $rstr .= '<td'.$class.'>';
            $rstr .= '<div class="options-hover">';
            $rstr .= '<b>'.$rs->user_fname.' '.$rs->user_lname.'</b>';
            $rstr .= $status;
            $rstr .= '<span class="row-options">';
            $rstr .= '<a href="/admin/users/all/edit/'.$rs->user_id.'" class="edit-link">Edit</a>';
            if($rs->user_active == 1) {
            $rstr .= ' | <a href="javascript:void(0)" class="suspend-link suspend-item" data-id="'.$rs->user_id.'" data-post="'.$this->path.'/suspend/'.$rs->user_id.'">Suspend Account</a>';
            } else {
            $rstr .= ' | <a href="javascript:void(0)" class="activate-link activate-item" data-id="'.$rs->user_id.'" data-post="'.$this->path.'/activate/'.$rs->user_id.'">Activate Account</a>';
            }
            $rstr .= ' | <a href="javascript:void(0)" class="delete-link delete-item" data-id="'.$rs->user_id.'" data-post="'.$this->path.'/delete/'.$rs->user_id.'">Delete</a>';
            $rstr .= '</span>';
            $rstr .= '</div>';
            $rstr .= '</td>';
            
            $rstr .= '</tr>';  
        }
        
        } else {
            $rstr .= '<tr><td>';
            $rstr .= '<div class="form-row"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>You haven\'t created any Users for your website yet. Start by clicking "Create New User" on the right.</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="add-user"></div></div>';   
            $rstr .= '</td></tr>';
        }
        
        return $rstr;
    }
    

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function create() {
        
        $data = array(
            'user_id' => '0',
            'user_active' => '1',
            'user_fname' => '',
            'user_lname' => '',
            'user_email' => '',
            'user_password' => ''
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/usersCreateForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }
    

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function edit() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT * FROM fdcms_users WHERE user_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        $data = array(
            'user_id' => $row->user_id,
            'user_active' => $row->user_active,
            'user_fname' => $row->user_fname,
            'user_lname' => $row->user_lname,
            'user_email' => $row->user_email,
            'user_password' => $row->user_password
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/usersEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function save() {
        $id = $this->uri->segment(5);
        
        $data = array(
            'user_id' => $this->input->post('user_id'),
            'user_fname' => $this->input->post('user_fname'),
            'user_lname' => $this->input->post('user_lname'),
            'user_email' => $this->input->post('user_email')
        );
        
        if($this->input->post('user_password') != '') {
            $data["user_password"] = md5($this->input->post('user_password'));
        } else {
            $data["user_password"] = $this->input->post('user_password_hash');
        }
        
        if($id == 0) {  
        
            $this->db->insert('fdcms_users',$data);
			$return["id"] = $this->db->insert_id();
            $return["message"] = "The New User [ ".$data["user_fname"]." ".$data["user_lname"]." ] was created successfully.";
        } else {
            // Saving an existing Page    
            $this->db->where('user_id',$id);
			$this->db->update('fdcms_users',$data);
            $return["id"] = $id;
            $return["message"] = "Your changes have been saved.";
        }
        
        return $return;
    }
    

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function getUserName($id) {
        $SQL = "SELECT user_id, user_fname, user_lname FROM fdcms_users WHERE user_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        return $row->user_fname.' '.$row->user_lname;   
    }

    
}
?>