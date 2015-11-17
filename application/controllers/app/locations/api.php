<?php 

// Section Name: API Settings
// Section Link: api
// Section Order: 4
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class API extends CI_Controller {
	
	public $data = array();
    public $path = '/admin/locations/api';
    public $primary_table = 'fdcms_locations_api';
    public $primary_model = 'api_model';
	
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
        
        $this->data["scripts"] = '';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Locations &raquo; <span class="small">API Settings</span>';
		$this->data["adminContent"] = $model->edit();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->editActions('Save Changes');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Index Controller 
	// ------------------------------------ //
    public function save() {	
        $model = $this->primary_model;
        $model = $this->$model;
        	
        $response = array();
        $response = $model->save();
        
		$this->session->set_flashdata('message','Your changes have been saved');
		$this->session->set_flashdata('messageClass','good');
		redirect('/admin/locations/api');
        
		$this->load->view('app/ajax', $data);
	}
}