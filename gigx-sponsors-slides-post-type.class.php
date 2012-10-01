<?php
# creates GIGX Sponsors Slides post type
# and contains query function for widget

class GIGX_Sponsors_Slides_Post_Type {
  	var $post_type_name = 'gigx_sponsors_slide';
  	var $handle = 'gigx-meta-box';
  	var $attachments = null;
  
  	var $post_type = array(
  		'label' => 'Sponsor Slides',
  		'singular_label' => 'Sponsor Slide',
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
  
		$this->post_type['menu_icon'] = plugin_dir_url( __FILE__ ) . '/images/icon16x16.png';
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
    		# custom icon
        add_action('admin_head', array( &$this,'gigx_sponsors_slide_icon'));
        # custom thumbnail size
        add_image_size( 'gigx-sponsors-slide', 250, 100 );
        
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
        </style>
        <?php
    }
  

  	  function query_posts( $num_posts = -1, $orderby = 'menu_order' ) {
		$wpdb =& $GLOBALS['wpdb'];
		$the_slides= $wpdb->get_results("SELECT   wp_posts.* FROM wp_posts  WHERE 1=1  AND wp_posts.post_type = 'gigx_sponsors_slide' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'private')  ORDER BY menu_order ASC");
  		$gallery = array();
  		$child = array( 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'none' );

		foreach ($the_slides as $slide) {
			$child['post_parent'] = $slide->ID;
			$p = new stdClass();
			$p->post_title= get_post_meta($child['post_parent'], 'gigx_sponsors_slide_title', true);
                        $p->post_url= get_post_meta($child['post_parent'], 'gigx_sponsors_slide_url', true);
			$img=wp_get_attachment_image_src (get_post_thumbnail_id($slide->ID),'gigx-sponsors-slide',false);
  			$p->image = '<img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$p->post_title.'" title="'.$p->post_title.'"/>';
  			$gallery[] = $p;  			
		}
		
		// the old args: $args = sprintf( 'showposts=%d&post_type=%s&orderby=%s&order=ASC', $num_posts, $this->post_type_name,$orderby ); 		
		// the old query: $the_slides = new WP_Query( $args );  
		/* the old loop
  		while( $the_slides->have_posts() ) {
  			$the_slides->the_post();
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
  			$img=wp_get_attachment_image_src (get_post_thumbnail_id($p->ID),'gigx-sponsors-slide',false);
  			$p->image = '<img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$p->post_title.'" title="'.$p->post_title.'"/>';
  			$gallery[] = $p;
  		}
		*/	
  		wp_reset_query();
  		return $gallery;
  	}
  	function admin_menu() {
  		add_action( 'do_meta_boxes', array( &$this, 'add_metabox' ), 9 );
  	}        
}
?>