<?php

class Page_Loader extends CI_Model {
	
	/* Get URL String */
	/* ****************************************** */
	public function getUrlString() {
		//segment the uri array
		$argv = $this->uri->segment_array();
		$argc = count($argv);
		$request_uri = '';
		
		//stack it all up as a string
		foreach($argv as $item) {
			$request_uri .= '/'.$item;	
		}
		
		return $request_uri;	
	}
	
	public function loadRequest($request_uri) {
		$query = $this->db->query("SELECT * FROM fdcms_url_rewrites WHERE url = '".$request_uri."' LIMIT 1");
		if($query->num_rows() > 0) {
			foreach($query->result() as $rs) {
				if($rs->page_id > 0) {
                    // Load Page Info
					$data = $this->getPageInfo($rs->page_id);
				} else if($rs->news_id > 0) {
                    // Load News Info
				} else if ($rs->event_id > 0) {
                    // Load Event Info
                }else if($rs->blog_id > 0) {
                    // Load Blog Info
				}
			}
				
		} else {
			$data = $this->loadNotFound();
		}		
		return $data;
	}
                
	
	/* Get Page Info */
	/* ****************************************** */
	public function getPageInfo($id) {	
		
		$query = $this->db->query("SELECT * FROM fdcms_pages WHERE page_id = '".$id."' LIMIT 1");
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data["pageId"] = $row->page_id;
				$data["pageMetaTitle"] = $row->page_meta_title;
				$data["pageMetaDesc"] = $row->page_meta_desc;
				$data["pageName"] = $row->page_name;
				$data["pageSubtitle"] = $row->page_subtitle;
				$data["pageUrl"] = $row->page_url;	
				$data["view"] = $row->page_layout;
			}				
		}
		
		return $data;
	}
    
}

?>