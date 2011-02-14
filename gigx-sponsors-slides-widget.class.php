<?php

# GIGX_Sponsors_Slides_Widget
# properties:
# $id
# $queued

class GIGX_Sponsors_Slides_Widget extends WP_Widget {
	// Note: these strings match strings in WP exactly. If changed the gettext domain will need to be added
	var $id = 'gigx_sponsors_slide_widget';
	var $queued = false;
	function GIGX_Sponsors_Slides_Widget() {
		$widget_ops = array( 'description' => __( 'GIGX Sponsors Slides Widget' ) );
		$this->WP_Widget( $this->id, __('GIGX Sponsors Slides Widget'), $widget_ops );
		add_action( 'wp_head', array( &$this, 'wp_head' ), 1 );
		add_action( 'wp_footer', array( &$this, 'wp_footer' ), 2 );
	}
	function widget( $args, $instance ) {
		global $gigx_sponsors_slide_type;
		extract( $args );
		echo $before_widget; ?>			
    <div class="gigx-sponsors-slideshow">				
      <div class="gigx-sponsors-slideshow-wrapper">
<?php		$first = true;
		$num_posts = -1;
		//if( $instance['how_many'] > 0 ) $num_posts = $instance['how_many'];
			
		if( !empty( $gigx_sponsors_slide_type ) ) {
			$posts = $gigx_sponsors_slide_type->query_posts( $num_posts );
			$pagermenu='';
			$count=0;
			foreach( $posts as $p ) {
    			$numdays=count($p->post_limit);
    			$showslide=true;
          if ($numdays>0){
            foreach ($p->post_limit as $d){
              if(strtolower(date('D'))==$d) $showslide=false;
            }
          }
          if($showslide){
              $count++;
                  ?>  		
                <div class="gigx-sponsors-slide<?php echo ' gigx-sponsors-slide'.$count;  ?>">			    
                  <div class="gigx-sponsors-slide-text">            <h1>
                      <?php echo $p->post_title; ?></h1>            
                    <p>
                      <?php echo $p->post_excerpt; ?><br />
                    </p>  			  
                  </div>                 
                  <?php if (($p->post_url)&&($p->post_url<>"http://")) {?>
                  <a href="<?php echo $p->post_url; ?>" title="
                    <?php echo $p->post_title; ?>">
                    <?php } ?>          
                    <?php echo $p->image; ?>          
                    <?php if (($p->post_url)&&($p->post_url<>"http://")) {?></a>
                  <?php } ?>		
                </div>
                <?php				
              $pagermenu.='<li class="gigx-sponsors-slideshow-pagerbutton gigx-sponsors-slideshow-pagerbutton'.$count.'" title="'.$p->post_title.'"><a href="'.$p->post_url.'">'.$p->post_tab.'</a></li>';
              $first = false;
          }   
			}
		}
    ?>				
      </div>        
      <ul class="gigx-sponsors-slideshow-pager">
        <?php echo $pagermenu; ?>
      </ul>				
      <div style="clear:both;">
      </div>			
    </div>
<?php 		echo $after_widget;
		if( $this->queued )
			$this->queued = false;
	}
 	function update( $new_instance, $old_instance ) {
		$new_instance['how_many'] = intval( $new_instance['how_many'] );
		return $new_instance;
	}
	function form( $instance ) { ?>		
<p>
  <label for="<?php echo $this->get_field_id('how_many'); ?>">
    <?php _e('How many gallery posts:') ?>
  </label>		
  <input type="text" id="<?php echo $this->get_field_id('how_many'); ?>" name="
  <?php echo $this->get_field_name('how_many'); ?>" value="
  <?php echo ( $instance['how_many'] > 0 ? esc_attr( $instance['how_many'] ) : '' ); ?>" />
</p>		
<?php	}
	function wp_head() {
		if( !is_admin() ) {
			$this->queued = true;
			$url = plugin_dir_url( __FILE__ );
			wp_enqueue_style( 'gigx-sponsors-slides-css', $url . 'css/style.css' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'gigx-bxslider-js', $url . 'js/jquery.bxSlider.min.js', array( 'jquery' ), '1.4', true );
			wp_enqueue_script( 'gigx-clickable-js', $url . 'js/jquery.clickable-0.1.9.js', array( 'jquery' ), '1.4', true );
      wp_enqueue_script( 'gigx-tooltip-js', $url . 'js/jquery.tipTip.minified.js', array( 'jquery' ), '1.4', true );
			wp_enqueue_script( 'gigx-sponsors-slides-js', $url . 'js/gigx-sponsors-slides.js', false, false, true );
		}
	}
	function wp_footer() {
		if( $this->queued ) {
			wp_deregister_script( 'gigx-bxslider-js' );
			wp_deregister_script( 'gigx-sponsors-slides-js' );
		}
	}
}
?>