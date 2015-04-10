<?php
//////////////////////////////
//GEOCODE ADDRESS FOR LAT/LONG
//////////////////////////////
function molitor_geocode_address($post_id)
{
    $address = get_post_meta( $post_id, 'post_location', TRUE );
    $alatitude = get_post_meta( $post_id, 'contractor_latitude', TRUE );
    $alongitude = get_post_meta( $post_id, 'contractor_longitude', TRUE );
    if(!empty($address)) {
        $resp = wp_remote_get( "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false" );
        if ( 200 == $resp['response']['code'] ) {
            $body = $resp['body'];
            $data = json_decode($body);
            if($data->status=="OK"){
                $latitude = $data->results[0]->geometry->location->lat;
                $longitude = $data->results[0]->geometry->location->lng;
                update_post_meta($post_id, "contractor_latitude", $latitude);
                update_post_meta($post_id, "contractor_longitude", $longitude);
            }
        }
    }
}
add_action('save_post', 'molitor_geocode_address');


/////////////////////////////////
//IMAGE ATTACHMENTS FOR POST PAGE
/////////////////////////////////
function molitor_attachment_postpage() {
	if($images = get_children(array(
		'order'   => 'ASC',
		'orderby' => 'menu_order',
		'post_parent'    => get_the_ID(),
		'post_type'      => 'attachment',
		'numberposts'    => -1, // show all
		'post_status'    => null,
		'post_mime_type' => 'image'
	))) {
		foreach($images as $image) {
			$attimg   = wp_get_attachment_image_src($image->ID,'full');
			$attimgGrid   = wp_get_attachment_image($image->ID,'grid');
			$atturl   = wp_get_attachment_url($image->ID);
			$atttitle = apply_filters('the_title',$image->post_title);
			echo'<li><a rel="zoomBox[gallery]" href="'. $attimg[0].'" />'.$attimgGrid.'<p>'.$atttitle.'</p></a></li>';
		}
	}
}


///////////////////////////////
//IMAGE ATTACHMENTS FOR ARCHIVE
///////////////////////////////
function molitor_attachment_archivepage() {
	$thumb_ID = get_post_thumbnail_id();
	if($images = get_children(array(
		'order'   => 'ASC',
		'orderby' => 'menu_order',
		'post_parent'    => get_the_ID(),
		'post_type'      => 'attachment',
		'numberposts'    => 3, // show all
		'post_status'    => null,
		'post_mime_type' => 'image',
		'exclude' => $thumb_ID,
	))) {
		echo '<div class="postPrev">';
		foreach($images as $image) {
			$attimgPrev   = wp_get_attachment_image($image->ID,'preview');
			echo $attimgPrev;
		}
		echo '</div>';
	}
}


//////////////
//POST FORMATS - http://codex.wordpress.org/Post_Formats
//////////////
add_theme_support( 'post-formats', array( 'gallery','quote','aside' ) );


//////////////////////////////////////////
//REMOVE SHORTCODE FOR GALLERY POST FORMAT
//////////////////////////////////////////
function remove_shortcode_from_gallery($content) {
  if ( is_single() && has_post_format( 'gallery' ) ) {
  	$pattern = get_shortcode_regex();
    preg_match('/'.$pattern.'/s', $content, $matches);
    if ( isset($matches[2]) && is_array($matches) && $matches[2] == 'gallery') {
        $content = str_replace( $matches['0'], '', $content );
    }
  }
  return $content;
}
add_filter('the_content', 'remove_shortcode_from_gallery');


////////////////
//GET MENU NAME
////////////////
function get_menu_name( $theme_location ) {
	if( ! $theme_location ) return false;
 
	$theme_locations = get_nav_menu_locations();
	if( ! isset( $theme_locations[$theme_location] ) ) return false;
 
	$menu_obj = get_term( $theme_locations[$theme_location], 'nav_menu' );
	if( ! $menu_obj ) $menu_obj = false;
	if( ! isset( $menu_obj->name ) ) return false;
 
	return $menu_obj->name;
}


///////////////////////
//Localization Support
///////////////////////
load_theme_textdomain( 'themolitor', get_template_directory().'/languages' );
$locale = get_locale();
$locale_file = get_template_directory()."/languages/$locale.php";
if ( is_readable($locale_file) )
    require_once($locale_file);
    
    
///////////////
//EDITOR STYLES
///////////////
function contractor_add_editor_styles() {
    add_editor_style( '/css/contractor-editor-style.css' );
}
add_action( 'init', 'contractor_add_editor_styles' );


///////////////
//EXCERPT STUFF
///////////////
function add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'add_excerpts_to_pages' );

