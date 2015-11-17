';
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailer extends CI_Controller {
	
// ------------------------------------- //
// CONSTRUCT
// ------------------------------------- //
function __construct() {	
  	parent::__construct();
  	$this->load->helper('url');	
}

    public function validateForm($form_id) {
        // Get the required fields  
        $SQL2 = "SELECT * FROM fdcms_forms_f2f f2f JOIN fdcms_forms_fields f ON (f.field_id = f2f.field_id) WHERE f2f.form_id = '".$form_id."' ORDER BY sort_order ASC";
		$query2 = $this->db->query($SQL2);
		$result2 = $query2->result();
        
        // Defaults
        $errorsFound = 'false';
        $errorArray = array();
		
        // Validate the fields
		foreach ($result2 as $rs) {
            $errorArray['form-'.$form_id.'-field-'.$rs->field_id.'-value'] = $this->input->post('form-'.$form_id.'-field-'.$rs->field_id);
            $errorArray['form-'.$form_id.'-field-'.$rs->field_id.'-error'] = '';
            
            if($rs->field_required == '1') {
            
                // Text Validation
                if($rs->field_type == 'text' || $rs->field_type == 'date' || $rs->field_type == 'password' || $rs->field_type == 'textarea') {
                    if($this->input->post('form-'.$form_id.'-field-'.$rs->field_id) == '') {
                        $errorsFound = 'true';
                        $errorArray['form-'.$form_id.'-field-'.$rs->field_id.'-error'] = 'Please enter a value';
                    }
                }
                
                // Telephone Validdation
                if($rs->field_type == 'tel') {
                    if(! $this->validatePhoneNum($this->input->post('form-'.$form_id.'-field-'.$rs->field_id))) {
                        $errorsFound = 'true';
                        $errorArray['form-'.$form_id.'-field-'.$rs->field_id.'-error'] = 'Please enter a valid telephone number';
                    }
                }
                
                // Email Address
                if($rs->field_type == 'email') {
                    if(! filter_var($this->input->post('form-'.$form_id.'-field-'.$rs->field_id), FILTER_VALIDATE_EMAIL)) {
                        $errorsFound = 'true';
                        $errorArray['form-'.$form_id.'-field-'.$rs->field_id.'-error'] = 'Please enter a valid email address';
                    }
                }
                
                // Captcha
                if($rs->field_type == 'captcha') {
                    $valid = $this->input->post('captcha-confirm');
                    $attempt = $this->input->post('form-'.$form_id.'-field-'.$rs->field_id);
                    
                    if($valid != $attempt) {
                        $errorsFound = 'true';
                        $errorArray['form-'.$form_id.'-field-'.$rs->field_id.'-error'] = 'Please prove you\'re a human';
                    } else {
                        // captcha confirmed   
                    }
                }
            
            }
            
        }
        
        if($errorsFound == 'true') {
            return $errorArray;   
        } else {
            return 'true';   
        }
        
        
    }
    
    public function validatePhoneNum($str) {
        $t='/\(?[2-9][0-8][0-9]\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}/';
        return preg_match($t, $str, $mat);
    }

	/* Index Controller */
	/* ****************************************** */
	public function send()
	{		
		// Our form ID	
		$form_id = $this->uri->segment(2);
        
        $body = '';
        
        
        // Validate the form
        $formValid = $this->validateForm($form_id);
        
        if($formValid == 'true') {
		
            // Get the form we're sending
            $SQL = "SELECT * FROM fdcms_forms WHERE form_id = '".$form_id."'";		
            $query = $this->db->query($SQL);
            $result = $query->result();
            
            // Save our form info
            foreach($result as $rs) {
                $form_to = $rs->form_to;
                $form_from = $rs->form_from;
                $form_cc = $rs->form_cc;
                $form_bcc = $rs->form_bcc;
                $form_subject = $rs->form_subject;
                $form_name = $rs->form_name;
                $form_response_action = $rs->form_response_action;
                $form_response_message = $rs->form_response_message;
                $form_response_forward = $rs->form_response_forward;
            }
            
            // Build our form body
            $body = '';
            $body = '<html><head>';
            $body .= '<style type="text/css">';
            $body .= 'body { font-size: 14px; line-height: 26px; }';
            $body .= '.subhead { font-family: Arial; font-size: 14px; color: #fff; background-color: #708da5; }';
            $body .= '.head { font-family: Arial; font-size: 26px; color: #fff; background-color: #295375; text-transform: uppercase; }';
            $body .= '.content { border: 1px solid #295375; }';
            $body .= '.label { font-weight: 700; padding: 4px; border-bottom: 1px solid #ccc; }';
            $body .= '.value { padding: 4px; border-bottom: 1px solid #ccc; }';
            $body .= '</style>';
            $body .= '</head><body>';
        
            $body .= '<center><table cellspacing="0" cellpadding="12" width="700">';
            $body .= '<tr><td><img src="http://'.$_SERVER['HTTP_HOST'].'/images/display/pearl-logo.jpg"></td></tr>';
            $body .= '<tr><td class="subhead">Pearl Midtown</td></tr>';
            $body .= '<tr><td class="head">'.$form_name.'</td></tr>';
            $body .= '<tr><td class="content">';
            
            // Get the form fields
            $SQL2 = "SELECT * FROM fdcms_forms_f2f f2f JOIN fdcms_forms_fields f ON (f.field_id = f2f.field_id) WHERE f2f.form_id = '".$form_id."' ORDER BY sort_order ASC";
            $query2 = $this->db->query($SQL2);
            $result2 = $query2->result();
            
            $body .= '<table width="100%" cellspacing="0" cellpadding="0">';
            
            foreach ($result2 as $rs) {
                    
                    $thisLabel = $rs->field_label;
                    $thisValue = $this->input->post('form-'.$form_id.'-field-'.$rs->field_id);
                    
                    $body .= '<tr>';
                    $body .= '<td class="label">';
                    $body .= $thisLabel;
                    $body .= '</td>';
                    $body .= '<td class="value">';
                    $body .= $thisValue;
                    $body .= '</td>';
                    $body .= '</tr>';
            }
            
            $body .= '</table>';
            
            $body .= '</td></tr>';        
            $body .= '</table>'; 
            $body .= '<br><br>This mesage was automatically generated by a form at: '.$_SERVER['HTTP_REFERER'];
            $body .= '</center>';
            $body .= '</body>';
            $body .= '</html>';
                    
            // Line break fix
            $body = str_replace('\"','"',$body);
            $body = str_replace('</tr>','
            </tr>',$body);
            
            // Headers
            $headers  = "From: $form_from\r\n"; 
            $headers .= "Cc: $form_cc\r\n"; 
            $headers .= "Bcc: $form_bcc\r\n"; 
            $headers .= "Content-type: text/html\r\n"; 
            
            
            // Send the e-mail
            mail($form_to,$form_subject,$body,$headers);
            $this->session->set_flashdata('form_sent', $form_response_message);
            
            // Redirect
            if($form_response_action == '1') {
                redirect($_SERVER['HTTP_REFERER']);
            } else if($form_response_action == '2') {
                redirect($form_response_forward);
            }
        
        } else {
        
        
            $this->session->set_flashdata('form_data', $formValid);
            redirect($_SERVER['HTTP_REFERER']);
        
        }
	}
}

/* End of file mailer.php */
/* Location: ./application/controllers/display/mailer.php */