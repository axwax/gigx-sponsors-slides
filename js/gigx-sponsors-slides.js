var $gigx_sponsors_slides = jQuery.noConflict();

$gigx_sponsors_slides(document).ready(function() {
  // slideshow
  	$gigx_sponsors_slides('.gigx-sponsors-slideshow-wrapper').cycle({
  		fx: 'fade',
  		timeout: 7000,
  		speed: 400,
  		pager: $gigx_sponsors_slides('.gigx-sponsors-slideshow-pager'),
  		pagerAnchorBuilder: function(idx, slide) { 
          // return selector string for existing anchor 
          return '.gigx-sponsors-slideshow-pager li:eq(' + idx + ') a'; 
      } 
  	});
	
	//make slides clickable
    $gigx_sponsors_slides('div.gigx-sponsors-slide').clickable();
          
	//tooltips 

        $gigx_sponsors_slides(".gigx-sponsors-slideshow-pagerbutton").tipTip({maxWidth: "200px", edgeOffset: 3, delay: 0, defaultPosition: "top",fadeIn: 100, fadeOut: 200});

          
});
           