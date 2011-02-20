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
            <h2 class="widget-title">... now a message from our sponsors:</h2>
			
      <div class="gigx-sponsors-slideshow-wrapper">
        <div class="gigx-sponsors-slideshow-inner-wrapper">
          <ul class="gigx-sponsors-slider">   
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
                if (($p->post_url)&&($p->post_url<>"http://"))$url=$p->post_url;
                else $url=home_url();
                if ($p->image)$img=$p->image;
                //else $img=plugin_dir_url( __FILE__ )."images/default.png";
                
                    ?>
               <li class="gigx-sponsors-slides-tip" title="<?php if ($p->post_excerpt) echo $p->post_excerpt; else echo $p->post_title; ?>"><a href="<?php echo $url; ?>" target="_blank"><?php echo $img; ?></a></li>
  <?php 
                 $first = false;
            } // end if($swhowslide)  
  			} // end foreach
  		} // end if (!empty)
      ?>         		
          </ul>
        </div><?php /* inner-wrapper */ ?>  
      </div>        			
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