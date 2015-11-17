<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function validate(){
		
		// grab user input
		$username = $this->security->xss_clean($this->input->post('login-email'));
		$password = md5($this->security->xss_clean($this->input->post('login-password')));
		
		// Prep the query
		$this->db->where('user_email', $username);
		$this->db->where('user_password', $password);
		$this->db->where('user_active', '1');
		
		// Run the query
		$query = $this->db->get('fdcms_users');
		// Let's check if there are any results
		if($query->num_rows == 1)
		{
			// If there is a user, then create session data
			$row = $query->row();
			$data = array(
					'userid' => $row->user_id,
					'fname' => $row->user_fname,
					'lname' => $row->user_lname,
					'username' => $row->user_email,
					'validated' => true
					);
			$this->session->set_userdata($data);
			return true;
		}
		// If the previous process did not validate
		// then return false.
		return false;
	}
}
?>