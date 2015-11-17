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
		$this->data["adminContent"] = 'Publications - home controller';
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}
	
}