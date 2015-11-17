<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System extends CI_Controller {
    
    
    public function index() {        
        $data["html"] = 'System Controller';        
        $this->load->view('system/ajax',$data);    
    }
    
    public function getContent() {
     $blockId = str_replace('pContent_','',$this->input->post('blockId'));
     $pageContent = json_decode($this->input->post('pageContent'),true);
     var_dump($pageContent);
     if(isset($pageContent[''.$blockId.''])) {
        $thisContent = html_entity_decode($pageContent[''.$blockId.'']);     
     } else {
        $thisContent = '<i>Please Enter Content Here</i>';   
     }
     $data["html"] = $thisContent;     
     $this->load->view('system/ajax',$data);
    }
    
}

?>