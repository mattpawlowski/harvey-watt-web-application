<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Core_model extends CI_Model {
    
    // ----------------------------------- //
	// RUNS ON EVERY PAGE
	// ----------------------------------- //
    function __construct() {
    	$this->load->helper('url');
        $logged_in = $this->user_isvalidated();
        if($logged_in == false) { redirect('/admin/login'); }
        
        // check for logout
        if(isset($_GET['logout'])) { $this->session->sess_destroy(); redirect('admin','refresh'); }
    }
    
    // ----------------------------------- //
	// RUNS ON EVERY PAGE
	// ----------------------------------- //
    private function user_isvalidated() {
		if(! $this->session->userdata('validated')) {
			return false;
		} else {
            return true;   
        }
	}
    
    // ----------------------------------- //
	// RENDER USER STATUS
	// ----------------------------------- //
    function renderUserStatus() {
		$rstr = '';
		
		$rstr .= 'Logged In as ';
		$rstr .= $this->session->userdata('fname').' '.$this->session->userdata('lname');
		$rstr .= ' | ';
		$rstr .= '<a href="?logout">Logout</a>';		
		
		return $rstr;	
	}

	// ----------------------------------- //
	// Load our Controllers and Nav 
	// ----------------------------------- //
	public function renderAdminNav() {
        $this->load->helper('directory');
        $this->load->helper('file');               
        $map = directory_map('./application/controllers/app', FALSE, FALSE);  
        $navigationArray = array();        
        foreach($map as $key => $value) {            
            if(is_array($value)) {                
                // Get our section settings
                $onfigArray = array();
                $configArray = $this->readConfig('./application/controllers/app/'.$key.'/section-config.php');      
                if(!empty($value) && $configArray != NULL) {    
                    $subsectionArray = array();
                    foreach($value as $index => $section) {
                        if($section != 'section-config.php' && $section != 'home.php') {
                            $subsectionConfig = array();
                            $subsectionConfig = $this->readConfig('./application/controllers/app/'.$key.'/'.$section);
                            if($subsectionConfig != NULL) {
                                array_push($subsectionArray,$subsectionConfig);
                            }
                        }
                    }
                    $subsectionArray = $this->aasort($subsectionArray,"order");
                    $appendArray = $this->additionalLinks('./application/controllers/app/'.$key.'/section-config.php'); 
                    foreach($appendArray as $key => $value) {
                        $subsectionArray[] = $value;  
                    }
                    $configArray["subsection"] = $subsectionArray;                    
                } else {
                    // DON'T HANDLE EMPTY FILES   
                }
            } else {
                // DON'T HANDLE LOOSE FILES
            } // end if            
            array_push($navigationArray,$configArray);        
        } // end foreach
        $navigationArray = array_filter($this->aasort($navigationArray,"order"));
        return $this->buildAdminNav($navigationArray);        
        
    }

	// ----------------------------------- //
	// Load our Controllers and Nav 
	// ----------------------------------- //
	public function buildAdminNav($navArray) {
        $this->load->helper('url');
        
        $current = current_url();
        $current = str_replace(base_url(),'',$current);
        
        $dir = $this->uri->segment(2);
        
        $rstr ='';
        
        $rstr .= '<ul class="admin-nav">';
        foreach($navArray as $item) {
            if($dir == $item["link"]) { $active = ' class="parent-active"'; } else { $active = ' class="parent-inactive"'; }
            $rstr .= '<li'.$active.'>';
            $rstr .= '<!-- Link For: '.$item["link"].' -->';
            $rstr .= '<a href="/admin/'.$item["link"].'">';
            $rstr .= $item["name"];
            $rstr .= '</a>';
            
            if(!empty($item["subsection"])) {
                $rstr .= '<ul>';
                
                foreach($item["subsection"] as $controller) {
                    if($current == 'admin/'.$item["link"].'/'.$controller["link"]) { $active = ' class="active"'; } else { $active = ' class="inactive"'; }
                    $rstr .= '<li'.$active.'>';
                    $rstr .= '<a href="/admin/'.$item["link"].'/'.$controller["link"].'">';
                    $rstr .= $controller["name"];
                    $rstr .= '</a>';
                    $rstr .= '</li>';
                }
                
                $rstr .= '</ul>';
            }
            
            $rstr .= '</li>';
        }
        $rstr .= '</ul>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Load our Controllers and Nav 
	// ----------------------------------- //
	public function readConfig($path) {
        $configArray = array();        
        $fileContents = read_file($path);
        $values = 'true';
        
        // See if controller is active
        $regex = '#Section Active: (.*)#';
        if(preg_match($regex, $fileContents, $match)) {
            $configArray["active"] = $match[1];
        } else {
            $values = 'false';
        }
        
        // Get the Section Name
        $regex2 = '#Section Name: (.*)#';				
        if(preg_match($regex2, $fileContents, $match2)) {
            $configArray["name"] = $match2[1];
        } else {
            $values = 'false';
        }// endif
        
        // Get the Section Link
        $regex3 = '#Section Link: (.*)#';				
        if(preg_match($regex3, $fileContents, $match3)) {
            $configArray["link"] = $match3[1];
        } else {
            $values = 'false';
        }// endif
        
        // Get the Section Link
        $regex4 = '#Section Order: (.*)#';				
        if(preg_match($regex4, $fileContents, $match4)) {
            $configArray["order"] = $match4[1];
        } else {
            $values = 'false';
        }// endif
        
        // Get the Additonal Links
        $regex5 = '#Additonal Links: (.*)#';				
        if(preg_match($regex5, $fileContents, $match5)) {
            $configArray["addArray"] = $match5[1];
        } else {
            
        }// endif
        
        // Get the Additonal Links
        $regex6 = '#Section Active: (.*)#';				
        if(preg_match($regex6, $fileContents, $match6)) {
            $configArray["active"] = $match6[1];
        } else {
            
        }// endif
        
        if($values == 'true' && $configArray["active"] == 'true') {
            return $configArray;
        } else {
            return NULL;
        }
    }

	// ----------------------------------- //
	// Load our Controllers and Nav 
	// ----------------------------------- //
	public function additionalLinks($path) {
        $additionalLinks = array();        
        $fileContents = read_file($path);
        $values = 'true';
        
        // See if controller is active
        $regex = '#Additional Link: \'(.*)\':\'(.*)\'#';
        if(preg_match_all($regex, $fileContents, $matches, PREG_PATTERN_ORDER)) {
            $numMatches = count($matches[0]);
            
            for($i = 0; $i < $numMatches; $i++) {
                $configArray = array();        
                $configArray["name"] = $matches[1][$i];
                $configArray["link"] = $matches[2][$i];  
                $additionalLinks[] = $configArray; 
            }
        } else {
            $values = 'false';
        }
        
        if($values = 'true') {
            return $additionalLinks;
        } else {
            return NULL;
        }
    }

	// ----------------------------------- //
	// Load our Controllers and Nav 
	// ----------------------------------- //
	public function aasort($array, $key) {
        $sorter=array();
        $ret=array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
        $array=$ret;
        
        return $array;
    }
	
	// ------------------------------------- //
	// Build List Array Nav
	// ------------------------------------- //
	public function buildListNav($array) {
		$rstr = '';
		
		foreach($array as $item) {
            if($item["class"]) {
                $class = ' class="'.$item["class"].'"';
            } else {
                $class = '';   
            }
            
			$rstr .= '<li>';
			$rstr .= '<a href="'.$item["link"].'"'.$class.'>';
			
			if(isset($item["icon"])) {
			 $rstr .= '<img src="/images/app/icons/'.$item["icon"].'">';	
			}
			
			
			$rstr .= $item["name"];
			$rstr .= '</a>';
			$rstr .= '</li>';	
		}
		
		return $rstr;
	}
	
	// ------------------------------------- //
	// Build List Array Nav
	// ------------------------------------- //
    public function createActions($str) {
        $rstr = '';
        
        $rstr .= '<li>';
        $rstr .= '<div style="position: relative;">';
        $rstr .= '<img src="/images/app/core/loading.gif" style="position: absolute; top: 15px; left: 15px; display: none;" class="loading">';
        $rstr .= '<a href="javascript:void(0);" id="save-form">';
        $rstr .= $str;
        $rstr .= '</a>';
        $rstr .= '</div>';
        $rstr .= '</li>';
        
        return $rstr;
    }
	
	// ------------------------------------- //
	// Build List Array Nav
	// ------------------------------------- //
    public function editActions($str) {
        $rstr = '';
        
        $rstr .= '<li>';
        $rstr .= '<div style="position: relative;">';
        $rstr .= '<img src="/images/app/core/loading.gif" style="position: absolute; top: 15px; left: 15px; display: none;" class="loading">';
        $rstr .= '<a href="javascript:void(0);" id="save-form">';
        $rstr .= $str;
        $rstr .= '</a>';
        $rstr .= '</div>';
        $rstr .= '</li>';
        
        return $rstr;
    }
	
	// ------------------------------------- //
	// Build List Array Nav
	// ------------------------------------- //
    function get_extension($file_name){
        $ext = explode('.', $file_name);
        $ext = array_pop($ext);
        return strtolower($ext);
    }
	
	// ------------------------------------- //
	// Build List Array Nav
	// ------------------------------------- //  
    function exit_status($str){
        echo json_encode(array('status'=>$str));
        exit;
    }  
	
	// ------------------------------------- //
	// Resize Image JPG
    // Param 1: Image SRC
    // Param 2: Max Width Allowed
    // Param 3: Max Height Allowed
	// ------------------------------------- //  
    function resizeImageJpg($src, $mWidth = 1400, $mHeight = 1400){
        $size = getimagesize($src);
        $ratio = $size[0]/$size[1]; // width/height
        if( $ratio > 1) {
            $width = $mWidth;
            $height = $mWidth/$ratio;
        }
        else {
            $width = $mHeight*$ratio;
            $height = $mHeight;
        }
        $src = imagecreatefromstring(file_get_contents($src));
        $dst = imagecreatetruecolor($width,$height);
        imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
        imagedestroy($src);
        imagejpeg($dst,$src); // adjust format as needed
        imagedestroy($dst);
    }
	
	// ------------------------------------- //
	// Resize Image PNG
    // Param 1: Image SRC
    // Param 2: Max Width Allowed
    // Param 3: Max Height Allowed
	// ------------------------------------- //  
    function resizeImagePng($src, $mWidth = 1400, $mHeight = 1400){
        $size = getimagesize($src);
        $ratio = $size[0]/$size[1]; // width/height
        if( $ratio > 1) {
            $width = $mWidth;
            $height = $mWidth/$ratio;
        }
        else {
            $width = $mHeight*$ratio;
            $height = $mHeight;
        }
        $src = imagecreatefromstring(file_get_contents($src));
        $dst = imagecreatetruecolor($width,$height);
        imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
        imagedestroy($src);
        imagepng($dst,$src); // adjust format as needed
        imagedestroy($dst);
    }
	
	// ------------------------------------- //
	// Delete an Image
	// ------------------------------------- //  
    function deleteFile($src){
        unset($src);
    }
	
}