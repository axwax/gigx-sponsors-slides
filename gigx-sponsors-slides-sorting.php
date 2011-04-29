<?php

# Sorting functionality for GIGX Sponsors Slides

/**
 * Enable Sort menu
 *
 * @return void
 * @author Soul
 **/
function gigx_sponsors_slides_enable_sort() {
    add_submenu_page('edit.php?post_type=gigx_sponsors_slide', 'Sort Slides', 'Sort', 'edit_posts', basename(__FILE__), 'gigx_sponsors_slides_sort');
}
add_action('admin_menu' , 'gigx_sponsors_slides_enable_sort'); 
 
 
/**
 * Display Sort admin
 *
 * @return void
 * @author Soul
 **/
function gigx_sponsors_slides_sort() {
	$slides = new WP_Query('post_type=gigx_sponsors_slide&posts_per_page=-1&orderby=menu_order&order=ASC');
?>
	<div class="wrap">
	<h3>Sort Slides <img src="<?php bloginfo('url'); ?>/wp-admin/images/loading.gif" id="loading-animation" /></h3>
	<ul id="gigx-sponsors-slides-list">
	<?php 
    while ( $slides->have_posts() ) : $slides->the_post();	      
        $img=wp_get_attachment_image_src (get_post_thumbnail_id(get_the_id()),array(48,48),false);
  			$image = '<img class="alignright" src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt="'.$title.'" title="'.$title.'"/>';     
	  ?>
		<li id="<?php the_id(); ?>"><?php echo $image.get_post_meta(get_the_id(), 'gigx_sponsors_slide_url', true). '<br/><a style="font-size: 11px;text-decoration: none;font-weight:normal;" href="post.php?post='.get_the_id().'&action=edit">edit</a>';?></li>			
	<?php endwhile; ?>
	</div><!-- End div#wrap //-->
 
<?php
}
 
 
/**
 * Queue up administration JavaScript file
 *
 * @return void
 * @author Soul
 **/
function gigx_sponsors_slides_print_scripts() {
	global $pagenow;
 
	$pages = array('edit.php');
	if (in_array($pagenow, $pages)) {
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('gigx_sponsors_slides_sort', plugins_url('/js/gigx-sponsors-slides-sort.js', __FILE__));
	}
}
add_action( 'admin_print_scripts', 'gigx_sponsors_slides_print_scripts' );
 
 
/**
 * Queue up administration CSS
 *
 * @return void
 * @author Soul
 **/
function gigx_sponsors_slides_print_styles() {
	global $pagenow;
 
	$pages = array('edit.php');
	if (in_array($pagenow, $pages))
		wp_enqueue_style('gigx_sponsors_slides', plugins_url('/css/gigx-sponsors-slides-sort.css', __FILE__));
}
add_action( 'admin_print_styles', 'gigx_sponsors_slides_print_styles' );
 
 
function gigx_sponsors_slides_save_order() {
	global $wpdb; // WordPress database class
 
	$order = explode(',', $_POST['order']);
	$counter = 0;
 
	foreach ($order as $slide_id) {
		$wpdb->update($wpdb->posts, array( 'menu_order' => $counter ), array( 'ID' => $slide_id) );
		$counter++;
	}
	die(1);
}
add_action('wp_ajax_slide_sort', 'gigx_sponsors_slides_save_order');
?>
