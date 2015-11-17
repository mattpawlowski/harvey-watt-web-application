<?php 

// Section Name: Blog
// Section Link: blog
// Section Order: 2
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Blog extends CI_Controller {
	
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
		$this->data["adminContent"] = 'Publications - blog controller';
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}
	
}