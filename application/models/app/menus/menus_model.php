<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menus_model extends CI_Model {
    
    public $primary_table;
    public $path;
	
    
	// ----------------------------------- //
	// Initialize Our Primary Table
	// ----------------------------------- //
	public function initialize($table,$path) {
		$this->primary_table = $table;	
        $this->path = $path;
	}
	
    
	// ----------------------------------- //
	// Initialize Our Primary Table
	// ----------------------------------- //
    function renderMenuTable() {
        $rstr = '';
        
        $rstr .= '<table class="fdcms-table" width="100%" cellpadding="0" cellspacing="0">';
        $rstr .= $this->renderTableRows();
        
        $rstr .= '<tr>';
        $rstr .= '<td colspan="2">';
        $rstr .= '<img src="/images/app/core/arrow_ltr.png"> <a href="javascript: void(0);" class="check-all edit-link">Check</a> / <a href="javascript: void(0);" class="uncheck-all delete-link">Uncheck</a> All';
        $rstr .= '<a href="javascript:void(0);" class="delete delete-selected" data-post="'.$this->path.'/delete">Delete Selected</a>';
        $rstr .= '</td>';
        $rstr .= '</tr>';
        
        $rstr .= '</table>';
        
        return $rstr;
    }

	// ----------------------------------- //
	// Row output for pages table
	// ----------------------------------- //
    public function renderTableRows() {
        $rstr = '';
        
        $this->db->from($this->primary_table);
        $query = $this->db->get();
        $count = $query->num_rows();
        $result = $query->result();
        
        if($count > 0) {
        
        foreach($result as $rs) {
            
            $class = ' class="native"';  
            
            $rstr .= '<tr id="'.$rs->menu_id.'">';
            
            // delete checkbox
            $rstr .= '<td width="20"'.$class.' valign="top">';
            $rstr .= '<input type="checkbox" name="'.$rs->menu_id.'" value="'.$rs->menu_id.'" id="box_'.$rs->menu_id.'" class="del-box" data-id="'.$rs->menu_id.'" data-post="/admin/extras/menus/delete/'.$rs->menu_id.'">';
            $rstr .= '</td>';
            
            $rstr .= '<td'.$class.'>';
            $rstr .= '<div class="options-hover">';
            $rstr .= '<b>'.$rs->menu_name.'</b>';
            $rstr .= '<span class="row-options">';
            $rstr .= '<a href="/admin/extras/menus/edit/'.$rs->menu_id.'" class="edit-link">Edit</a>';
            $rstr .= ' | <a href="javascript:void(0)" class="delete-link delete-item" data-id="'.$rs->menu_id.'" data-post="'.$this->path.'/delete/'.$rs->menu_id.'">Delete</a>';
            $rstr .= '</span>';
            $rstr .= '</div>';
            $rstr .= '</td>';
            
            $rstr .= '</tr>';  
        }
        
        } else {
            $rstr .= '<tr><td>';
            $rstr .= '<div class="form-row"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>You haven\'t created any Menus for your website yet. Start by clicking "Create New Menu" on the right.</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="add-menus"></div></div>';   
            $rstr .= '</td></tr>';
        }
        
        return $rstr;
    }
    
    

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function create() {
        
        $data = array(
            'menu_id' => '0',
            'menu_name' => 'My New Menu',
            'menu_slug' => ''
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/menusEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function edit() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT * FROM fdcms_menus WHERE menu_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        $data = array(
            'menu_id' => $row->menu_id,
            'menu_name' => $row->menu_name,
            'menu_slug' => $row->menu_slug
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/menusEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function save() {
        $id = $this->uri->segment(5);
        $data = array(
            'menu_name' => $this->input->post('menu_name'),
            'menu_slug' => $this->input->post('menu_slug')
        );
        
        
        if($id == 0) {
            // Creating a new menu    
            $this->db->insert('fdcms_menus',$data);
			$return["id"] = $this->db->insert_id();
            $return["message"] = "Your new Menu [ ".$data["menu_name"]." ] was created successfully.";
        } else {
            // Update an existing menu
            $this->db->where('menu_id',$id);
			$this->db->update('fdcms_menus',$data);           
            $return["id"] = $id;
            $return["message"] = "Your changes have been saved.";
        }
        
        return $return;
        
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function menuitems() {
        $id = $this->uri->segment(5);
        $rstr = '';
        
        $rstr .= '<input type="hidden" name="menu_id" id="menu_id" value="'.$id.'">';
        
        $rstr .= '<div class="sortable-options">';
        $rstr .= $this->renderPageAdd();
        $rstr .= $this->renderManualAdd();
        $rstr .= '</div>';
        
        $rstr .= '<div class="sortable-list">';        
            $rstr .= '<h3>My Menu</h3>';
            $rstr .= '<div class="sortable-menu">';
                $rstr .= $this->recurseMenuStructure($id,'0');
            $rstr .= '</div>';
        $rstr .= '</div>';
        
        $rstr .= '<div class="clear"></div>';
        
            
        $rstr .= '
        <script type="text/javascript">
        $(document).ready(function(){

            $(\'ol.sortable\').nestedSortable({
                forcePlaceholderSize: true,
                handle: \'div\',
                helper:	\'clone\',
                items: \'li\',
                opacity: .6,
                placeholder: \'placeholder\',
                revert: 250,
                tabSize: 25,
                tolerance: \'pointer\',
                toleranceElement: \'> div\'
            });
            
            $(\'.disclose\').on(\'click\', function() {
            $(this).closest(\'li\').toggleClass(\'mjs-nestedSortable-collapsed\').toggleClass(\'mjs-nestedSortable-expanded\');
		})

        });
        </script>
        ';
        
        
        return $rstr;   
    }
    
    public function recurseMenuStructure($id, $parent = '0',$identity_count = 0) {
        $menuId = $id;        
        $rstr = '';
        
        $SQL = "SELECT * FROM fdcms_menus_items WHERE item_menu = '".$id."' AND item_parent_item = '".$parent."' ORDER BY sort_order ASC";
        $query = $this->db->query($SQL);
        $count = $query->num_rows();
        $result = $query->result();
        
        if($count > 0) {
            
            if($parent == 0) {
                $rstr .= '<ol class="sortable">';
            } else {
                $rstr .= '<ol>';
            }
            
            foreach($result as $rs) {
                
                $rstr .= '<li id="'.$rs->item_id.'" data-url="'.$rs->item_url.'" data-text="'.$rs->item_text.'" data-pageid="'.$rs->item_page.'">';
                $identity_count ++;
                $rstr .= '<div><span class="disclose"></span><span>'.$rs->item_text.'</span><span class="menu-remove"></span></div>';
                
                $SQL2 = "SELECT item_id FROM fdcms_menus_items WHERE item_menu = '".$menuId."' AND item_parent_item = '".$rs->item_identity."' LIMIT 1";
                $subquery = $this->db->query($SQL2);
                $subcount = $subquery->num_rows();
                if(($subcount > 0)) { $rstr .= $this->recurseMenuStructure($id, $rs->item_identity,$identity_count); }            
                $rstr .= '</li>';
                
            }
            $rstr .= '</ol>';
            
            
        } else {
            
            $rstr .= '<ol class="sortable">';
            $rstr .= '</ol>';
            
            $rstr .= '<div class="form-row temp-message"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>You haven\'t created any items for this menu. Add a new link or add an existing page from your website using the form to the right</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="add-menu-items"></div></div>'; 
            
        }
    
        
        return $rstr;
        
    }
    
    public function renderPageAdd() {
        $rstr = '';
        $rstr .= '<div class="option-add">';
        $rstr .= '<h3>Add Pages <span>&#9660;</span></h3>';
        
        $SQL = "SELECT page_id,page_url,page_name FROM fdcms_pages ORDER BY page_name ASC";
        $query = $this->db->query($SQL);
        $result = $query->result();
        
        $rstr .= '<div class="menu-pages">';
        foreach($result as $rs) {
            $rstr .= '<div class="menu-page-option">';
            $rstr .= '<input type="checkbox" name="" id="" class="menu-add-box" data-url="'.$rs->page_url.'" data-text="'.$rs->page_name.'" data-pageid="'.$rs->page_id.'">';
            $rstr .= $rs->page_name;
            $rstr .= '</div>';      
        }
        $rstr .= '</div>';
        $rstr .= '<div class="menu-pages-add">';
        $rstr .= '<input type="button" value="Add Pages" class="form-button-small trigger-add-pages">';
        $rstr .= '</div>';
        
        $rstr .= '</div>';   
        return $rstr;
    }
    
    public function renderManualAdd() {
        $rstr = '';
        $rstr .= '<div class="option-add">';
        $rstr .= '<h3>Add Text Link <span>&#9660;</span></h3>';
        $rstr .= '<div class="menu-links">';
            $rstr .= '<table width="100%" cellspacing="0" cellpadding="0">';
            $rstr .= '<tr><td width="100">';
            $rstr .= '<span class="menu-option-label">Link URL:</span> ';
            $rstr .= '</td><td>';
            $rstr .= '<div class="input-wrapper" style="margin-bottom: 6px;"><input type="text" name="menu_add_url" id="menu_add_url" value="" class="input-full"></div>';
            $rstr .= '</td></tr>';
            $rstr .= '<tr><td width="50">';
            $rstr .= '<span class="menu-option-label">Link Text:</span> ';
            $rstr .= '</td><td>';
            $rstr .= '<div class="input-wrapper"><input type="text" name="menu_add_text" id="menu_add_text" value="" class="input-full"></div>';
            $rstr .= '</td></tr>';
            $rstr .= '</table>';
        $rstr .= '</div>';
        $rstr .= '<div class="menu-pages-add">';
        $rstr .= '<input type="button" value="Add Link" class="form-button-small trigger-add-link">';
        $rstr .= '</div>';
        $rstr .= '</div>';   
        return $rstr;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function getMenuName($id) {
        $SQL = "SELECT * FROM fdcms_menus WHERE menu_id = '".$id."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        return $row->menu_name;
    }
    
    

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function editToolbox($id) {
        
        $toolboxArray = array();
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Menu Settings',
			'link' => '/admin/extras/menus/edit/'.$id.'',
			'icon' => 'widget-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Structure',
			'link' => '/admin/extras/menus/menuitems/'.$id.'',
			'icon' => 'edit-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
        
        return $toolboxArray;
    }

    
}
?>