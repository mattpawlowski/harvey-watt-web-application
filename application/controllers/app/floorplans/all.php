<?php 

// Section Name: My Floorplans
// Section Link: all
// Section Order: 1
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class All extends CI_Controller {
	
	public $data = array();
    public $path = '/admin/floorplans/all';
    public $primary_table = 'fdcms_floorplans';
    public $primary_model = 'floorplans_model';
	
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
			'name' => 'Add New Floorplan',
			'link' => $this->path.'/create',
			'icon' => 'add-icon.png',
            'class' => 'green'
		);
		array_push($toolboxArray, $addtool);
        
        
        $this->data["scripts"] = '
            <script type="text/javascript" src="/js/app/admin-list-tables.js"></script>
            <script type="text/javascript" src="/js/system/jquery-ui-1.10.4.min.js"></script>
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
        $this->data["adminTitle"] = 'Add New Floorplan';
		$this->data["adminContent"] = $model->create();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->createActions('Create Floorplan');
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
        $this->data["adminTitle"] = 'Edit Floorplan &raquo; <span class="small">'.$model->getFloorplanName($id).'</span>';
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
        
        $image_src = '';		
		
		if($_FILES["file"]["error"] != 4) {	
			// upload the image (if it's new)
			$uploaddir = './uploads/floorplans/';		 
			$getMime = explode('.', $_FILES['file']['name']);
			$mime = end($getMime);		 
			$randomName = str_replace('.'.$mime,'',$_FILES['file']['name']).'_'.substr_replace(sha1(microtime(true)), '', 12).'.'.$mime;		
			$uploaddir = $uploaddir . basename( $randomName );		
			if(move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir)) {
				//file uploaded success
			} else {
				//file uploaded fail
			}
				$image_src = '/uploads/floorplans/'.$randomName;
		}
        	
        $response = array();
        $response = $model->save($image_src);
		if($response["id"] > 0) {
			$this->session->set_flashdata('message',$response["message"]);
			$this->session->set_flashdata('messageClass','good');
			redirect('/admin/floorplans/all/edit/'.$response["id"]);
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
        
        $SQL = "DELETE FROM fdcms_floorplans WHERE floorplan_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        
        $SQL2 = "DELETE FROM fdcms_floorplans_f2c WHERE floorplan_id = '".$id."'";
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
            <script type="text/javascript" src="/js/app/floorplan-assignments.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Edit Floorplan Assignment &raquo; <span class="small">'.$model->getFloorplanName($id).'</span>';
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
        
        $SQL = "DELETE FROM fdcms_floorplans_f2c WHERE floorplan_id = '".$id."'";
        $query = $this->db->query($SQL);
           
    }
	

	// ------------------------------------ //
	// Edit Controller
	// ------------------------------------ //
	public function saveassignments() {
        $id = $this->uri->segment(5);
        
        $data = array(
            'floorplan_id' => $id,
            'category_id' => $_POST['id']
        );
        
        $this->db->insert('fdcms_floorplans_f2c',$data);
        
    }

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function refreshassignemnts() {
        $id = $this->uri->segment(5);
			$this->session->set_flashdata('message','Your changes have been saved');
			$this->session->set_flashdata('messageClass','good');
			redirect('/admin/floorplans/all/assign/'.$id);
	}
	
	// ------------------------------------- //
	// Get URL on request (AJAX) echo
	// ------------------------------------- //
    function order() {
        $listOrder = $this->input->post('order');
		$listArray = explode(',',$listOrder);		
		$curOrder = 0;		
		foreach($listArray as $key => $value) {			
			$SQL = "UPDATE fdcms_floorplans SET floorplan_sort_order = '".$curOrder."' WHERE floorplan_id = '".$value."'";
			$query = $this->db->query($SQL);			
			$curOrder = $curOrder + 1;
		}
    }
	
}