<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	/* Index Controller */
	/* ****************************************** */
	public function index()
	{
		$this->view();
	}
	
	
	/* Main Page View */
	/* ****************************************** */
	public function view() {
		$this->load->model('display/page_loader');
		
		// get and parse the URL string
		$request_uri = $this->page_loader->getUrlString();
		if($request_uri == '') {
			$request_uri = '/';	
		}		
		
		//get page content
        
		$data = $this->page_loader->loadRequest($request_uri);
        
        
        $params = array('page_id' => $data["pageId"], 'page_url' => $data["pageUrl"]);
        $this->load->library("Fdcms",$params);
		$data["fdcms"] = $this->fdcms;
		$this->load->view('display/'.$data["view"], $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */