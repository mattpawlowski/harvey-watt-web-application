<?php 

// Section Name: All Pages
// Section Link: all
// Section Order: 2
// Section Active: true

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class All extends CI_Controller {
	
	public $data = array();
    public $path = '/admin/pages';
    public $primary_table = 'fdcms_pages';
    public $primary_model = 'pages_model';
	
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
		$this->load->model('app/pages/'.$this->primary_model);
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
			'name' => 'Add New Page',
			'link' => '/admin/pages/all/create',
			'icon' => 'add-icon.png',
            'class' => 'green'
		);
		array_push($toolboxArray, $addtool);
        
        
        $this->data["scripts"] = '
	    <script type="text/javascript" src="/js/system/jquery-ui-1.10.4.min.js"></script>
        <script type="text/javascript" src="/js/app/admin-list-tables.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Pages';
		$this->data["adminContent"] = $model->renderRecurseTable();
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
        
        
        $this->data["scripts"] = '<script type="text/javascript" src="/plugins/tinymce/js/tinymce/tinymce.min.js"></script>';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Add New Page';
		$this->data["adminContent"] = $model->create();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->createActions('Create Page');
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
        $this->data["adminTitle"] = 'Edit Page &raquo; <span class="small">'.$this->pages_model->getPageName($id).'</span>';
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
        $response = array();
        $response = $this->pages_model->save();
		if($response["id"] > 0) {
			$this->session->set_flashdata('message',$response["message"]);
			$this->session->set_flashdata('messageClass','good');
			redirect('/admin/pages/all/edit/'.$response["id"]);
		} else {
			$this->session->set_flashdata('message','Sorry - something went wrong.');
			$this->session->set_flashdata('messageClass','bad');
		}				
		$this->load->view('app/ajax', $data);
	}
	
	// ------------------------------------- //
	// Get URL on request (AJAX) echo
	// ------------------------------------- //
    function order() {
        $listOrder = $this->input->post('order');
		$listArray = explode(',',$listOrder);		
		$curOrder = 0;		
		foreach($listArray as $key => $value) {			
			$SQL = "UPDATE fdcms_pages SET sort_order = '".$curOrder."' WHERE page_id = '".$value."'";
			$query = $this->db->query($SQL);			
			$curOrder = $curOrder + 1;
		}
    }

	// ------------------------------------ //
	// Individual Delete Function
	// ------------------------------------ //
    public function delete() {		
        $id = $this->uri->segment(5);
        
        // Remove page from page table and url_rewrites
        $SQL = "DELETE FROM fdcms_pages WHERE page_id = '".$id."'";
        $query = $this->db->query($SQL);
        
        // Delete from URL rewrites
        $SQL2 = "DELETE FROM fdcms_url_rewrites WHERE page_id = '".$id."'";
        $query2 = $this->db->query($SQL2);
        
        // Delete all the featured videos
        $SQL3 = "DELETE fdcms_media_videos , fdcms_media_v2p FROM fdcms_media_videos INNER JOIN fdcms_media_v2p  WHERE fdcms_media_v2p.video_id = fdcms_media_videos.video_id AND fdcms_media_v2p.page_id = '".$id."'";
        $query3 = $this->db->query($SQL3);
        
        // Delete all the featured files from server
        $SQL4 = "SELECT file_src FROM fdcms_media_files f JOIN fdcms_media_f2p f2p ON (f.file_id = f2p.file_id) WHERE f2p.page_id = '".$id."'";
        $query4 = $this->db->query($SQL4);
        $result = $query4->result();
        foreach($result as $rs) {
            unlink('.'.$rs->file_src); // Delete the physical file
        }
        
        // Dlete featured files from dB records
        $SQL5 = "DELETE fdcms_media_files , fdcms_media_f2p FROM fdcms_media_files INNER JOIN fdcms_media_f2p WHERE fdcms_media_f2p.file_id = fdcms_media_files.file_id AND fdcms_media_f2p.page_id = '".$id."'";
        $query5 = $this->db->query($SQL5);
        
        // Delete all the featured images from server
        $SQL6 = "SELECT image_src FROM fdcms_media_images i JOIN fdcms_media_i2p i2p ON (i.image_id = i2p.image_id) WHERE i2p.page_id = '".$id."'";
        $query6 = $this->db->query($SQL6);
        $result2 = $query6->result();
        foreach($result2 as $rs2) {
            unlink('.'.$rs2->image_src); // Delete the physical file
        }
        
        // Dlete featured files from dB records
        $SQL7 = "DELETE fdcms_media_images , fdcms_media_i2p FROM fdcms_media_images INNER JOIN fdcms_media_i2p WHERE fdcms_media_i2p.image_id = fdcms_media_images.image_id AND fdcms_media_i2p.page_id = '".$id."'";
        $query7 = $this->db->query($SQL7);
        
        $data["html"] = 'Deleted Pages sucessfully';
        $this->load->view('system/ajax',$data);
    }

	// ------------------------------------ //
	// Edit SEO (Title|Description)
	// ------------------------------------ //
	public function meta() {
        $id = $this->uri->segment(5);
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = $model->editToolbox($id);
        
        
        $this->data["scripts"] = '<script type="text/javascript" src="/plugins/tinymce/js/tinymce/tinymce.min.js"></script>';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'SEO Settings &raquo; <span class="small">'.$this->pages_model->getPageName($id).'</span>';
		$this->data["adminContent"] = $model->meta();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->editActions('Save Changes');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function metasave() {		
        $response = array();
        $response = $this->pages_model->metaSave();
        var_dump($response);
		if($response["id"] > 0) {
			$this->session->set_flashdata('message',$response["message"]);
			$this->session->set_flashdata('messageClass','good');
			redirect('/admin/pages/all/meta/'.$response["id"]);
		} else {
			$this->session->set_flashdata('message','Sorry - something went wrong.');
			$this->session->set_flashdata('messageClass','bad');
		}				
		$this->load->view('app/ajax', $data);
	}

	// ------------------------------------ //
	// Featured Videos
	// ------------------------------------ //
	public function videos() {
        $id = $this->uri->segment(5);
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = $model->editToolbox($id);
        
        
        $this->data["scripts"] = '
            <script type="text/javascript" src="/js/system/jquery-ui-1.10.4.min.js"></script>
            <script type="text/javascript" src="/js/app/admin-list-tables.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Featured Videos &raquo; <span class="small">'.$this->pages_model->getPageName($id).'</span>';
		$this->data["adminContent"] = $model->videos();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->editActions('Add Video');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function videossave() {		
        $response = array();
        $response = $this->pages_model->videosSave();
        var_dump($response);
		if($response["id"] > 0) {
			$this->session->set_flashdata('message',$response["message"]);
			$this->session->set_flashdata('messageClass','good');
			redirect('/admin/pages/all/videos/'.$response["id"]);
		} else {
			$this->session->set_flashdata('message','Sorry - something went wrong.');
			$this->session->set_flashdata('messageClass','bad');
		}				
		$this->load->view('app/ajax', $data);
	}
	
	// ------------------------------------- //
	// Get URL on request (AJAX) echo
	// ------------------------------------- //
    function videosorder() {
        $listOrder = $this->input->post('order');
		$listArray = explode(',',$listOrder);		
		$curOrder = 0;		
		foreach($listArray as $key => $value) {			
			$SQL = "UPDATE fdcms_media_videos SET video_sort_order = '".$curOrder."' WHERE video_id = '".$value."'";
			$query = $this->db->query($SQL);			
			$curOrder = $curOrder + 1;
		}
    }

	// ------------------------------------ //
	// Individual Delete Function
	// ------------------------------------ //
    public function videosdelete() {		
        $pageId = $this->input->post('page_id');
        $videoTitle = $this->input->post('video_title');
        $videoId = $this->input->post('video_id');
        
        // Remove page from page table and url_rewrites
        $SQL = "DELETE FROM fdcms_media_videos WHERE video_id = '".$videoId."'";
        $query = $this->db->query($SQL);
        
        $SQL2 = "DELETE FROM fdcms_media_v2p WHERE video_id = '".$videoId."'";
        $query2 = $this->db->query($SQL2);
        
        $this->session->set_flashdata('message','Your video ['.$videoTitle.'] was removed successfully');
        $this->session->set_flashdata('messageClass','good');
        redirect('/admin/pages/all/videos/'.$pageId);
    }

	// ------------------------------------ //
	// Featured Videos
	// ------------------------------------ //
	public function files() {
        $id = $this->uri->segment(5);
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = $model->editToolbox($id);
        
        
        $this->data["scripts"] = '
            <script type="text/javascript" src="/js/system/jquery-ui-1.10.4.min.js"></script>
            <script type="text/javascript" src="/js/app/admin-list-tables.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Attached Files &raquo; <span class="small">'.$this->pages_model->getPageName($id).'</span>';
		$this->data["adminContent"] = $model->files();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->editActions('Upload File');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function filessave() {		
        // We're putting all our files in a directory called images.
		$uploaddir = './uploads/files/';
		 
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
			'file_title' => $this->input->post('file_title'),
			'file_src' => '/uploads/files/'.$randomName,
			'file_type' => $_FILES['file']['type'],
			'file_size' => $_FILES['file']['size'],
			'file_upload_date' => date('d-M-Y')
		);			
			$this->db->insert('fdcms_media_files', $data);
			$thisId = $this->db->insert_id();
            //print_r("called"); 
			
		// Add it to the assignment
		$pageId = $this->uri->segment(5);
		$fileId = $thisId;
		
		$data = array(
			'file_id' => $fileId,
			'page_id' => $pageId
		);
		
		$this->db->insert('fdcms_media_f2p', $data);		
		redirect('/admin/pages/all/files/'.$pageId);
	}
	
	// ------------------------------------- //
	// Get URL on request (AJAX) echo
	// ------------------------------------- //
    function filesorder() {
        $listOrder = $this->input->post('order');
		$listArray = explode(',',$listOrder);		
		$curOrder = 0;		
		foreach($listArray as $key => $value) {			
			$SQL = "UPDATE fdcms_media_files SET file_sort_order = '".$curOrder."' WHERE file_id = '".$value."'";
			$query = $this->db->query($SQL);			
			$curOrder = $curOrder + 1;
		}
    }

	// ------------------------------------ //
	// Individual Delete Function
	// ------------------------------------ //
    public function filesdelete() {		
        $pageId = $this->input->post('page_id');
        $fileTitle = $this->input->post('file_title');
        $fileSrc = $this->input->post('file_src');
        $fileId = $this->input->post('file_id');
        
        // Delete the file
        if(unlink('.'.$fileSrc)) {        
            // Remove page from page table and url_rewrites
            $SQL = "DELETE FROM fdcms_media_files WHERE file_id = '".$fileId."'";
            $query = $this->db->query($SQL);
            
            $SQL2 = "DELETE FROM fdcms_media_f2p WHERE file_id = '".$fileId."'";
            $query2 = $this->db->query($SQL2);
            
            $this->session->set_flashdata('message','Your file ['.$fileTitle.'] was deleted successfully');
            $this->session->set_flashdata('messageClass','good');
        } else {
            $this->session->set_flashdata('message','Error attempting to delete file ['.$fileTitle.']');
            $this->session->set_flashdata('messageClass','bad');
        }
        var_dump($fileSrc);
        redirect('/admin/pages/all/files/'.$pageId);
    }

	// ------------------------------------ //
	// Featured Videos
	// ------------------------------------ //
	public function images() {
        $id = $this->uri->segment(5);
        $model = $this->primary_model;
        $model = $this->$model;
        
		$toolboxArray = $model->editToolbox($id);
        
        
        $this->data["scripts"] = '
            <script type="text/javascript" src="/js/system/jquery-ui-1.10.4.min.js"></script>
            <script type="text/javascript" src="/js/app/admin-list-tables.js"></script>
            <script type="text/javascript" src="/js/app/pagesImage.js"></script>
        ';
        $this->data["styles"] = '';
        $this->data["adminTitle"] = 'Featured Images &raquo; <span class="small">'.$this->pages_model->getPageName($id).'</span>';
		$this->data["adminContent"] = $model->images();
        $this->data["adminToolbox"] = $this->core_model->buildListNav($toolboxArray);
        $this->data["adminActions"] = $this->core_model->editActions('Upload Image');
        $this->data["userStatus"] = $this->core_model->renderUserStatus();
        $this->load->view('app/admin-layout',$this->data);
	}

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function imagessave() {		
        // We're putting all our files in a directory called images.
		$uploaddir = './uploads/images/';
		 
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
			'image_title' => $this->input->post('image_title'),
			'image_link' => $this->input->post('image_link'),
			'image_src' => '/uploads/images/'.$randomName,
			'image_type' => $_FILES['file']['type'],
			'image_size' => $_FILES['file']['size'],
			'image_upload_date' => date('d-M-Y')
		);			
			$this->db->insert('fdcms_media_images', $data);
			$thisId = $this->db->insert_id();
            //print_r("called"); 
			
		// Add it to the assignment
		$pageId = $this->uri->segment(5);
		$fileId = $thisId;
		
		$data = array(
			'image_id' => $fileId,
			'page_id' => $pageId
		);
		
		$this->db->insert('fdcms_media_i2p', $data);		
		redirect('/admin/pages/all/images/'.$pageId);
	}

	// ------------------------------------ //
	// Save Page Info
	// ------------------------------------ //
    public function imagesupdate() {		
    
        $image_id = $this->input->post('image_id');
        $image_src = $this->input->post('image_src');
        $page_id = $this->input->post('page_id');
        $image_title = $this->input->post('image_title');
    
        // We're putting all our files in a directory called images.
		$uploaddir = './uploads/images/';		 
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
			'image_src' => '/uploads/images/'.$randomName,
			'image_type' => $_FILES['file']['type'],
			'image_size' => $_FILES['file']['size'],
		);			
            $this->db->where('image_id', $image_id);
			$this->db->update('fdcms_media_images', $data);
            
        unlink('.'.$image_src);
            
        $this->session->set_flashdata('message','Your file ['.$image_title.'] was updated successfully');
        $this->session->set_flashdata('messageClass','good');
		redirect('/admin/pages/all/images/'.$page_id);
	}

	// ------------------------------------ //
	// Individual Delete Function
	// ------------------------------------ //
    public function imagesdetails() {
        $pageId = $this->input->post('page_id'); 
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
		redirect('/admin/pages/all/images/'.$pageId);
    }

	// ------------------------------------ //
	// Individual Delete Function
	// ------------------------------------ //
    public function imagesdelete() {		
        $pageId = $this->input->post('page_id');
        $imageTitle = $this->input->post('image_title');
        $imageSrc = $this->input->post('image_src');
        $imageId = $this->input->post('image_id');
        
        // Remove page from page table and url_rewrites
        $SQL = "DELETE FROM fdcms_media_images WHERE image_id = '".$imageId."'";
        $query = $this->db->query($SQL);
        
        $SQL2 = "DELETE FROM fdcms_media_i2p WHERE image_id = '".$imageId."'";
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
        redirect('/admin/pages/all/images/'.$pageId);
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
	
	// ------------------------------------- //
	// Get URL on request (AJAX) echo
	// ------------------------------------- //
    function get_url() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT page_id, page_url FROM fdcms_pages WHERE page_id = '".$id."'";   
        $query = $this->db->query($SQL);
        $result = $query->row();        
        $url = $result->page_url;
        
        echo $url;
    }

	// ------------------------------------ //
	// Update our Content Blocks
	// ------------------------------------ //
    function updateBlocks() {
        $layout = $this->input->post('layout');
        $data = array();
        
        $data["html"] = $this->pages_model->renderContentBlocks($layout);
        
        //$data["html"] = '<p>Sample Return HTML</p>';
    
        $this->load->view('system/ajax',$data);   
    }

	// ------------------------------------ //
	// Duplicate Funtion
	// ------------------------------------ //
    function duplicate() {
        $id = $this->uri->segment(5);
        $model = $this->primary_model;
        $model = $this->$model;
        
        $response = array();
        $response = $model->duplicate($id);
		if($response["success"] == 'true') {
			$this->session->set_flashdata('message',$response["message"]);
			$this->session->set_flashdata('messageClass','good');
		} else {
			$this->session->set_flashdata('message',$rsponse["message"]);
			$this->session->set_flashdata('messageClass','bad');
		}				
        redirect('/admin/pages/all','refresh');   
    }
	
}