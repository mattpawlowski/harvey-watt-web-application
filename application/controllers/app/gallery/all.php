<?php 

// Section Name: My Galleries
// Section Link: all
// Section Order: 2
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class All extends CI_Controller {
	
	public $data = array();
    public $path = '/admin/gallery/all';
    public $primary_table = 'fdcms_media_galleries';
    public $primary_model = 'gallery_model';
	
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
		$this->load->model('app/gallery/'.$this->primary_model);
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
			'name' => 'Add New Gallery',
			'link' => '/admin/gallery/all/create',
			'icon' => 'add-icon.png',
            'class' => 'green'
		);
		array_push($toolboxArray, $addtool);
        
        
        $this->data["scripts"] = '
        <script type="text/javascript" src="/js/app/admin-list-tables.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'My Galleries';
		$this->data["adminContent"] = $model->renderTable();
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
        $this->data["adminTitle"] = 'Add New Gallery';
		$this->data["adminContent"] = $model->create();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->createActions('Create Gallery');
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
        
		$toolboxArray = array();
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Gallery Info',
			'link' => '/admin/gallery/all/edit/'.$id.'',
			'icon' => 'edit-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Manage Media',
			'link' => '/admin/gallery/all/manage/'.$id.'',
			'icon' => 'widget-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
        
        $this->data["scripts"] = '';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Edit Gallery &raquo; <span class="small">'.$model->getGalleryName($id).'</span>';
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
		if($response["id"] > 0) {
			$this->session->set_flashdata('message',$response["message"]);
			$this->session->set_flashdata('messageClass','good');
			redirect('/admin/gallery/all/edit/'.$response["id"]);
		} else {
			$this->session->set_flashdata('message','Sorry - something went wrong.');
			$this->session->set_flashdata('messageClass','bad');
		}				
		$this->load->view('app/ajax', $data);
	}

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function delete() {
        $id = $this->uri->segment(5);
        
        $SQL = "DELETE FROM fdcms_media_galleries WHERE gallery_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        
        // Delete all the featured files from server
        $SQL2 = "SELECT image_src FROM fdcms_media_images i JOIN fdcms_media_i2g i2g ON (i.image_id = i2g.image_id) WHERE i2g.gallery_id = '".$id."'";
        $query2 = $this->db->query($SQL2);
        $result2 = $query2->result();
        foreach($result2 as $rs) {
            unlink('.'.$rs->image_src); // Delete the physical file
        }
        
        $SQL3 = "DELETE FROM fdcms_media_i2g WHERE gallery_id = '".$id."'";
        $query3 = $this->db->query($SQL3);
        
        $data["html"] = 'Deleted Category sucessfully';
        $this->load->view('system/ajax',$data);
    }

	// ------------------------------------ //
	// Create Controller
	// ------------------------------------ //
	public function upload() {
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = array();
        
        
        $this->data["scripts"] = '
            <script src="/plugins/html5-file-upload/assets/js/jquery.filedrop.js"></script>
            <script src="/plugins/html5-file-upload/assets/js/script.js"></script>
        ';
        $this->data["styles"] = '
            <link rel="stylesheet" href="/plugins/html5-file-upload/assets/css/styles.css" />
        ';
        $this->data["adminTitle"] = 'Upload Images';
		$this->data["adminContent"] = $model->upload();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->createActions('Done');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Create Controller
	// ------------------------------------ //
	public function uploadimage() {    
        $gallery_id = $this->uri->segment(5);    
        $demo_mode = false;
        $upload_dir = './uploads/gallery/';
        $allowed_ext = array('jpg','jpeg','png','gif');
        
        if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
            $this->core_model->exit_status('Error! Wrong HTTP method!');
        }        
        
        if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 ){
            $pic = $_FILES['pic'];
            if(!in_array($this->core_model->get_extension($pic['name']),$allowed_ext)){
                $this->core_model->exit_status('Only '.implode(',',$allowed_ext).' files are allowed!');
            }	
            
            $randomHash = 'gallery_'.substr_replace(sha1(microtime(true)), '', 12).'_';
            
		
            // Get Image size
            $image_info = getimagesize($pic['tmp_name']);
            $width = $image_info[0];
            $height = $image_info[1];
            
            if(move_uploaded_file($pic['tmp_name'], $upload_dir.$randomHash.$pic['name'])){
                $data = array(
                    'image_title' => $pic['name'],
                    'image_link' => '',
                    'image_src' => '/uploads/gallery/'.$randomHash.$pic['name'],
                    'image_type' => $pic['type'],
                    'image_width' => $width,
                    'image_height' => $height,
                    'image_size' => $pic['size'],
                    'image_upload_date' => date('d-M-Y')
                );			
                $this->db->insert('fdcms_media_images', $data);
                $thisId = $this->db->insert_id();
                
                $data = array(
                    'image_id' => $thisId,
                    'gallery_id' => $gallery_id
                );                
                $this->db->insert('fdcms_media_i2g', $data);
                
                $this->core_model->exit_status('File was uploaded successfuly!');
                
            }
            
        }        
        $this->core_model->exit_status('Something went wrong with your upload!');
        
    }

	// ------------------------------------ //
	// Edit Controller
	// ------------------------------------ //
	public function manage() {
        $id = $this->uri->segment(5);
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = array();
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Gallery Info',
			'link' => '/admin/gallery/all/edit/'.$id.'',
			'icon' => 'edit-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Manage Media',
			'link' => '/admin/gallery/all/manage/'.$id.'',
			'icon' => 'widget-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
        
        $this->data["scripts"] = '
            <script type="text/javascript" src="/js/system/jquery-ui-1.10.4.min.js"></script>
            <script type="text/javascript" src="/js/app/admin-list-tables.js"></script>
            <script type="text/javascript" src="/js/app/galleryImage.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Manage Gallery &raquo; <span class="small">'.$model->getGalleryName($id).'</span>';
		$this->data["adminContent"] = $model->manage($id);
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->editActions('Done');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function imagesupdate() {		
    
        $image_id = $this->input->post('image_id');
        $image_src = $this->input->post('image_src');
        $gallery_id = $this->input->post('gallery_id');
        $image_title = $this->input->post('image_title');
    
        // We're putting all our files in a directory called images.
		$uploaddir = './uploads/gallery/';		 
		$getMime = explode('.', $_FILES['file']['name']);
		$mime = end($getMime);		 
		$randomName = str_replace('.'.$mime,'',$_FILES['file']['name']).'_'.substr_replace(sha1(microtime(true)), '', 12).'.'.$mime;		
		$uploaddir = $uploaddir . basename( $randomName );		
		if(move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir)) {
			//file uploaded success
		} else {
			//file uploaded fail
		}
		
		// Insert it into the database
		// add a page
		$data = array(
			'image_src' => '/uploads/gallery/'.$randomName,
			'image_type' => $_FILES['file']['type'],
			'image_size' => $_FILES['file']['size'],
		);			
            $this->db->where('image_id', $image_id);
			$this->db->update('fdcms_media_images', $data);
            
        unlink('.'.$image_src);
            
        $this->session->set_flashdata('message','Your file ['.$image_title.'] was updated successfully');
        $this->session->set_flashdata('messageClass','good');
		redirect('/admin/gallery/all/manage/'.$gallery_id);
	}

	// ------------------------------------ //
	// Individual Delete Function
	// ------------------------------------ //
    public function imagesdetails() {
        $galleryId = $this->input->post('gallery_id'); 
        $imageTitle = $this->input->post('image_title');
        $imageLink = $this->input->post('image_link');
        $imageId = $this->input->post('image_id'); 
        
		$data = array(
			'image_title' => $imageTitle,
			'image_link' => $imageLink
		);			
            $this->db->where('image_id', $imageId);
			$this->db->update('fdcms_media_images', $data); 
        
        $this->session->set_flashdata('message','Your file ['.$imageTitle.'] was updated successfully');
        $this->session->set_flashdata('messageClass','good');
		redirect('/admin/gallery/all/manage/'.$galleryId);
    }

	// ------------------------------------ //
	// Individual Delete Function
	// ------------------------------------ //
    public function imagesdelete() {		
        $galleryId = $this->input->post('gallery_id');
        $imageTitle = $this->input->post('image_title');
        $imageSrc = $this->input->post('image_src');
        $imageId = $this->input->post('image_id');
        
        // Remove page from page table and url_rewrites
        $SQL = "DELETE FROM fdcms_media_images WHERE image_id = '".$imageId."'";
        $query = $this->db->query($SQL);
        
        $SQL2 = "DELETE FROM fdcms_media_i2g WHERE image_id = '".$imageId."'";
        $query2 = $this->db->query($SQL2);
            
        // Delete the file
        if(unlink('.'.$imageSrc)) {        
            $this->session->set_flashdata('message','Your file ['.$imageTitle.'] was deleted successfully');
            $this->session->set_flashdata('messageClass','good');
        } else {
            $this->session->set_flashdata('message','Error attempting to delete file ['.$imageTitle.']');
            $this->session->set_flashdata('messageClass','bad');
        }
        var_dump($fileSrc);
        redirect('/admin/gallery/all/manage/'.$galleryId);
    }
	
	// ------------------------------------- //
	// Get URL on request (AJAX) echo
	// ------------------------------------- //
    function imagesorder() {
        $listOrder = $this->input->post('order');
		$listArray = explode(',',$listOrder);		
		$curOrder = 0;		
		foreach($listArray as $key => $value) {			
			$SQL = "UPDATE fdcms_media_images SET image_sort_order = '".$curOrder."' WHERE image_id = '".$value."'";
			$query = $this->db->query($SQL);			
			$curOrder = $curOrder + 1;
		}
    }
        
        
        
	
}