function new_excerpt_length($length) {
	return 35;
}
add_filter('excerpt_length', 'new_excerpt_length');
function new_excerpt_more($more) {
       global $post;
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');


/////////////
//STYLESHEETS
/////////////
function contractor_enqueue_style() {
	wp_enqueue_style( 'stylesheet', get_stylesheet_uri() );
	wp_enqueue_style( 'respond', get_template_directory_uri() . '/css/respond.css');
}
add_action( 'wp_enqueue_scripts', 'contractor_enqueue_style' );


/////////
//SCRIPTS
/////////
function contractor_enqueue_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('pace',get_template_directory_uri() . '/scripts/pace.js',array(),false,true);
	wp_enqueue_script('custom',get_template_directory_uri() . '/scripts/custom.js',array(),false,true);
	if (is_page_template('location-map.php') || is_page_template('location-map-widgets.php')){ wp_enqueue_script('google_maps','https://maps.googleapis.com/maps/api/js?v=3.exp',array(),false,true); }
}
add_action( 'wp_enqueue_scripts', 'contractor_enqueue_scripts' );


/////////////////////
//CUSTOM HEADER IMAGE
/////////////////////
$headerArgs = array(
	'default-image' => get_template_directory_uri() . '/images/architecture.jpg',
	'header-text'   => false,
	'height'        => 800,
	'width'         => 1200,
	'uploads'       => true
);
add_theme_support( 'custom-header', $headerArgs );


///////////////
//CONTENT WIDTH
///////////////
if ( ! isset( $content_width ) ) $content_width = 1170;


//////////////////////
//AUTOMATIC FEED LINKS
//////////////////////
add_theme_support('automatic-feed-links' );


////////////////////////
//FEATURED IMAGE SUPPORT
////////////////////////
add_theme_support( 'post-thumbnails', array( 'post','page','project' ) );
set_post_thumbnail_size( 700, 450, true );
add_image_size('grid',700 ,450,true );
add_image_size('quote',90 ,90,true );
add_image_size('preview',233 ,150,true );


//////////////////
//ADD MENU SUPPORT
//////////////////
add_theme_support( 'menus' );
register_nav_menu('profile', 'Main Menu 1');
register_nav_menu('work', 'Main Menu 2');
register_nav_menu('social', 'Main Menu 3');
register_nav_menu('footer', 'Footer Menu');


//////////////////
//WIDGET GENERATOR
//////////////////
function contractor_widgets_init() {
	register_sidebar(array('name'=>'Live Widgets',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h2 class="widgettitle">',
			'after_title' => '</h2>'
	));

	//WIDGETS PAGE
    $widgetPages = get_pages(
    	array(
	        'sort_column' => 'post_date',
	        'hierarchical' => 0,
	        'meta_key' => '_wp_page_template',
			'meta_value' => 'page-widgets.php'
    	)
    );    
    foreach($widgetPages as $widgetPage){
    	$sidebarID = 'sidebar-'.$widgetPage->post_name;
        register_sidebar(array(  
          'name' => $widgetPage->post_title,  
          'id'   => $sidebarID, 
          'description'   => 'These widgets only display on the "'.$widgetPage->post_title.'" page.',  
          'before_widget' => '<li id="%1$s" class="widget %2$s">',
          'after_widget'  => '</li>',  
          'before_title'  => '<h2 class="widgettitle">',  
          'after_title'   => '</h2>'  
        ));
    }
    
    //MAP WIDGETS PAGE
    $mapWidgetPages = get_pages(
    	array(
	        'sort_column' => 'post_date',
	        'hierarchical' => 0,
	        'meta_key' => '_wp_page_template',
			'meta_value' => 'location-map-widgets.php'
    	)
    );
    foreach($mapWidgetPages as $mapWidgetPage){
    	$sidebarID = 'sidebar-'.$mapWidgetPage->post_name;
        register_sidebar(array(  
          'name' => $mapWidgetPage->post_title,  
          'id'   => $sidebarID, 
          'description'   => 'These widgets only display on the "'.$mapWidgetPage->post_title.'" page.',  
          'before_widget' => '<li id="%1$s" class="widget %2$s">',
          'after_widget'  => '</li>',  
          'before_title'  => '<h2 class="widgettitle">',  
          'after_title'   => '</h2>'  
        ));
    }
}
add_action( 'widgets_init', 'contractor_widgets_init' );

///////////////
//POST OPTIONS
///////////////
include(TEMPLATEPATH . '/include/post-options/custom-meta-box-template.php');

///////////////
//THEME OPTIONS
///////////////
include(TEMPLATEPATH . '/include/theme-options.php');