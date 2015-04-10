<script>
jQuery.noConflict(); jQuery(document).ready(function(){

	////////////////
	//LOCATION SETUP
	////////////////
	var locations = [<?php
		//LOOK FOR POSTS WITH ADDRESS ONLY
		$args = array(
			'post_type' => 'any',
			'posts_per_page' => -1,
			'meta_query' => array(
                    array(
                    	'key' => 'post_location',
                    )
            ),
            'ignore_sticky_posts' => 1
		);
		$the_query = new WP_Query($args);
		//LOOP
		if ( $the_query->have_posts() ) { while ( $the_query->have_posts() ) { $the_query->the_post();
			//VAR SETUP
			$address = get_post_meta( $post->ID, 'post_location', TRUE );
			$latitude = get_post_meta( $post->ID, 'contractor_latitude', TRUE );
			$longitude = get_post_meta( $post->ID, 'contractor_longitude', TRUE );
			$thePermalink = get_the_permalink();
			$theTitle = get_the_title();

			//CATEGORY OUTPUT LOOKS LIKE THIS
			$thumb_id = get_post_thumbnail_id();
			$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'grid', true);
			$thumb_url = $thumb_url_array[0];
			
			//ECHO LOCATION DETAILS
			if($address && $latitude && $longitude){				
				echo '["<div class=\"closeDetails\">&times;</div><a href=\"'.$thePermalink.'\"><img src=\"'.$thumb_url.'\" alt=\"\"/></a><div class=\"infoBox\"><h3><a href=\"'.$thePermalink.'\">' . addslashes($theTitle) . '</a></h3><small>'.$address.'</small></div>",'.$latitude.','.$longitude.'],';
			}
		}}
		//RESET LOOP
		wp_reset_postdata();
	?>];
      	
	//CREATES COLOR PROFILE
	var stylez = [
		 {
		    "featureType": "all",
		    "stylers": [
		      { "color": "#1a1a1a" },
		      { "lightness": 15 }
		    ]
		  },{
		    "featureType": "water",
		    "elementType": "geometry",
		    "stylers": [
		      { "color": "#1a1a1a" },
		      { "lightness": 6 }
		    ]
		  },{
		    "featureType": "landscape",
		    "elementType": "geometry",
		    "stylers": [
		      { "color": "#1a1a1a" },
		      { "lightness": 15 }
		    ]
		  },{
		    "featureType": "road.highway",
		    "elementType": "geometry.fill",
		    "stylers": [
		      { "color": "#1a1a1a" },
		      { "lightness": 17 }
		    ]
		  },{
		    "featureType": "road.highway",
		    "elementType": "geometry.stroke",
		    "stylers": [
		      { "color": "#1a1a1a" },
		      { "lightness": 29 },
		      { "weight": 0.2 }
		    ]
		  },{
		    "featureType": "road.arterial",
		    "elementType": "geometry",
		    "stylers": [
		      { "color": "#1a1a1a" },
		      { "lightness": 18 }
		    ]
		  },{
		    "featureType": "road.local",
		    "elementType": "geometry",
		    "stylers": [
		      { "color": "#1a1a1a" },
		      { "lightness": 16 }
		    ]
		  },{
		    featureType: "poi",
				stylers: [
		  			{ "visibility": "off" }
				]
		  },{
		    "elementType": "labels.text.stroke",
		    "stylers": [
		      { "visibility": "on" },
		      { "color": "#1a1a1a" },
		      { "lightness": 16 }
		    ]
		  },{
		    "elementType": "labels.text.fill",
		    "stylers": [
		      { "saturation": 36 },
		      { "color": "#1a1a1a" },
		      { "lightness": 65 }
		    ]
		  },{
		    "elementType": "labels.icon",
		    "stylers": [
		      { "visibility": "off" }
		    ]
		  },{
		    "featureType": "transit",
		    "elementType": "geometry",
		    "stylers": [
		      { "color": "#1a1a1a" },
		      { "lightness": 19 }
		    ]
		  },{
		    "featureType": "administrative",
		    "elementType": "geometry.fill",
		    "stylers": [
		      { "color": "#1a1a1a" },
		      { "lightness": 20 }
		    ]
		  },{
		    "featureType": "administrative",
		    "elementType": "geometry.stroke",
		    "stylers": [
		      { "color": "#1a1a1a" },
		      { "lightness": 17 },
		      { "weight": 1.2 }
		    ]
		  }
	];
    
    var locationDetails = jQuery('#locationDetails');
	
	var markers=new Array();
	
	
	//////////////
	//MAP FEATURES
	//////////////
    var map = new google.maps.Map(document.getElementById('map-canvas'), {
    	panControl: false,
    	zoomControl: false,
    	zoomControlOptions: {
        	style: google.maps.ZoomControlStyle.SMALL,
        	position: google.maps.ControlPosition.TOP_RIGHT
    	},
    	scrollwheel: false,
    	scaleControl: false,
    	mapTypeControl: false,
    	streetViewControl:false
    });

    var mapType = new google.maps.StyledMapType(stylez, { name:"Grayscale" });    
	map.mapTypes.set('grayscale', mapType);
	map.setMapTypeId('grayscale');

	var bounds = new google.maps.LatLngBounds();
    var marker, i;
    
    
    /////////////////
    //NEXT-PREV SETUP
    /////////////////
    var maxIndex = locations.length - 1;
    jQuery('#next-loc').data('next', 0);
	jQuery('#prev-loc').data('prev', maxIndex);
	
	
	////////////
	//NEXT CLICK
	////////////
	jQuery(document).on('click','#next-loc',function(){
		var nextIndex = jQuery(this).data('next');
		
		if(nextIndex <= maxIndex){
			google.maps.event.trigger(markers[nextIndex], 'click');
		} else {
			google.maps.event.trigger(markers[0], 'click');
		}
					
		return false;
	});	
	
	
	////////////
	//PREV CLICK
	////////////
	jQuery(document).on('click','#prev-loc',function(){
		var prevIndex = jQuery(this).data('prev');
		
		if(prevIndex >= 0){
			google.maps.event.trigger(markers[prevIndex], 'click');
		} else {
			google.maps.event.trigger(markers[maxIndex], 'click');
		}
					
		return false;
	});	
	
	
	/////////////////
	//MAP SIZE TOGGLE
	/////////////////
	jQuery(document).on('click','#full-map-toggle',function(){
		jQuery('body').toggleClass('full-map');	
		
		//CENTER MAP
		var center = map.getCenter();
    	google.maps.event.trigger(map, "resize");
    	map.setCenter(center);
		
		return false;
	});

    for (i = 0; i < locations.length; i++) {
    	    	
    	var customIcon = new google.maps.MarkerImage('<?php echo get_template_directory_uri();?>/images/marker.png', null, null, null, new google.maps.Size( 30,41));
      
    	marker = new google.maps.Marker({
        	position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        	map: map,
        	animation: google.maps.Animation.DROP,
        	icon: customIcon
        });
    	markers.push(marker);
    	
    	bounds.extend(marker.position);
		
		//////////////
		//MARKER CLICK
		//////////////
		google.maps.event.addListener(marker, 'click', (function(marker, i) {
        	return function() {
        		var nextIndex = i+1,
        			prevIndex = i-1;
        			
        		//CLOSE LOC LIST
        		jQuery('#list-item-toggle.list-open').click();

        		locationDetails.stop(true,true).removeClass('infoOpen').fadeOut(150,function(){
        			locationDetails.html(locations[i][0]);
        		
	        		jQuery('#next-loc').data('next', nextIndex);
	        		jQuery('#prev-loc').data('prev', prevIndex);
	        		
	        		map.panTo(marker.getPosition());
	    				locationDetails.stop(true,true).fadeIn(300).addClass('infoOpen');
	    				
	    				//SCROLL INFO INTO VIEW
	    				var locationOffset = locationDetails.offset().top;
	    				if( locationOffset < jQuery(document).scrollTop() ) {
	    				
	    					jQuery('html,body').stop(true,true).animate({scrollTop: locationOffset+"px"}, 500);
	    				}	    				
				});
        	}
        })(marker, i));
    }
    
    
    ///////////////////////////////////
	//CLOSE DETAILS WHEN CENTER CHANGES
	///////////////////////////////////
	google.maps.event.addListener(map, 'center_changed', (function() {
    	if(locationDetails.is(':visible')) {
    		locationDetails.stop(true,true).fadeOut(150).removeClass('infoOpen');
    	}
    }));
    
    
    ///////////////
	//CLOSE DETAILS
	///////////////
	jQuery(document).on('click',".closeDetails",function(){
		jQuery('#locationDetails').stop(true,true).fadeOut(150).removeClass('infoOpen');
	});
    
    
    //////////////////////
	//CENTER MAP ON RESIZE
	//////////////////////
    google.maps.event.addDomListener(window, "resize", function() {
    	var center = map.getCenter();
    	google.maps.event.trigger(map, "resize");
    	map.setCenter(center);
	});
    
    
    ///////////////
    //AUTO ZOOM MAP
    ///////////////
	map.fitBounds(bounds);
	
	
	////////////////////////
	//GENERATE LOC LIST
	////////////////////////
	var locList = jQuery('#loc-list');
	jQuery.each(locations, function(i,v){
  		locList.append('<div class="loc-item">'+v[0]+'</div>');
	});
	
	
	/////////////////
	//LOC LIST TOGGLE
	/////////////////
	jQuery('#list-item-toggle').click(function(){
		jQuery(this).add('#loc-list').toggleClass('list-open');
		jQuery('.closeDetails').click();
		return false;
	}); 
	
	
	////////////
	//KEYS PRESS
	////////////
	jQuery(document).keydown(function(e){
		//LEFT KEY
		if (e.keyCode == 37 && jQuery('body').hasClass('full-map')) {jQuery('#prev-loc').click(); return false;}
		//UP KEY
		if (e.keyCode == 38 && jQuery('body').hasClass('full-map')) {jQuery('#prev-loc').click(); return false;}
		//RIGHT KEY
		if (e.keyCode == 39 && jQuery('body').hasClass('full-map')) {jQuery('#next-loc').click(); return false;}
   		//DOWN KEY
		if (e.keyCode == 40 && jQuery('body').hasClass('full-map')) {jQuery('#next-loc').click(); return false;}
		//ESCAPE
		if (e.keyCode == 27 && jQuery('body').hasClass('full-map')) {jQuery('#full-map-toggle').click(); return false;}
	});
});
</script>