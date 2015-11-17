<?php 

// Section Name: Menus
// Section Link: menus
// Section Order: 2
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menus extends CI_Controller {
	
	
	public $data = array();
    public $path = '/admin/extras/menus';
    public $primary_table = 'fdcms_menus';
    public $primary_model = 'menus_model';
	
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
		$this->load->model('app/menus/'.$this->primary_model);
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
			'name' => 'Create New Menu',
			'link' => '/admin/extras/menus/create',
			'icon' => 'add-icon.png',
            'class' => 'green'
		);
		array_push($toolboxArray, $addtool);
        
        
        $this->data["scripts"] = '
	    <script type="text/javascript" src="/js/system/jquery-ui-1.10.4.min.js"></script>
        <script type="text/javascript" src="/js/app/admin-list-tables.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Menus';
		$this->data["adminContent"] = $model->renderMenuTable();
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
        $this->data["adminTitle"] = 'Create New Menu';
		$this->data["adminContent"] = $model->create();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->createActions('Create Menu');
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
        $this->data["adminTitle"] = 'Edit Menu &raquo; <span class="small">'.$model->getMenuName($id).'</span>';
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
			redirect('/admin/extras/menus/edit/'.$response["id"]);
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
        $SQL = "DELETE FROM fdcms_menus WHERE menu_id = '".$id."'";
        $query = $this->db->query($SQL);
        
        // Delete from URL rewrites
        $SQL2 = "DELETE FROM fdcms_menus_items WHERE item_menu = '".$id."'";
        $query2 = $this->db->query($SQL2);
        
        $data["html"] = 'Deleted Menu sucessfully';
        $this->load->view('system/ajax',$data);
    }

	// ------------------------------------ //
	// Edit Controller
	// ------------------------------------ //
	public function menuitems() {
        $id = $this->uri->segment(5);
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = $model->editToolbox($id);
        
        
        $this->data["scripts"] = '
            <script type="text/javascript" src="/js/system/jquery-ui-1.10.4.min.js"></script>
            <script type="text/javascript" src="/js/app/jquery.nestedSortable.js"></script>
            <script type="text/javascript" src="/js/app/menus.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Menu Structure &raquo; <span class="small">'.$model->getMenuName($id).'</span>';
		$this->data["adminContent"] = $model->menuitems();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->editActions('Save Menu');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function deleteitems() {
        $id = $this->uri->segment(5);
        $SQL = "DELETE FROM fdcms_menus_items WHERE item_menu = '".$id."'";
        $query = $this->db->query($SQL);
    }

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function saveitems() {
        $data = array(
            'item_identity' => $_POST['id'],
            'item_url' => $_POST['url'],
            'item_text' => $_POST['text'],
            'item_page' => $_POST['page'],
            'item_parent_item' => $_POST['parent'],
            'sort_order' => $_POST['order'],
            'item_menu' => $this->uri->segment(5)
        );
        
        $this->db->insert('fdcms_menus_items',$data);        
	}

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function refreshitems() {
        $id = $this->uri->segment(5);
			$this->session->set_flashdata('message','Your changes have been saved');
			$this->session->set_flashdata('messageClass','good');
			redirect('/admin/extras/menus/menuitems/'.$id);
	}
	
}