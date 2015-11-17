<?php 

// Section Name: Home
// Section Link: home
// Section Order: 1
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public $data = array();
	
	// ------------------------------------- //
	// __Construct
	// ------------------------------------- //
	function __construct() {
		parent::__construct();
		
		$this->load->model('app/core_model');
		
		// Render Our Admin Navigation
		$this->data["adminNav"] = $this->core_model->renderAdminNav();
		
	}

	// ------------------------------------ //
	// Index Controller 
	// ------------------------------------ //
	public function index()
	{ 
        $this->data["scripts"] = '';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Dashboard';
		$this->data["adminContent"] = 'Welcome to FirmDesign Content Management System version 4.0.1 RC';
        $this->data["adminToolbox"] = '';
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}
	
}