var $gigx_sponsors_slides = jQuery.noConflict();

$gigx_sponsors_slides(document).ready(function() {
    $gigx_sponsors_slides('.gigx-sponsors-slideshow').show("slow");
    
    
    
    
    $gigx_sponsors_slides('#slider1').bxSlider({
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

	
	//make slides clickable
    //$gigx_sponsors_slides('div.gigx-sponsors-slide').clickable();
          
	//tooltips 

        $gigx_sponsors_slides(".gigx-sponsors-slides-tip").tipTip({maxWidth: "200px", edgeOffset: 3, delay: 0, defaultPosition: "top",fadeIn: 100, fadeOut: 200});
        $gigx_sponsors_slides(".gigx-sponsors-slides-tip a img").removeAttr('title').removeAttr('alt');

          
});



	
           