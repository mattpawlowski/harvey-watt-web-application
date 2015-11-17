<?php 

// Section Name: Events
// Section Link: events
// Section Order: 4
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Events extends CI_Controller {
	
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
		$this->data["adminContent"] = 'Publications - events controller';
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}
	
}