<?php 
if (!class_exists('MSDChapterCPT')) {
	class MSDChapterCPT {
		//Properties
		var $cpt = 'chapter';
		//Methods
	    /**
	    * PHP 4 Compatible Constructor
	    */
		public function MSDChapterCPT(){$this->__construct();}
	
		/**
		 * PHP 5 Constructor
		 */
		function __construct(){
			global $current_screen;
        	//"Constants" setup
        	$this->plugin_url = plugin_dir_url('msd-custom-cpt/msd-custom-cpt.php');
        	$this->plugin_path = plugin_dir_path('msd-custom-cpt/msd-custom-cpt.php');
			//Actions
            add_action( 'init', array(&$this,'register_tax_chapters') );
            add_action( 'init', array(&$this,'register_cpt_chapter') );
            
			add_action('admin_head', array(&$this,'plugin_header'));
			add_action('admin_print_scripts', array(&$this,'add_admin_scripts') );
			add_action('admin_print_styles', array(&$this,'add_admin_styles') );
			// important: note the priority of 99, the js needs to be placed after tinymce loads
			add_action('admin_print_footer_scripts',array(&$this,'print_footer_scripts'),99);
            
            add_action('genesis_entry_header',array(&$this,'remove_post_meta'));
            add_filter( 'genesis_cpt_archive_intro_text_output', 'do_shortcode' );
            
            add_shortcode('book-menu',array(&$this,'book_menu_shortcode_handler'));
            
		}
		
		
        public function register_tax_chapters() {
        
            $labels = array( 
                'name' => _x( 'Chapters', 'chapters' ),
                'singular_name' => _x( 'Chapter', 'chapters' ),
                'search_items' => _x( 'Search chapters', 'chapters' ),
                'popular_items' => _x( 'Popular chapters', 'chapters' ),
                'all_items' => _x( 'All chapters', 'chapters' ),
                'parent_item' => _x( 'Parent chapter', 'chapters' ),
                'parent_item_colon' => _x( 'Parent chapter:', 'chapters' ),
                'edit_item' => _x( 'Edit chapter', 'chapters' ),
                'update_item' => _x( 'Update chapter', 'chapters' ),
                'add_new_item' => _x( 'Add new chapter', 'chapters' ),
                'new_item_name' => _x( 'New chapter name', 'chapters' ),
                'separate_items_with_commas' => _x( 'Separate chapters with commas', 'chapters' ),
                'add_or_remove_items' => _x( 'Add or remove chapters', 'chapters' ),
                'choose_from_most_used' => _x( 'Choose from the most used chapters', 'chapters' ),
                'menu_name' => _x( 'Chapters', 'chapters' ),
            );
        
            $args = array( 
                'labels' => $labels,
                'public' => true,
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => false,
                'hierarchical' => true, //we want a "category" style taxonomy, but may have to restrict selection via a dropdown or something.
        
                'rewrite' => array('slug'=>'chapter','with_front'=>false),
                'query_var' => true
            );
        
            register_taxonomy( 'chapters', array($this->cpt), $args );
        }
		
		function register_cpt_chapter() {
		
		    $labels = array( 
		        'name' => _x( 'Chapters', 'chapter' ),
		        'singular_name' => _x( 'Chapter', 'chapter' ),
		        'add_new' => _x( 'Add New', 'chapter' ),
		        'add_new_item' => _x( 'Add New Chapter', 'chapter' ),
		        'edit_item' => _x( 'Edit Chapter', 'chapter' ),
		        'new_item' => _x( 'New Chapter', 'chapter' ),
		        'view_item' => _x( 'View Chapter', 'chapter' ),
		        'search_items' => _x( 'Search Chapter', 'chapter' ),
		        'not_found' => _x( 'No chapter found', 'chapter' ),
		        'not_found_in_trash' => _x( 'No chapter found in Trash', 'chapter' ),
		        'parent_item_colon' => _x( 'Parent Chapter:', 'chapter' ),
		        'menu_name' => _x( 'Chapter', 'chapter' ),
		    );
		
		    $args = array( 
		        'labels' => $labels,
		        'hierarchical' => false,
		        'description' => 'Chapter',
		        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'revisions','genesis-cpt-archives-settings'),
		        'taxonomies' => array('chapter'),
		        'public' => true,
		        'show_ui' => true,
		        'show_in_menu' => true,
		        'menu_position' => 20,
		        
		        'show_in_nav_menus' => true,
		        'publicly_queryable' => true,
		        'exclude_from_search' => true,
		        'has_archive' => true,
		        'query_var' => true,
		        'can_export' => true,
		        'rewrite' => array('slug'=>'book','with_front'=>false),
		        'capability_type' => 'post'
		    );
		
		    register_post_type( $this->cpt, $args );
        
		}
		
		function plugin_header() {
			global $post_type;
		}
		 
		function add_admin_scripts() {
			global $current_screen;
			if($current_screen->post_type == $this->cpt){
			}
		}

        function add_admin_styles() {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
            }
        }   
			
		function print_footer_scripts()
		{
			global $current_screen;
			if($current_screen->post_type == $this->cpt){
				?><script type="text/javascript">
                    jQuery(function($){
                    });
                 </script><?php
			}
		}
		
        function remove_post_meta(){
            global $post;
            if($post->post_type == $this->cpt){
               remove_action('genesis_entry_header','genesis_post_info',12);
            }
        }   
        
        function book_menu_shortcode_handler($atts){
            $args = array(
                'posts_per_page'   => -1,
                'orderby'          => 'date',
                'order'            => 'DESC',
                'post_type'        => $this->cpt,
            );
            $posts_array = get_posts( $args );
            foreach($posts_array AS $p){
                $ret .= '<li class="menu-item"><a href="'.get_the_permalink($p->ID).'">'.get_the_title($p->ID).'</a></li>';
            }
            return $ret;
        }
  } //End Class
} //End if class exists statement
