<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Floorplans_model extends CI_Model {
    
    public $primary_table;
    public $path;
	
    
	// ----------------------------------- //
	// Initialize Our Primary Table
	// ----------------------------------- //
	public function initialize($table,$path) {
		$this->primary_table = $table;	
        $this->path = $path;
	}
    
    public function renderTable() {
        $rstr = '';
        
        $rstr .= '<table class="fdcms-table sortable" width="100%" cellpadding="0" cellspacing="0">';
        $rstr .= $this->renderTableRows();
        
        $rstr .= '<tr>';
        $rstr .= '<td colspan="2">';
        $rstr .= '<img src="/images/app/core/arrow_ltr.png"> <a href="javascript: void(0);" class="check-all edit-link">Check</a> / <a href="javascript: void(0);" class="uncheck-all delete-link">Uncheck</a> All';
        $rstr .= '<a href="javascript:void(0);" class="delete delete-selected" data-post="'.$this->path.'/delete">Delete Selected</a>';
        $rstr .= '</td>';
        $rstr .= '</tr>';
        
        $rstr .= '</table>';
        
        $rstr .= '
            <script>
            var fixHelper = function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            };                
            $(".sortable tbody").sortable({
                helper: fixHelper,
                update: function(event, ui) {
                    var newOrder = $(this).sortable(\'toArray\').toString();
                    $.post(\'/admin/floorplans/all/order\', {order:newOrder});
                }
            }).disableSelection();
            </script>
        ';
        
        return $rstr;
    }

	// ----------------------------------- //
	// Row output for pages table
	// ----------------------------------- //
    public function renderTableRows() {
        $rstr = '';
        
        $this->db->from($this->primary_table);
        $this->db->order_by("floorplan_sort_order", "asc");
        $query = $this->db->get();
        $count = $query->num_rows();
        $result = $query->result();
        
        if($count > 0) {
        
        foreach($result as $rs) {
            
            $class = ' class="native"';  
            
            $rstr .= '<tr id="'.$rs->floorplan_id.'">';
            
            // delete checkbox
            $rstr .= '<td width="20"'.$class.' valign="top">';
            $rstr .= '<input type="checkbox" name="'.$rs->floorplan_id.'" value="'.$rs->floorplan_id.'" id="box_'.$rs->floorplan_id.'" class="del-box" data-id="'.$rs->floorplan_id.'" data-post="'.$this->path.'/delete/'.$rs->floorplan_id.'">';
            $rstr .= '</td>';
            
            $rstr .= '<td'.$class.'>';
            $rstr .= '<div class="options-hover">';
            $rstr .= '<img src="'.$rs->floorplan_image.'" width="100" style="float: right; margin: 8px;">';
            $rstr .= '<b>'.$rs->floorplan_name.'</b>';
                if($rs->floorplan_price != 0) { $rstr .= ' | $'.money_format($rs->floorplan_price,2).'/month'; }
                if($rs->floorplan_br == 99 && $rs->floorplan_ba == 99) { $rstr .= ' | Efficiency'; }
                else if($rs->floorplan_br == 0 && $rs->floorplan_ba == 0) { $rstr .= ' | Studio'; }
                else { $rstr .= ' | '.$rs->floorplan_br.' Bed(s), '.$rs->floorplan_ba.' Bath(s)'; }
                if($rs->floorplan_sf != 0) { $rstr .= ' | '.$rs->floorplan_sf.' sf'; }
            
            $rstr .= '<span class="row-options">';
            $rstr .= '<a href="'.$this->path.'/edit/'.$rs->floorplan_id.'" class="edit-link">Edit</a>';
            $rstr .= ' | <a href="javascript:void(0)" class="delete-link delete-item" data-id="'.$rs->floorplan_id.'" data-post="'.$this->path.'/delete/'.$rs->floorplan_id.'">Delete</a>';
            $rstr .= '</span>';
            $rstr .= '<div class="clear"></div>';
            $rstr .= '</div>';
            $rstr .= '</td>';
            
            $rstr .= '</tr>';  
        }
        
        } else {
            $rstr .= '<tr><td>';
            $rstr .= '<div class="form-row"><div class="input-wrapper warning-text"><img src="/images/app/icons/warning.png" class="warning-icon"> <i>You haven\'t created any Floorplans to your website yet. Start by clicking "Create New Floorplan" on the right.</b></i><img src="/images/app/icons/help.png" class="help-icon help" data-subject="add-menus"></div></div>';   
            $rstr .= '</td></tr>';
        }
        
        return $rstr;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function create() {
        
        $data = array(
            'floorplan_id' => '0',
            'floorplan_name' => '',
            'floorplan_price' => '',
            'floorplan_br' => '',
            'floorplan_ba' => '',
            'floorplan_sf' => '',
            'floorplan_image' => '',
            'floorplan_sort_order' => '0'
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/floorplanEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function edit() {
        $id = $this->uri->segment(5);
        
        $SQL = "SELECT * FROM fdcms_floorplans WHERE floorplan_id = '".$id."' LIMIT 1";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        $data = array(
            'floorplan_id' => $row->floorplan_id,
            'floorplan_name' => $row->floorplan_name,
            'floorplan_price' => $row->floorplan_price,
            'floorplan_br' => $row->floorplan_br,
            'floorplan_ba' => $row->floorplan_ba,
            'floorplan_sf' => $row->floorplan_sf,
            'floorplan_image' => $row->floorplan_image
        );
        
        $rstr = '';
        $rstr .= '<div class="content-container">';
        $rstr .= $this->load->view('appForms/floorplanEditForm', $data, true);
        $rstr .= '</div>';
        
        return $rstr;   
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function save($image = '') {
        $id = $this->uri->segment(5);
        
		if($image == '') {
			$image = $this->input->post('floorplan_image_current');	
		} else {
            $this->core_model->deleteFile($this->input->post('floorplan_image_current'));   
        }
        
        $data = array(
            'floorplan_id' => $this->input->post('floorplan_id'),
            'floorplan_name' => $this->input->post('floorplan_name'),
            'floorplan_price' => $this->input->post('floorplan_price'),
            'floorplan_br' => $this->input->post('floorplan_br'),
            'floorplan_ba' => $this->input->post('floorplan_ba'),
            'floorplan_sf' => $this->input->post('floorplan_sf'),
            'floorplan_image' => $image
        );
        
        
        if($id == 0) {          
            $this->db->insert('fdcms_floorplans',$data);
			$return["id"] = $this->db->insert_id();
            $return["message"] = "The New Floorplan [ ".$data["floorplan_name"]." ] was created successfully.";
        } else {
            // Saving an existing Page    
            $this->db->where('floorplan_id',$id);
			$this->db->update('fdcms_floorplans',$data); 
            $return["id"] = $id;
            $return["message"] = "Your changes have been saved.";
        }
        
        return $return;
    }

	// ----------------------------------- //
	// Create New Page Function
	// ----------------------------------- //
    public function getFloorplanName($id) {
        $SQL = "SELECT * FROM fdcms_floorplans WHERE floorplan_id = '".$id."'";
        $query = $this->db->query($SQL);
        $row = $query->row();
        
        return $row->floorplan_name;
    }

	// ----------------------------------- //
	// Saves and Creates pages
	// ----------------------------------- //
    public function assign() {
        $id = $this->uri->segment(5);
        $rstr = '';
        
        $rstr .= '<input type="hidden" name="floorplan_id" id="floorplan_id" value="'.$id.'">';
        
        // Get our Assignments
        $SQL = "SELECT * FROM fdcms_floorplans f JOIN fdcms_floorplans_f2c f2c ON( f.floorplan_id = f2c.floorplan_id ) JOIN fdcms_floorplans_categories fc ON (f2c.category_id = fc.category_id)  WHERE f2c.floorplan_id = '".$id."'";
        $query = $this->db->query($SQL);
        $results = $query->result();        
        $contained = array();  
            $rstr .= '<div class="sortable-list" style="float: right;">';        
            $rstr .= '<h3>Assigned To</h3>';
            $rstr .= '<div class="sortable-menu">';
            $rstr .= '<ul id="sortable1" class="connectedSortable box-ul">';    
            foreach($results as $rs) {
                $contained[] = $rs->category_id;
                $rstr .= '<li class="ui-state-default box-li" data-id="'.$rs->category_id.'">'.$rs->category_name.'</li>';              
            }
            $rstr .= '</ul>';
            $rstr .= '</div>';
            $rstr .= '</div>';
        
        // Get our list of unassigned
        $SQL2 = "SELECT * FROM fdcms_floorplans_categories";
        if(!empty($contained)) { $SQL2 .= " WHERE category_id NOT IN (".implode(",",$contained).")"; }
        $query2 = $this->db->query($SQL2);
        $results2 = $query2->result();
        $unassigned = array();
            $rstr .= '<div class="sortable-list" style="float: left;">';        
            $rstr .= '<h3>Available Categories</h3>';
            $rstr .= '<div class="sortable-menu">';
            $rstr .= '<ul id="sortable2" class="connectedSortable box-ul">';   
            foreach($results2 as $rs) {
                $unassigned[] = $rs->category_id;
                $rstr .= '<li class="ui-state-default box-li" data-id="'.$rs->category_id.'">'.$rs->category_name.'</li>';              
            }
            $rstr .= '</ul>';
            $rstr .= '</div>';
            $rstr .= '</div>';
            
            $rstr .= '<div class="clear"></div>';
            
            $rstr .= '
            <script>
            $(function() {
                $( "#sortable1, #sortable2" ).sortable({
                  connectWith: ".connectedSortable"
                }).disableSelection();
            });
            </script>
            ';
        
        return $rstr;   
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
			'name' => 'Floorplan Info',
			'link' => '/admin/floorplans/all/edit/'.$id.'',
			'icon' => 'edit-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
		
		// Toolbox Item
		// ------------------------------------- //
		$addtool = '';
		$addtool = array(
			'name' => 'Assignments',
			'link' => '/admin/floorplans/all/assign/'.$id.'',
			'icon' => 'widget-icon.png',
            'class' => 'default'
		);
		array_push($toolboxArray, $addtool);
        
        return $toolboxArray;
    }
    
    
    
    
}

?>