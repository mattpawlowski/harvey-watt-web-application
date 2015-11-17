<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Fdcms {
    
    public $page_id;
    public $page_url;
    public $CI;
    
    
    /* ------------------------------------- */
    /* Construct                             */
    /* ------------------------------------- */
    public function __construct($params)
    {
        // Do something with $params
        $this->page_id = $params['page_id'];
        $this->page_url = $params['page_url'];
        $this->CI =   &get_instance();
    }
    
    
    /* ------------------------------------- */
    /* Meta Title                            */
    /* ------------------------------------- */
    public function meta_title() {
        $SQL = "SELECT page_meta_title FROM fdcms_pages WHERE page_id = '".$this->page_id."' LIMIT 1";
        $query = $this->CI->db->query($SQL);
        $row = $query->row();
        if($row->page_meta_title != '') {
            echo $row->page_meta_title;
        } else {   
            echo '<div class="fdcms-warning"><b>SYSTEM:</b> Please Enter and Save a <b>"'.$slug.'"</b> in your CMS System</div>';   
        }
    }
    
    
    /* ------------------------------------- */
    /* Meta Description                      */
    /* ------------------------------------- */
    public function meta_desc() {
        $SQL = "SELECT page_meta_desc FROM fdcms_pages WHERE page_id = '".$this->page_id."' LIMIT 1";
        $query = $this->CI->db->query($SQL);
        $row = $query->row();
        
        echo $row->page_meta_desc;
    }
    
    
    /* ------------------------------------- */
    /* Page Title                            */
    /* ------------------------------------- */
    public function the_title() {
        $SQL = "SELECT page_name FROM fdcms_pages WHERE page_id = '".$this->page_id."' LIMIT 1";
        $query = $this->CI->db->query($SQL);
        $row = $query->row();
        
        echo $row->page_name;
    }
    
    
    /* ------------------------------------- */
    /* Page Subtitle                         */
    /* ------------------------------------- */
    public function the_subtitle() {
        $SQL = "SELECT page_subtitle FROM fdcms_pages WHERE page_id = '".$this->page_id."' LIMIT 1";
        $query = $this->CI->db->query($SQL);
        $row = $query->row();
        
        echo $row->page_subtitle;
    }
    
    
    /* ------------------------------------- */
    /* Render Menus                          */
    /* Param 1: Slug Name                    */
    /* Param 2: Include <ul> wrapper         */
    /* Param 3: Class Names for <ul>         */
    /* ------------------------------------- */
    public function nav_menu($slug = '', $wrap = true, $class = 'nav-menu') {
        // Get Menu Via Slug
        $SQL = "SELECT menu_id FROM fdcms_menus WHERE menu_slug = '".$slug."' LIMIT 1";
        $query = $this->CI->db->query($SQL);
        $row = $query->row();
        $menuId = $row->menu_id;
        
        if($wrap == true) { echo '<ul class="'.$class.'">'; }
        echo $this->recurse_nav_menu($menuId);
        if($wrap == true) { echo '</ul>'; }
        
    }
    
    
            /* ------------------------------------- */
            /* Menu Recruse Function                 */
            /* Child Function for Render Menus       */
            /* ------------------------------------- */
            public function recurse_nav_menu($id, $parent = '0',$identity_count = 0) {
                $menuId = $id;        
                $rstr = '';
                
                $SQL = "SELECT * FROM fdcms_menus_items WHERE item_menu = '".$id."' AND item_parent_item = '".$parent."' ORDER BY sort_order ASC";
                $query = $this->CI->db->query($SQL);
                $count = $query->num_rows();
                $result = $query->result();
                
                if($count > 0) {
                    
                    if($parent != 0) {
                        $rstr .= '<ul>';
                    }
                    
                    foreach($result as $rs) {
                        
                        $rstr .= '<li id="nav-'.$rs->item_id.'">';
                        $identity_count ++;
                        $rstr .= '<a href="'.$rs->item_url.'">'.$rs->item_text.'</a>';                        
                            $SQL2 = "SELECT item_id FROM fdcms_menus_items WHERE item_menu = '".$menuId."' AND item_parent_item = '".$rs->item_identity."' LIMIT 1";
                            $subquery = $this->CI->db->query($SQL2);
                            $subcount = $subquery->num_rows();
                            if(($subcount > 0)) { $rstr .= $this->recurse_nav_menu($id, $rs->item_identity,$identity_count); }  
                                      
                        $rstr .= '</li>';
                        
                    }
                    $rstr .= '</ul>';
                    
                    
                } else {
                    // No Items                    
                }
            
                
                return $rstr;
            }
    
    
    /* ------------------------------------- */
    /* Slideshow Switch                      */
    /* ------------------------------------- */
    public function render_slideshow($type = 'flexslider') {
        
        $SQL = "SELECT * FROM fdcms_media_i2p i2p JOIN fdcms_media_images mi ON (i2p.image_id = mi.image_id) WHERE i2p.page_id = '".$this->page_id."' ORDER BY image_sort_order ASC";
        $query = $this->CI->db->query($SQL);
        $results = $query->result();
        $num = $query->num_rows();  
        
         switch($type) {
            case "flexslider":
                $this->render_flexslider($results,$num);
                break;
            case "parallax-slider":
                $this->render_parallaxSlider($results,$num);
                break;
            default: 
                echo '<div class="fdcms-warning"><b>SYSTEM:</b> Invalid Type Paramater was given to function render_gallery() :: "'.$type.'" is not a supported Slideshow Plugin</div>';
         }
    }
    
        /* ------------------------------------- */
        /* FLEXSLIDER Featured Images            */
        /* Supports Image Linking                */
        /* ------------------------------------- */
        public function render_flexslider($results,$num) {      
            $rstr = '';
            
            if($num == 0) {
                // No Featured Images
                $rstr .= '';
            
            } else if($num == 1) {
                // Single Image Result
                $rstr .= '<div id="images">';
                
                foreach($results as $rs) {
                $rstr .= '<img src="'.$rs->image_src.'">';	
                }
                
                $rstr .= '</div>';
            
            } else if($num >= 2) {
                
                // Slideshow Result	
                $rstr .= '<div class="featured flexslider" id="slideshow">';
                $rstr .= '<ul class="slides">';
                foreach($results as $rs) {
                    $rstr .= '<li>';
                    if($rs->image_link != '') { $rstr .= '<a href="'.$rs->image_link.'">'; }
                    $rstr .= '<img src="'.$rs->image_src.'">';
                    if($rs->image_link != '') { $rstr .= '</a>'; }
                    $rstr .= '</li>';
                }
                $rstr .= '</ul>';
                $rstr .= '</div>';
                
                $rstr .= '<link rel="stylesheet" type="text/css" href="/plugins/flexslider/flexslider.css">';
                $rstr .= '<script type="text/javascript" src="/plugins/flexslider/jquery.flexslider-min.js"></script>';
                $rstr .= '
                <script type="text/javascript">	
                $(window).load(function() {
                    $(\'.flexslider\').flexslider({
                        animation: "slide"
                    });
                });
                </script>
                ';
            }
            
            echo $rstr;
        }
    
        /* ------------------------------------- */
        /* PARALLAX SLIDER Featured Images       */
        /* Supports Image Linking                */
        /* Supports Title and Subtitle Fields    */
        /* ------------------------------------- */
        public function render_parallaxSlider($results,$num) {      
            $rstr = '';
            
            if($num == 0) {
                // No Featured Images
                $rstr .= '';
            
            } else if($num == 1) {
                // Single Image Result
                $rstr .= '<div id="images">';
                
                foreach($results as $rs) {
                $rstr .= '<img src="'.$rs->image_src.'">';	
                }
                
                $rstr .= '</div>';
            
            } else if($num >= 2) {
                
                // Slideshow Result	
                $rstr .= '<div class="responsive-slider" data-spy="responsive-slider" data-autoplay="true" data-interval="5000" data-transitiontime="300">';
                $rstr .= '<div class="slides" data-group="slides">';
                $rstr .= '<ul>';
                foreach($results as $rs) {
                    $rstr .= '<li>';
                    $rstr .= '<div class="slide-body" data-group="slide">';
                    $rstr .= '<img src="'.$rs->image_src.'">';
                    if($rs->image_link != '') { $rstr .= '<a href="'.$rs->image_link.'" class="slide-link"></a>'; }
                    $rstr .= '<div class="caption-wrapper">';
                    $rstr .= '<div class="caption rs-header" data-animate="slideAppearRightToLeft" data-delay="500" data-length="300">';
                        $rstr .= '<h2>'.$rs->image_title.'</h2>';
                    $rstr .= '</div>';   
                    $rstr .= '<div class="caption rs-subhead" data-animate="slideAppearLeftToRight" data-delay="800" data-length="300">';
                        $rstr .= '<h3>713.522.3101</h3>';
                    $rstr .= '</div>';   
                    $rstr .= '</div>';   
                    $rstr .= '</div>';
                    $rstr .= '</li>';
                }
                $rstr .= '</ul>';
                $rstr .= '</div>';
                $rstr .= '<a class="slider-control left" href="#" data-jump="prev">Prev</a>';
                $rstr .= '<a class="slider-control right" href="#" data-jump="next">Next</a>';
                $rstr .= '<div class="pages">';
                $i = 1;
                foreach($results as $rs) {
                    $rstr .= '<a class="page" href="#" data-jump-to="'.$i.'">'.$i.'</a>';    
                    $i++;
                }
                $rstr .= '</div>';
                $rstr .= '</div>';
                
                $rstr .= '
                <script type="text/javascript" src="/plugins/responsive-slider/js/jquery.event.move.js"></script>
                <script type="text/javascript" src="/plugins/responsive-slider/js/responsive-slider.min.js"></script>
                <link rel="stylesheet" type="text/css" href="/plugins/responsive-slider/css/responsive-slider.css">
                ';
                
                $rstr .= '
                <script type="text/javascript">	
                    $(document).ready(function() {
                       $(\'#responsive-slider\').responsiveSlider({
                            autoplay: true,
                            interval: 5000,
                            transitionTime: 300   
                       });
                    });
                </script>';
            }
            
            echo $rstr;
        }
    
    
    /* ------------------------------------- */
    /* HTML Block Render                     */
    /* ------------------------------------- */
    public function html_block($slug = '') {
        $SQL = "SELECT page_content FROM fdcms_pages WHERE page_id = '".$this->page_id."' LIMIT 1";
        $query = $this->CI->db->query($SQL);
        $row = $query->row();
        $content_json = $row->page_content;
        
        $content_array = json_decode($content_json,true);
        $format_slug = str_replace(' ','_',$slug);
        if(array_key_exists($format_slug,$content_array)) {
            $content_final = urldecode($content_array[$format_slug]);
            echo $content_final;
        } else {
            // Option 'please set content' message
            echo '<div class="fdcms-warning"><b>SYSTEM:</b> Please Enter and Save content the block <b>"'.$slug.'"</b> in your CMS System</div>';   
        }
    }
    
    
    /* ------------------------------------- */
    /* Gallery Switch                        */
    /* ------------------------------------- */
    public function render_gallery($slug = '',$type = 'megafolio') {
        
        if($slug == '') {
            echo '<div class="fdcms-warning"><b>SYSTEM:</b> No Slug Given - Please provide the gallery slug to function render_gallery()</div>';
            exit();   
        }
        
        // Get the Gallery ID
        $SQL = "SELECT gallery_id, gallery_slug FROM fdcms_media_galleries WHERE gallery_slug = '".$slug."' LIMIT 1";
        $query = $this->CI->db->query($SQL);
        $row = $query->row();
        $gallery_id = $row->gallery_id;
        
        
        // Get the Gallery Images
        $SQL2 = "SELECT * FROM fdcms_media_images i JOIN fdcms_media_i2g i2g ON (i.image_id = i2g.image_id) WHERE i2g.gallery_id = '".$gallery_id."' ORDER BY image_sort_order ASC";
        $query2 = $this->CI->db->query($SQL2);
        $result = $query2->result();
        
         switch($type) {
            // Content Blocks Dialog
            case "megafolio":
                $this->render_megafolio($result);
                break;
            default: 
                echo '<div class="fdcms-warning"><b>SYSTEM:</b> No Type Paramater was given to function render_gallery()</div>';
         }
    }
    
    
        /* ------------------------------------- */
        /* MEGAFOLIO Gallery                     */
        /* ------------------------------------- */
        public function render_megafolio($array) {
            $rstr = '';
            $rstr .= '<div class="megafolio-container">';
            $count = 1;
            foreach($array as $item) {
                $rstr .= '<div class="mega-entry cat-all" id="mega-entry-'.$count.'" data-src="'.$item->image_src.'" data-width="'.$item->image_width.'" data-height="'.$item->image_height.'">';
                $rstr .= '<a class="fancybox" rel="group" href="'.$item->image_src.'">';
                $rstr .= '<div class="mega-hover">';
                    $rstr .= '<div class="mega-hovertitle">'.$item->image_title.'</div>';
                    if($item->image_link != '') { 
                        $rstr .= '<a href="'.$item->image_link.'"><div class="mega-hoverlink"></div></a>';
                        $rstr .= '<a class="fancybox" rel="group" href="'.$item->image_src.'"><div class="mega-hoverview"></div></a>';
                    }
                $rstr .= '</div>';
                $rstr .= '</a>';
                $rstr .= '</div>';
                $count++;
            }
            
            $rstr .= '</div>';
            
            $rstr .= '<script type="text/javascript" src="/plugins/megafolio/js/jquery.themepunch.plugins.min.js"></script>';
            $rstr .= '<script type="text/javascript" src="/plugins/megafolio/js/jquery.themepunch.megafoliopro.js"></script>';
            $rstr .= '<script type="text/javascript" src="/plugins/megafolio/fancybox/jquery.fancybox.pack.js"></script>';
            $rstr .= '<script type="text/javascript" src="/plugins/megafolio/fancybox/helpers/jquery.fancybox-media.js"></script>';
            
            $rstr .= '<link rel="stylesheet" type="text/css" href="/plugins/megafolio/css/settings.css" media="screen" />';
            $rstr .= '<link rel="stylesheet" href="/plugins/megafolio/fancybox/jquery.fancybox.css" type="text/css" media="screen" />';
            
            $rstr .= '
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    var api=jQuery(\'.megafolio-container\').megafoliopro({
                        filterChangeAnimation:"pagetop",
                        filterChangeSpeed:600,
                        filterChangeRotate:99,
                        filterChangeScale:0.6,          
                        delay:20,
                        paddingHorizontal:10,
                        paddingVertical:10,
                        layoutarray:[9,6,3,6,0,4,13]
                    });
                });
                jQuery(".fancybox").fancybox({
                 openEffect  : \'none\',
                 closeEffect : \'none\',
                 helpers : {
                             media : {}
                            }
            });
            </script>
            ';
            
            echo $rstr;
        }
    
    
    /* ------------------------------------- */
    /* Default Copyright Statement           */
    /* ------------------------------------- */
    public function copyright($str = 'My Default Company') {
       echo '&copy; Copyright '.date('Y').' '.$str;
    }
    
    
    /* ------------------------------------- */
    /* Atlanta Web Design                    */
    /* ------------------------------------- */
    public function firm_copy($location = 'Atlanta') {
       echo '<a href="http://www.firmdesign.com">'.$location.' Web Design</a> by FirmDesign';
    }
    
    
    
    /* ------------------------------------- */
    /* Map Switch                            */
    /* ------------------------------------- */
    public function render_map($slug = '') {
        
        if($slug == '') {
            echo '<div class="fdcms-warning"><b>SYSTEM:</b> No Slug Given - Please provide the Map slug to function render_map()</div>';
        } else {
        
            // Get the Gallery ID
            $SQL = "SELECT map_id, map_slug FROM fdcms_locations_maps WHERE map_slug = '".$slug."' LIMIT 1";
            $query = $this->CI->db->query($SQL);
            $row = $query->row();
            $map_id = $row->map_id;
            
            
            // Get the Map
            $SQL2 = "SELECT * FROM fdcms_locations_maps WHERE map_id = '".$map_id."' LIMIT 1";
            $query2 = $this->CI->db->query($SQL2);
            $row2 = $query2->row();
            $map_type = $row2->map_type;
            $map_zoom = $row2->map_zoom;
            $map_center = $row2->map_center;
            $map_styles = $row2->map_styles;
            
            // Get our API Key
            $SQL3 = "SELECT * FROM fdcms_locations_api WHERE setting_key = 'api_key' LIMIT 1";
            $query3 = $this->CI->db->query($SQL3);
            $row3 = $query3->row();
            $api_key = $row3->setting_value;
            
             switch($map_type) {
                // Content Blocks Dialog
                case "cat":
                    $this->render_category_map($map_id,$map_zoom,$map_center,$map_styles,$api_key);
                    break;
                case "loc":
                    $SQL4 = "SELECT * FROM fdcms_locations l JOIN fdcms_locations_l2m l2m ON (l2m.location_id = l.location_id) WHERE l2m.map_id = '".$map_id."'";
                    $query4 = $this->CI->db->query($SQL4);
                    $result4 = $query4->result();
                    $this->render_location_map($map_id,$map_zoom,$map_center,$map_styles,$result4,$api_key);
                    break;
                default: 
                    echo '<div class="fdcms-warning"><b>SYSTEM:</b> No Type Paramater was given to function render_map()</div>';
             }
         
        }
    }
    
    
    
        /* ------------------------------------- */
        /* MAP: Location Map                     */
        /* ------------------------------------- */
        public function render_location_map($map_id,$map_zoom,$map_center,$map_styles,$locations,$api_key) {
            $data = array(
                'map_id' => $map_id,
                'map_zoom' => $map_zoom,
                'map_center' => $map_center,
                'map_styles' => $map_styles
            );
            
            $rstr = '';
            
            foreach($locations as $rs) {
                $rstr .= '<input type="hidden" name="location-'.$rs->location_id.'" id="location-'.$rs->location_id.'" class="marker-add" data-id="'.$rs->location_id.'" data-text="'.$rs->location_name.'" data-address="'.$rs->location_street.' '.$rs->location_city.' '.$rs->location_state.' '.$rs->location_zip.'">';
            }
            
            
            $rstr .= $this->CI->load->view('mapsAPI/dynamic-map-loc',$data,true);
            $rstr .= '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key='.$api_key.'&sensor=false"></script>';
            $rstr .= '<script type="text/javascript" src="/js/maps/infobubble.js"></script>';
            $rstr .= '<div id="map_canvas" style="width: 100%; height: 400px; z-index: 99;"></div>';
            $rstr .= '<script type="text/javascript" src="/js/maps/location-map.js"></script>';
            $rstr .= '<script type="text/javascript">initialize();</script>';
            
            echo $rstr;
        }
    
    
    
        /* ------------------------------------- */
        /* MAP: Category Map                     */
        /* ------------------------------------- */
        public function render_category_map($map_id,$map_zoom,$map_center,$map_styles,$api_key) {
            $data = array(
                'map_id' => $map_id,
                'map_zoom' => $map_zoom,
                'map_center' => $map_center,
                'map_styles' => $map_styles
            );   
            
            $rstr = '';         
            
            $rstr .= $this->CI->load->view('mapsAPI/dynamic-map-cat',$data,true);
            $rstr .= '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key='.$api_key.'&sensor=false"></script>';
            $rstr .= '<script type="text/javascript" src="/js/maps/infobubble.js"></script>';
            $rstr .= '<div id="map_canvas" style="width: 100%; height: 400px; z-index: 99;"></div>';
            $rstr .= '<script type="text/javascript" src="/js/maps/category-map.js"></script>';
            $rstr .= '<script type="text/javascript">initialize();</script>';
            
            echo $rstr;
        }
    
    
    
        /* ------------------------------------- */
        /* MAP: Category Map Controller          */
        /* ------------------------------------- */
        public function render_category_controller($slug = '') {
            if($slug == '') {
                echo '<div class="fdcms-warning"><b>SYSTEM:</b> No Slug Given - Please provide the Map slug to function render_category_controller()</div>';
            } else {
                // Get the Gallery ID
                $SQL = "SELECT map_id, map_slug FROM fdcms_locations_maps WHERE map_slug = '".$slug."' LIMIT 1";
                $query = $this->CI->db->query($SQL);
                $row = $query->row();
                $map_id = $row->map_id;
                
                $SQL = "SELECT * FROM fdcms_locations_categories lc JOIN fdcms_locations_c2m c2m ON (c2m.category_id = lc.category_id) WHERE c2m.map_id = '".$map_id."'";
                $query = $this->CI->db->query($SQL);
                $categories = $query->result();
                
                $rstr = '';
                
                $rstr .= '<ul class="map_category_list">';
                
                foreach($categories as $rs) {
                    $rstr .= $this->render_category_locations($rs);  
                }
                
                $rstr .= '</ul>';
                
                echo $rstr;
            }
        
        }
    
    
    
        /* ------------------------------------- */
        /* MAP: Category Map  Category Locations */
        /* ------------------------------------- */
        public function render_category_locations($data) {
            
            $rstr = '';
            
            $rstr .= '<li>';
            $rstr .= '<a href="javascript: void(0);" id="'.$data->category_id.'" class="category-trigger">'.$data->category_name.'</a>';
            
            $SQL = "SELECT * FROM fdcms_locations l JOIN fdcms_locations_l2c l2c ON (l2c.location_id = l.location_id) WHERE l2c.category_id = '".$data->category_id."'";
            $query = $this->CI->db->query($SQL);
            $locations = $query->result();
            
            if($query->num_rows() > 0) {
                $rstr .= '<ul class="map_location_list" style="display: none;">';
                
                foreach($locations as $rs) {
                    $rstr .= '<li id="location-'.$rs->location_id.'" class="marker-add" data-category="'.$rs->category_id.'" data-id="'.$rs->location_id.'" data-text="'.$rs->location_name.'" data-address="'.$rs->location_street.' '.$rs->location_city.' '.$rs->location_state.' '.$rs->location_zip.'">'.$rs->location_name.'</li>'; 
                }
                
                $rstr .= '</ul>';
            } else {
                
            }
            
            $rstr .= '</li>';
            
            return $rstr;
            
        }
    
    
    
    /* ------------------------------------- */
    /* Render Form                           */
    /* ------------------------------------- */
    public function render_form($id = '', $class = 'page-form') {
        
        if($id == '') {
            echo '<div class="fdcms-warning"><b>SYSTEM:</b> No Form ID Given - Please provide the Form ID to function render_form()</div>';
        } else {            
            
            // Has Our Form Been Sent?
            if($this->CI->session->flashdata('sent')) {
                $form_sent = 'true';    
            } else {
                $form_sent = 'false';
            }
            
             switch($form_sent) {
                // Content Blocks Dialog
                case "true":
                    $this->handle_form_response($id);
                    break;
                case "false":                    
                    // Get our Form Fields
                    $form_data = $this->CI->session->flashdata('form_data');
                    $SQL = "SELECT * FROM fdcms_forms_fields f JOIN fdcms_forms_f2f f2f ON (f2f.field_id = f.field_id) WHERE f2f.form_id = '".$id."' ORDER BY sort_order ASC";
                    $query = $this->CI->db->query($SQL);
                    $results = $query->result();
                    $this->render_form_fields($id,$results,$form_data,$class);
                    break;
                default: 
                    echo '<div class="fdcms-warning"><b>ERROR:</b> Could not read formSent Parameter in function render_map()</div>';
             }
         
        }
    }
    
    
    
        /* ------------------------------------- */
        /* Fender Our Form                       */
        /* ------------------------------------- */
        public function render_form_fields($form_id,$fields,$form_data,$class) {
            $rstr = '';
            $rstr .= '<div class="'.$class.'">';
            $rstr .= '<form action="/x-form/'.$form_id.'" method="POST" enctype="multipart/form-data" name="form-'.$form_id.'" id="form-'.$form_id.'">';
            
            foreach($fields as $rs) {
                $field_id       = $rs->field_id;
                $field_type     = $rs->field_type;
                $field_label    = $rs->field_label;
                $field_options  = $rs->field_options;
                $field_required = $rs->field_required;
                $sort_order     = $rs->sort_order;
                
                $formValue = $form_data['form-'.$form_id.'-field-'.$field_id.'-value'];
                $formError = $form_data['form-'.$form_id.'-field-'.$field_id.'-error'];
            
                $rstr .= '<div class="form-row">';      
                $rstr .= '<label for="form-'.$form_id.'-field-'.$field_id.'">';
                $rstr .= '<span class="form-label">';
                $rstr .= $field_label;
                if($field_required == '1') {
                    $rstr .= ' <span class="form-req">*</span>';   
                }
                $rstr .= '</span>';
                $rstr .= '<div class="input-wrapper">';
                
                    // OUTPUT FORM FIELD
                    // -------------------------------------
                    switch($field_type) {
                        case "text":
                            $rstr .= '<input type="text" name="form-'.$form_id.'-field-'.$field_id.'" id="form-'.$form_id.'-field-'.$field_id.'" value="'.$formValue.'" class="std-input input-full">';
                            break;
                        case "textarea":
                            $rstr .= '<textarea name="form-'.$form_id.'-field-'.$field_id.'" id="form-'.$form_id.'-field-'.$field_id.'" class="std-multi text-full">'.$formValue.'</textarea>';
                            break;
                        case "tel":
                            $rstr .= '<input type="tel" name="form-'.$form_id.'-field-'.$field_id.'" id="form-'.$form_id.'-field-'.$field_id.'" value="'.$formValue.'" class="std-input phone-mask input-full">';
                            break;
                        case "email":
                            $rstr .= '<input type="email" name="form-'.$form_id.'-field-'.$field_id.'" id="form-'.$form_id.'-field-'.$field_id.'" value="'.$formValue.'" class="std-input email-mask input-full">';
                            break;
                        default:
                            $rstr .= '<div class="fdcms-warning"><b>ERROR:</b> Unknown Field Type for FIELD ['.$field_id.']</div>';
                    }
                    // -------------------------------------
                    // OUTPUT FORM FIELD
                    
                $rstr .= '</div>';
                $rstr .= '<div class="form-error" id="form-'.$form_id.'-field-'.$field_id.'-error">'.$formError.'</div>';
                $rstr .= '</label>'; 
                $rstr .= '<div class="clear"></div>';
                $rstr .= '</div>'; 
            }
            
            $rstr .= '<div class="form-row"><label for="form-'.$form_id.'-submit"><span></span><input type="submit" name="form-'.$form_id.'-submit" id="form-'.$form_id.'-submit" class="std-button input-button" value="Submit"></label><div class="clear"></div></div>';
            $rstr .= '</form>';
            $rstr .= '</div>';
            echo $rstr;
        }
    
    
    
        /* ------------------------------------- */
        /* Handle Thank You                      */
        /* ------------------------------------- */
        public function handle_form_response($form_id) {
            echo 'handling form response - form has been sent';
        }
    
    
    
    /* ------------------------------------- */
    /* Floorplan Slider                      */
    /* ------------------------------------- */
    public function floorplans_slider($class = 'flexslider') {
        
        $SQL = "SELECT * FROM fdcms_floorplans f INNER JOIN fdcms_floorplans_f2c f2c ON (f.floorplan_id = f2c.floorplan_id) INNER JOIN fdcms_floorplans_categories fc ON (f2c.category_id = fc.category_id) ORDER BY f2c.category_id, f.floorplan_sort_order";
        $query = $this->CI->db->query($SQL);
        $result = $query->result();
        
        $rstr = '';
        
        $rstr .= '<div class="flexslider floorplans-slider" style="border: 8px solid #fff;">';
        $rstr .= '<ul class="slides">';
        
        foreach($result as $rs) {
            
            if($rs->floorplan_br > 1) { $bp = 's'; } else { $bp = ''; }
            if($rs->floorplan_ba > 1) { $bap = 's'; } else { $bap = ''; }
            
            $rstr .= '<li>';
            $rstr .= '<img src="'.$rs->floorplan_image.'">';
            $rstr .= '<p class="flex-caption">';
            $rstr .= $rs->floorplan_name;
                if($rs->floorplan_br == 99 && $rs->floorplan_ba == 99) { $rstr .= ' | Efficiency'; }
                else if($rs->floorplan_br == 0 && $rs->floorplan_ba == 0) { $rstr .= ' | Studio'; }
                else { $rstr .= ' | '.$rs->floorplan_br.' Bed'.$bp.', '.$rs->floorplan_ba.' Bath'.$bap; }
            $rstr .= ' | '.$rs->floorplan_sf.' sf';
            $rstr .= '</p>';
            $rstr .= '</li>';   
        }
        $rstr .= '</ul>';
        $rstr .= '</div>';
        
        echo $rstr;
        
    }
    
    
    
    /* ------------------------------------- */
    /* Floorplan Controller                  */
    /* ------------------------------------- */
    public function floorplans_slider_control($categorize = true) {
        // Get our categories
        $SQL = "SELECT * FROM fdcms_floorplans f INNER JOIN fdcms_floorplans_f2c f2c ON (f.floorplan_id = f2c.floorplan_id) INNER JOIN fdcms_floorplans_categories fc ON (f2c.category_id = fc.category_id) ORDER BY f2c.category_id, f.floorplan_sort_order";
        $query = $this->CI->db->query($SQL);
        $result = $query->result();
        
        $rstr = '';
        $current_category = '';
        $count = 1;
        foreach($result as $rs) {
            if($current_category != $rs->category_id) { 
                $rstr .= '<h3>'.$rs->category_name.'</h3>';
                if($current_category != '') { $rstr.= '</ul>'; }
                $rstr .= '<ul>';
                $current_category = $rs->category_id;
            }
            
            if($rs->floorplan_br > 1) { $bp = 's'; } else { $bp = ''; }
            if($rs->floorplan_ba > 1) { $bap = 's'; } else { $bap = ''; }
            
            $rstr .= '<li>';
            $rstr .= '<a href="javascript: void(0);" alt="'.$count.'" class="sliderlink">';
            $rstr .= $rs->floorplan_name;
                if($rs->floorplan_br == 99 && $rs->floorplan_ba == 99) { $rstr .= ' | Efficiency'; }
                else if($rs->floorplan_br == 0 && $rs->floorplan_ba == 0) { $rstr .= ' | Studio'; }
                else { $rstr .= ' | '.$rs->floorplan_br.' Bed'.$bp.', '.$rs->floorplan_ba.' Bath'.$bap; }
            $rstr .= ' | '.$rs->floorplan_sf.' sf';
            $rstr .= '</a>';
            $rstr .= '</li>';
            
            $count++;
        }
        $rstr .= '</ul>';
        
        echo $rstr;
        
    }
    
            
            
}

?>