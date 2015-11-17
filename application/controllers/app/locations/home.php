<?php 

// Section Name: Dashboard
// Section Link: home
// Section Order: 1
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public $data = array();
    public $path = '/admin/users';
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
		$this->load->model('app/locations/'.$model);
        $this->$model->initialize($this->primary_table,$this->path);
		
	}

	// ------------------------------------ //
	// Index Controller 
	// ------------------------------------ //
	public function index()
	{
        // No Pages Dashboard - forward to pages list
        redirect('/admin/locations/all','refresh');
	}
	
}