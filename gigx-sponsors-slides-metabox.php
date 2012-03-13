<?php

# creates Meta Box for GIGX Sponsors Slides
   
      # meta boxes class
      if (!class_exists('RW_Meta_Box')) require 'meta-box.php';
      # meta boxes class
      
      # change textarea field
      class GIGX_Meta_Box extends RW_Meta_Box {
        	function show_field_textarea($field, $meta) {
        		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th>
        			  <td><textarea name='{$field['id']}' cols='60' rows='5' style='width:97%'>$meta</textarea><br />{$field['desc']}</td>";
        	}      
      }
      
   
      # Register meta boxes      
      $prefix = 'gigx_sponsors_slide_';
      
      $meta_boxes = array();
      
      // first meta box
      $meta_boxes[] = array(
      	'id' => 'gigx-sponsors-slides-meta-box',
      	'title' => 'GIGX Sponsors Slide Fields',
      	'pages' => array('gigx_sponsors_slide'), // multiple post types, accept custom post types
      	'context' => 'normal', // normal, advanced, side (optional)
      	'priority' => 'high', // high, low (optional)
      	'fields' => array(
      		array(
            'name' => 'Link Title',
            'desc' => 'Title of the page the slide links to',
            'id' => $prefix .'title',
            'type' => 'textarea',
            'std' => ''
      		),
      		array(
            'name' => 'Link URL',
            'desc' => 'URL of the page the slide links to',
            'id' => $prefix .'url',
            'type' => 'text',
            'std' => 'http://'
      		)
      	)
      );
      foreach ($meta_boxes as $meta_box) {
      	$my_box = new GIGX_Meta_Box($meta_box);
      }
      
?>
