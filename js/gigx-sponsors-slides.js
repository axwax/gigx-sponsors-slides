// GIGX Sponsors Slides custom javascript
// uses jquery.bxSlider, jquery.tipTip and gigx-sponsors-slides-sort

var $gigx_sponsors_slides = jQuery.noConflict();

$gigx_sponsors_slides(document).ready(function() {

  // only display slideshow when fully loaded
    $gigx_sponsors_slides('.gigx-sponsors-slideshow').show("slow");
    
  // add slideshow  
    $gigx_sponsors_slides('.gigx-sponsors-slider').bxSlider({
        mode: 'horizontal',
        infiniteLoop: true,
        speed: 800,
        pause: 2000,
        auto: true,
        pager: false,
        controls: true,
    		displaySlideQty: 3,
    		moveSlideQty: 1,
        autoHover: true    
		});   
	          
	// tooltips 
    $gigx_sponsors_slides(".gigx-sponsors-slides-tip").tipTip({maxWidth: "200px", edgeOffset: 3, delay: 0, defaultPosition: "top",fadeIn: 100, fadeOut: 200});

  // remove duplicate tooltip on image
    $gigx_sponsors_slides(".gigx-sponsors-slides-tip a img").removeAttr('title').removeAttr('alt');
});



	
           