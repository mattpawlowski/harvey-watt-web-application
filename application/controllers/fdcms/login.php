<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller{
	function __construct(){
		parent::__construct();
        $this->load->helper('url');
	}
	public function index($msg = ''){
		// Load our view to be displayed
		// to the user
        $data["msg"] = $msg;
		$this->load->view('app/admin-login',$data);
	}
    
    public function process() {
		$this->load->model('fdcms/login_model');		
		$result = $this->login_model->validate();
		
		if(!$result) {
			// If user did not validate,
			// show them the login page again
			$msg = '<span class="error">Invalid username and/or password.</span>';
			$this->index($msg);
		} else {
			// If user did validate,
			// send them to admin panel
			redirect('admin','refresh');
		}
	}
}
?>