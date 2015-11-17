<?php 

// Section Name: Categories
// Section Link: categories
// Section Order: 3
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Controller {
	
	public $data = array();
    public $path = '/admin/locations/categories';
    public $primary_table = 'fdcms_locations_categories';
    public $primary_model = 'categories_model';
	
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
		$this->load->model('app/floorplans/'.$this->primary_model);
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
			'name' => 'Add New Category',
			'link' => $this->path.'/create',
			'icon' => 'add-icon.png',
            'class' => 'green'
		);
		array_push($toolboxArray, $addtool);
        
        
        $this->data["scripts"] = '
        <script type="text/javascript" src="/js/app/admin-list-tables.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Floorplan Categories';
		$this->data["adminContent"] = $model->renderTable();
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
        
        
        $this->data["scripts"] = '';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Add New Category';
		$this->data["adminContent"] = $model->create();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->createActions('Create Category');
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
        $this->data["adminTitle"] = 'Edit Location Category &raquo; <span class="small">'.$model->getCategoryname($id).'</span>';
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
			redirect('/admin/floorplans/categories/edit/'.$response["id"]);
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
        
        $SQL = "DELETE FROM fdcms_locations_categories WHERE category_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        
        $SQL2 = "DELETE FROM fdcms_locations_l2c WHERE category_id = '".$id."'";
        $query2 = $this->db->query($SQL2);
        
        $data["html"] = 'Deleted Category sucessfully';
        $this->load->view('system/ajax',$data);
    }
}