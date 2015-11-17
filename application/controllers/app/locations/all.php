<?php 

// Section Name: My Locations
// Section Link: all
// Section Order: 1
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class All extends CI_Controller {
	
	public $data = array();
    public $path = '/admin/locations/all';
    public $primary_table = 'fdcms_locations';
    public $primary_model = 'locations_model';
	
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
		$this->load->model('app/locations/'.$this->primary_model);
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
			'name' => 'Add New Location',
			'link' => $this->path.'/create',
			'icon' => 'add-icon.png',
            'class' => 'green'
		);
		array_push($toolboxArray, $addtool);
        
        
        $this->data["scripts"] = '
        <script type="text/javascript" src="/js/app/admin-list-tables.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Floorplans';
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
        $this->data["adminTitle"] = 'Add New Location';
		$this->data["adminContent"] = $model->create();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->createActions('Create Location');
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
        
		$toolboxArray = $model->editToolbox($id);
        
        $this->data["scripts"] = '';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Edit Location &raquo; <span class="small">'.$model->getLocationName($id).'</span>';
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
			redirect('/admin/locations/all/edit/'.$response["id"]);
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
        
        $SQL = "DELETE FROM fdcms_locations WHERE location_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        
        $SQL2 = "DELETE FROM fdcms_locations_l2c WHERE location_id = '".$id."'";
        $query2 = $this->db->query($SQL2);
        
        $data["html"] = 'Deleted Location sucessfully';
        $this->load->view('system/ajax',$data);
    }
	

	// ------------------------------------ //
	// Edit Controller
	// ------------------------------------ //
	public function assign() {
        $id = $this->uri->segment(5);
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = $model->editToolbox($id);
        
        $this->data["scripts"] = '
            <script type="text/javascript" src="/js/system/jquery-ui-1.10.4.min.js"></script>
            <script type="text/javascript" src="/js/app/location-assignments.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Edit Location Assignment &raquo; <span class="small">'.$model->getLocationName($id).'</span>';
		$this->data["adminContent"] = $model->assign();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->editActions('Save Changes');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}
	

	// ------------------------------------ //
	// Edit Controller
	// ------------------------------------ //
	public function deleteassignments() {
        $id = $this->uri->segment(5);
        
        $SQL = "DELETE FROM fdcms_locations_l2c WHERE location_id = '".$id."'";
        $query = $this->db->query($SQL);
           
    }
	

	// ------------------------------------ //
	// Edit Controller
	// ------------------------------------ //
	public function saveassignments() {
        $id = $this->uri->segment(5);
        
        $data = array(
            'location_id' => $id,
            'category_id' => $_POST['id']
        );
        
        $this->db->insert('fdcms_locations_l2c',$data);
        
    }

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function refreshassignemnts() {
        $id = $this->uri->segment(5);
			$this->session->set_flashdata('message','Your changes have been saved');
			$this->session->set_flashdata('messageClass','good');
			redirect('/admin/locations/all/assign/'.$id);
	}
	
}