<?php 

// Section Name: Home
// Section Link: home
// Section Order: 1
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public $data = array();
    public $path = '/admin/products';
    public $primary_table = '';
    public $primary_model = '';
	
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
		//$this->load->model('app/pages/'.$this->primary_model);
        //$this->$model->initialize($this->primary_table,$this->path);
		
	}

	// ------------------------------------ //
	// Index Controller 
	// ------------------------------------ //
	public function index()
	{        
		$toolboxArray = array();
        
        $this->data["scripts"] = '';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Products';
		$this->data["adminContent"] = 'Products Dashboard';
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}
	
}