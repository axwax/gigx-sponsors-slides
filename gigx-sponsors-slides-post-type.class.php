<?php
/*
 * Set values for post type
 */
class GIGX_Sponsors_Slides_Post_Type {
  	var $post_type_name = 'gigx_sponsors_slide';
  	var $handle = 'gigx-meta-box';
  	var $attachments = null;
  
  	var $post_type = array(
  		'label' => 'GIGX Sponsors Slides',
  		'singular_label' => 'GIGX Sponsors Slide',
  		'menu_position' => '1',
  		'taxonomies' => array(),
  		'public' => true,
  		'show_ui' => true,
  		'rewrite' => false,
  		'query_var' => false,
  		'supports' => array( 'thumbnail' )
  		); // 'custom-fields'
    		  
  	function GIGX_Sponsors_Slides_Post_Type() {
  		return $this->__construct();
  	}
  
  	function  __construct() {
  		add_action( 'init', array( &$this, 'init' ) );
  
  		$this->post_type['description'] = $this->post_type['singular_label'];
  		$this->post_type['labels'] = array(
  			'name' => $this->post_type['label'],
  			'singular_name' => $this->post_type["singular_label"],
  			'add_new' => 'Add ' . $this->post_type["singular_label"],
  			'add_new_item' => 'Add New ' . $this->post_type["singular_label"],
  			'edit' => 'Edit',
  			'edit_item' => 'Edit ' . $this->post_type["singular_label"],
  			'new_item' => 'New ' . $this->post_type["singular_label"],
  			'view' => 'View ' . $this->post_type["singular_label"],
  			'view_item' => 'View ' . $this->post_type["singular_label"],
  			'search_items' => 'Search ' . $this->post_type["label"],
  			'not_found' => 'No ' . $this->post_type["singular_label"] . ' Found',
  			'not_found_in_trash' => 'No ' . $this->post_type["singular_label"] . ' Found in Trash'
  			);
  	}
  
  	function init() {
    		register_post_type( $this->post_type_name, $this->post_type );
    		add_action('save_post', array( &$this,'mytheme_save_data'));
        # custom icon
        add_action('admin_head', array( &$this,'gigx_sponsors_slide_icon'));
        # custom thumbnail size
        add_image_size( 'gigx-sponsors-slide', 300, 225 );
        
        # change title text (only works for wp >=3.1)
        add_filter( 'enter_title_here', array( &$this, 'gigx_change_default_title') );            
  	}
  	# change title text        
    function gigx_change_default_title( $title ){
      $screen = get_current_screen();
      if  ( 'gigx_sponsors_slide' == $screen->post_type ) {
        $title = 'Enter Slide Title';
      }
      return $title;
    }  	
  	function gigx_sponsors_slide_icon() {
      	global $post_type;
      	$url = plugin_dir_url( __FILE__ );
      	?>
      	<style>
      	<?php if (($_GET['post_type'] == 'gigx_sponsors_slide') || ($post_type == 'gigx_sponsors_slide')) : ?>
      	#icon-edit { background:transparent url('<?php echo $url .'images/icon32x32.png';?>') no-repeat; }		
      	<?php endif; ?>
      	#adminmenu #menu-posts-gigxsponsorsslide div.wp-menu-image{background: url("<?php echo $url .'images/icon.png';?>") no-repeat 6px -17px !important;}
      	#adminmenu #menu-posts-gigxsponsorsslide:hover div.wp-menu-image,#adminmenu #menu-posts-gallery.wp-has-current-submenu div.wp-menu-image{background-position:6px 7px!important;}	    	
      	
        </style>
        <?php
    }
  

  	  function query_posts( $num_posts = -1, $orderby = 'menu_order' ) {
  		$query = sprintf( 'showposts=%d&post_type=%s&orderby=%s&order=ASC', $num_posts, $this->post_type_name,$orderby );
  		$posts = new WP_Query( $query );  
  		$gallery = array();
  		$child = array( 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'none' );
  		while( $posts->have_posts() ) {
  			$posts->the_post();
  			$child['post_parent'] = get_the_ID(); 
  			
  
  			$p = new stdClass();
  			//$p->post_title = get_the_title();
  			//$p->post_excerpt = get_the_content();
  			$p->post_title= get_post_meta($child['post_parent'], 'gigx_sponsors_slide_title', true);
        $p->post_url= get_post_meta($child['post_parent'], 'gigx_sponsors_slide_url', true);
  			//$p->post_tab= get_post_meta($child['post_parent'], 'gigx_sponsors_slide_tab', true);
        //$p->post_limit= get_post_meta($child['post_parent'], 'gigx_sponsors_slide_limit', false);      
        
        if( ( $c = count( $attachments ) ) > 1 ) {
  				$x = rand( 1, $c );
  				while( $c > $x++ )
  					next( $attachments );
  			}
  			$img=wp_get_attachment_image_src (get_post_thumbnail_id(get_the_ID()),'gigx-sponsors-slide',false);
  			$p->image = '<img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$p->post_title.'" title="'.$p->post_title.'"/>';
  			$gallery[] = $p;
  		}
  		wp_reset_query();
  		return $gallery;
  	}
  	function admin_menu() {
  		add_action( 'do_meta_boxes', array( &$this, 'add_metabox' ), 9 );
  	}        
}

/* Customise columns shown in list of custom post type */
add_action("manage_posts_custom_column", "my_custom_columns");
add_filter("manage_edit-gigx_sponsors_slide_columns", "my_website_columns");
 
function my_website_columns($columns)
{
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",        
        "linktitle" => "Link Title",
        "url" => "Link URL",
        "linkimage" => "Featured Image"       
    );
    return $columns;
}
 
function my_custom_columns($column)
{
    global $post;
    if ("ID" == $column) echo $post->ID;
    elseif ("url" == $column) {
        $url = get_post_meta($post->ID, "gigx_sponsors_slide_url", $single=true);
        echo "<a href=\"$url\" target=\"_blank\">$url</a>";
    }
    elseif ("linktitle" == $column) {
        $title = get_post_meta($post->ID, "gigx_sponsors_slide_title", $single=true);
        edit_post_link($title, '<p><strong>', '</strong></p>',$post->ID);
    } 
    elseif ("linkimage" == $column) {
        $title = get_post_meta($post->ID, "gigx_sponsors_slide_title", $single=true);
        $img=wp_get_attachment_image_src (get_post_thumbnail_id($post->ID),array(64,64),false);
  			$image = '<img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$title.'" title="'.$title.'"/>';
        edit_post_link($image, '<p><strong>', '</strong></p>',$post->ID);
    }    
}

/* remove links menu */
function remove_menus () {
global $menu;
	$restricted = array(__('Links'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'remove_menus');

?>