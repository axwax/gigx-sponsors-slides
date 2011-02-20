<?php
/*
Plugin Name: GIGX Sponsors Slides
Plugin URI: http://gigx.co.uk/wordpress/plugins/gigx-sponsors-slides/
Description: Replace the Wordpress Links section with a jquery.bxSlider-based slideshow.
Author: AxWax
Version: 0.1.0
Author URI: http://axwax.de/
Credits:
This plugin is based on Post Gallery Widget by Ron Rennick 
and uses jquery.bxslider by Steven Wanderski ( http://bxslider.com ) and jquery.tipTip by Drew Wilson ( http://code.drewwilson.com/entry/tiptip-jquery-plugin ).
Sorting code is based on a tutorial by Ryan Marganti ( http://soulsizzle.com/jquery/create-an-ajax-sorter-for-wordpress-custom-post-types ).
Update code by Janis Elsts ( http://w-shadow.com/blog/2010/09/02/automatic-updates-for-any-plugin ).
*/

# check for updates
require 'plugin-update-checker.php';
$checkForUpdate = new PluginUpdateChecker('http://gigx.co.uk/wordpress/update/gigx-sponsors-slides.json', __FILE__, 'gigx-sponsors-slides', 1);
$checkForUpdate->checkForUpdates();
# /check for updates

# meta box
require 'gigx-sponsors-slides-metabox.php';
# /meta box

# gigx_sponsors_slide custom post type
require 'gigx-sponsors-slides-post-type.class.php';
$gigx_sponsors_slide_type = new GIGX_Sponsors_Slides_Post_Type();
# /gigx_sponsors_slide custom post type

# gigx slides widget
require 'gigx-sponsors-slides-widget.class.php';
function register_gigx_sponsors_slides_widget() {
	register_widget( 'GIGX_Sponsors_Slides_Widget' );
}
add_action( 'widgets_init', 'register_gigx_sponsors_slides_widget' );
# /gigx slides widget

# gigx_sponsors_slide sorting functions
require 'gigx-sponsors-slides-sorting.php';
# /gigx_sponsors_slide sorting functions

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