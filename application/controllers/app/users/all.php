<?php 

// Section Name: All users
// Section Link: all
// Section Order: 2
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class All extends CI_Controller {
	
	public $data = array();
    public $path = '/admin/users/all';
    public $primary_table = 'fdcms_users';
    public $primary_model = 'users_model';
	
	// ------------------------------------- //
	// __Construct
	// ------------------------------------- //
	function __construct() {
		parent::__construct();
        $model = $this->primary_model;
		
        // Load our Core Model
		$this->load->model('app/core_model');
		$this->data["adminNav"] = $this->core_model->renderAdminNav();
        
        // Load Primary Model
		$this->load->model('app/users/'.$this->primary_model);
        $this->$model->initialize($this->primary_table,$this->path);
		
	}

	// ------------------------------------ //
	// Index Controller 
	// ------------------------------------ //
	public function index()
	{
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = array();
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Add New User',
			'link' => '/admin/users/all/create',
			'icon' => 'add-icon.png',
            'class' => 'green'
		);
		array_push($toolboxArray, $addtool);
        
        
        $this->data["scripts"] = '
        <script type="text/javascript" src="/js/app/admin-list-tables.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Users';
		$this->data["adminContent"] = $model->renderUserTable();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Create Controller
	// ------------------------------------ //
	public function create() {
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = array();
        
        
        $this->data["scripts"] = '<script type="text/javascript" src="/plugins/tinymce/js/tinymce/tinymce.min.js"></script>';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Add New User';
		$this->data["adminContent"] = $model->create();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->createActions('Create User');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Edit Controller
	// ------------------------------------ //
	public function edit() {
        $id = $this->uri->segment(5);
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = array();
        
        $this->data["scripts"] = '';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Edit User &raquo; <span class="small">'.$model->getUserName($id).'</span>';
		$this->data["adminContent"] = $model->edit();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->editActions('Save Changes');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function save() {	
        $model = $this->primary_model;
        $model = $this->$model;
        	
        $response = array();
        $response = $model->save();
		if($response["id"] > 0) {
			$this->session->set_flashdata('message',$response["message"]);
			$this->session->set_flashdata('messageClass','good');
			redirect('/admin/users/all/edit/'.$response["id"]);
		} else {
			$this->session->set_flashdata('message','Sorry - something went wrong.');
			$this->session->set_flashdata('messageClass','bad');
		}				
		$this->load->view('app/ajax', $data);
	}
    

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function delete() {
        $id = $this->uri->segment(5);
        
        $SQL = "DELETE FROM fdcms_users WHERE user_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        
        $data["html"] = 'Deleted User sucessfully';
        $this->load->view('system/ajax',$data);
    }
    

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function suspend() {
        $id = $this->uri->segment(5);
        
        $SQL = "UPDATE fdcms_users SET user_active = '0' WHERE user_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        
        $data["html"] = 'Suspended User sucessfully';
        $this->load->view('system/ajax',$data);
    }
    

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function activate() {
        $id = $this->uri->segment(5);
        
        $SQL = "UPDATE fdcms_users SET user_active = '1' WHERE user_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        
        $data["html"] = 'Activated User sucessfully';
        $this->load->view('system/ajax',$data);
    }
	
}