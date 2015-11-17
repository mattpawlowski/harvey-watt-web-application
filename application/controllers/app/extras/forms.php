<?php 

// Section Name: Forms
// Section Link: forms
// Section Order: 2
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forms extends CI_Controller {
	
	
	public $data = array();
    public $path = '/admin/extras/forms';
    public $primary_table = 'fdcms_forms';
    public $primary_model = 'forms_model';
	
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
		$this->load->model('app/forms/'.$this->primary_model);
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
			'name' => 'Create New Form',
			'link' => '/admin/extras/forms/create',
			'icon' => 'add-icon.png',
            'class' => 'green'
		);
		array_push($toolboxArray, $addtool);
        
        
        $this->data["scripts"] = '
	    <script type="text/javascript" src="/js/system/jquery-ui-1.10.4.min.js"></script>
        <script type="text/javascript" src="/js/app/admin-list-tables.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Forms';
		$this->data["adminContent"] = $model->renderFormsTable();
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
        $this->data["adminTitle"] = 'Create New Form';
		$this->data["adminContent"] = $model->create();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->createActions('Create Form');
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
        
        
        $this->data["scripts"] = '<script type="text/javascript" src="/plugins/tinymce/js/tinymce/tinymce.min.js"></script>';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Edit Form &raquo; <span class="small">'.$model->getFormName($id).'</span>';
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
        		
        $response = array();
        $response = $model->save();
        var_dump($response);
		if($response["id"] > 0) {
			$this->session->set_flashdata('message',$response["message"]);
			$this->session->set_flashdata('messageClass','good');
			redirect('/admin/extras/forms/edit/'.$response["id"]);
		} else {
			$this->session->set_flashdata('message','Sorry - something went wrong.');
			$this->session->set_flashdata('messageClass','bad');
		}				
		$this->load->view('app/ajax', $data);
	}

	// ------------------------------------ //
	// Individual Delete Function
	// ------------------------------------ //
    public function delete() {		
        $id = $this->uri->segment(5);
        
        // Remove page from page table and url_rewrites
        $SQL = "DELETE FROM fdcms_forms WHERE form_id = '".$id."'";
        $query = $this->db->query($SQL);
        
        // Delete from URL rewrites
        $SQL2 = "DELETE f, f2f FROM fdcms_forms_fields AS f, fdcms_forms_f2f AS f2f WHERE f2f.form_id = '".$id."' AND f2f.field_id=f.field_id";
        $query2 = $this->db->query($SQL2);
        
        $data["html"] = 'Deleted Form sucessfully';
        $this->load->view('system/ajax',$data);
    }

	// ------------------------------------ //
	// Edit Controller
	// ------------------------------------ //
	public function fields() {
        $id = $this->uri->segment(5);
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = $model->editToolbox($id);
        
        
        $this->data["scripts"] = '
            <script type="text/javascript" src="/js/system/jquery-ui-1.10.4.min.js"></script>
            <script type="text/javascript" src="/plugins/tagit/js/tag-it.min.js"></script>
        ';
        $this->data["styles"] = '
            <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
            <link rel="stylesheet" type="text/css" href="/plugins/tagit/css/jquery.tagit.css">
        ';
        $this->data["adminTitle"] = 'Form Fields &raquo; <span class="small">'.$model->getFormName($id).'</span>';
		$this->data["adminContent"] = $model->fields();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->editActions('Save Fields');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function savefields() {
        $id = $this->uri->segment(5);
        $data = array(
            'field_type' => $_POST['type'],
            'field_label' => $_POST['label'],
            'field_options' => $_POST['options'],
            'field_required' => $_POST['required'],
            'sort_order' => $_POST['order']
        );        
        $this->db->insert('fdcms_forms_fields',$data); 
        $thisId = $this->db->insert_id();       
        
        $data = array(
            'field_id' => $thisId,
            'form_id' => $id
        );
        $this->db->insert('fdcms_forms_f2f',$data);
	}

	// ------------------------------------ //
	// Edit Controller
	// ------------------------------------ //
	public function deletefields() {
        $id = $this->uri->segment(5);
        
        $SQL = "DELETE f, f2f FROM fdcms_forms_fields AS f, fdcms_forms_f2f AS f2f WHERE f2f.form_id = '".$id."' AND f2f.field_id=f.field_id";
        $query = $this->db->query($SQL);
        
        $data["html"] = 'Deleted';
        $this->load->view('system/ajax',$data);
    }

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function refreshfields() {
        $id = $this->uri->segment(5);
			$this->session->set_flashdata('message','Your changes have been saved');
			$this->session->set_flashdata('messageClass','good');
			redirect('/admin/extras/forms/fields/'.$id);
	}

	// ------------------------------------ //
	// Edit Controller
	// ------------------------------------ //
	public function response() {
        $id = $this->uri->segment(5);
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = $model->editToolbox($id);
        
        
        $this->data["scripts"] = '<script type="text/javascript" src="/plugins/tinymce/js/tinymce/tinymce.min.js"></script>';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Response Options &raquo; <span class="small">'.$model->getFormName($id).'</span>';
		$this->data["adminContent"] = $model->response();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->editActions('Save Changes');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function saveresponse() {
        $model = $this->primary_model;
        $model = $this->$model;
        		
        $response = array();
        $response = $model->saveresponse();
        var_dump($response);
		if($response["id"] > 0) {
			$this->session->set_flashdata('message',$response["message"]);
			$this->session->set_flashdata('messageClass','good');
			redirect('/admin/extras/forms/response/'.$response["id"]);
		} else {
			$this->session->set_flashdata('message','Sorry - something went wrong.');
			$this->session->set_flashdata('messageClass','bad');
		}				
		$this->load->view('app/ajax', $data);
	}

	// ------------------------------------ //
	// Edit Controller
	// ------------------------------------ //
	public function autoreply() {
        $id = $this->uri->segment(5);
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = $model->editToolbox($id);
        
        
        $this->data["scripts"] = '<script type="text/javascript" src="/plugins/tinymce/js/tinymce/tinymce.min.js"></script>';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Autoreply &raquo; <span class="small">'.$model->getFormName($id).'</span>';
		$this->data["adminContent"] = 'We\'re Sorry - this content is not available yet.';
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->editActions('Save Changes');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	
}