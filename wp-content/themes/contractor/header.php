<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta name="viewport" content="initial-scale=1.0,width=device-width,maximum-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php wp_title( '' ); ?></title>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php
///////////
//VAR SETUP
///////////
$logo = get_theme_mod('themolitor_customizer_logo');
$textColor = get_theme_mod('themolitor_customizer_text_color','#666666');
$hColor = get_theme_mod('themolitor_customizer_h_color','#333333');
$linkColor = get_theme_mod('themolitor_customizer_link_color','#3b92c4');
$headerColor = get_theme_mod('themolitor_customizer_bg_color','#1a1a1a');
$headerOpacity = get_theme_mod('themolitor_customizer_bg_opacity','.5');
$googleKeyword = get_theme_mod('themolitor_customizer_google_key','Open Sans');
$googleWeight = get_theme_mod('themolitor_customizer_google_weight','400,600');
$contactInfo = get_theme_mod( 'themolitor_customizer_contact','<a target="_blank" href="http://tinyurl.com/p5f3y3u">1000 4th Avenue Seattle, WA 98104</a> <br /> 1.234.567.8910  &nbsp;/&nbsp;  <a target="_blank" href="http://twitter.com/themolitor">@theMOLITOR</a>');
$customCSS = get_theme_mod( 'themolitor_customizer_css');
$bgOpacity = '';
$columnCount = get_theme_mod( 'themolitor_customizer_columns','2');
if($columnCount == ''){$columnCount = '1';}
$perColumns = '';
$headerImages = '';
//CHECK CUSTOM POST OPTIONS FOR VARS
if(is_single() || is_page()){
	$post = $wp_query->post;
	if(has_post_thumbnail($post->ID)){
		$image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID),'large');
		$bgURL = $image[0];
		$bgOpacity = get_post_meta( $post->ID, 'header-transparency', TRUE );
	} else {
		$headerImages = get_uploaded_header_images();
		$bgURL = get_header_image();
	}
	$perColumns = get_post_meta( $post->ID, 'column-count', TRUE );
	$displayStyle = get_post_meta( $post->ID, 'header-style', TRUE );
	if($displayStyle == ''){
		$displayStyle = get_theme_mod('themolitor_customizer_display','standard-display');
	}
} else {
	$headerImages = get_uploaded_header_images();
	$bgURL = get_header_image();
	$displayStyle = get_theme_mod('themolitor_customizer_display','standard-display');
}
//CHECK IF VARS EMPTY
if($bgOpacity == ''){$bgOpacity = $headerOpacity;}
if($perColumns == ''){$perColumns = $columnCount;}
if(!$logo){$logo = get_template_directory_uri().'/images/logo.png';}
if($bgURL == ''){$bgURL = get_template_directory_uri().'/images/architecture.jpg';}

//GOOGLE FONTS API
if(!empty($googleKeyword)) { $googleKeyEdited = str_replace(" ", "+", $googleKeyword); echo '<link href="http://fonts.googleapis.com/css?family='.$googleKeyEdited.':'.$googleWeight.'" rel="stylesheet" type="text/css" />'; } 

if ( is_singular() ) wp_enqueue_script('comment-reply');

wp_head();
?>

<style>
body {
	<?php if(!empty($googleKeyword)){ echo "font-family: '".$googleKeyword."', sans-serif;\n";}?>
	color: <?php echo $textColor;?>;
	font-size: 13px;
}

/*--COLUMNS--*/
#pageWidgets,
.entry{  
	-webkit-column-count:<?php echo $perColumns; ?>;
	-moz-column-count: <?php echo $perColumns; ?>;
	column-count: <?php echo $perColumns; ?>;
}

/*--MENU TEXT COLOR--*/
.menu a {color: <?php echo $textColor;?>;}

/*--HEADING COLOR--*/
h1,h1 a,h1 a:visited,
h2,h2 a,h2 a:visited,
h3,h3 a,h3 a:visited,
h4,h4 a,h4 a:visited,
h5,h5 a,h5 a:visited,
h6,h6 a,h6 a:visited {color: <?php echo $hColor;?>;}

/*--LINK COLOR--*/
a {color: <?php echo $linkColor;?>;}

/*--OVERLAY STYLE--*/
#headerImages:after {
	background-color: <?php echo $headerColor; ?>;
	opacity: <?php echo $bgOpacity; ?>;
}

<?php echo $customCSS; ?>
</style>

</head>

<body <?php body_class($displayStyle);?>>

<div id="headerContainer">
	<div id="header">
		<?php if(is_active_sidebar( 1 )) { ?><a id="widgetPanelToggle" href="#"><span>+</span></a><?php } ?>
		
		<?php get_search_form();?>
		
		<a id="logo" href="<?php echo home_url(); ?>"><img src="<?php echo $logo;?>" alt="<?php bloginfo('name'); ?>" /></a><!--end logo-->
		
		<?php if( $contactInfo ){ ?>
		<div id="contactInfo">
			<?php echo $contactInfo; ?>
		</div>  
		<?php if ( $displayStyle == 'large-display' ) { echo '<a href="#" id="scroll-down"><img src="'. get_template_directory_uri().'/images/arrow_down.png" alt="" /></a>'; }
		} ?>
	</div><!--end header-->
	
	<?php get_sidebar();?>
		
	<div id="headerImages">
		<?php if($headerImages != ''){
			if(is_random_header_image()){shuffle($headerImages);}
			foreach($headerImages as $headerImage) {
				echo '<div style="background-image:url('.$headerImage['url'].');"><img src="'.$headerImage['url'].'" alt=""></div>';
			}	
		} else {
			echo '<div style="background-image:url('.$bgURL.');"><img src="'.$bgURL.'" alt=""></div>';
		} ?>
	</div><!--end headerImages-->
</div><!--end headerContainer-->	

<div id="contentContainer">
	<div id="content">
		<div id="topContentStuff">
		<a href="#" id="menuToggle" class="closed">&#9776; &nbsp;<?php _e('MENU','themolitor'); ?></a>	
		<div id="menusContainer">	
			<?php 
			if (has_nav_menu('profile')) {
				echo '<div class="menuContainer"><h3>'.get_menu_name('profile').'</h3>';
				wp_nav_menu(array(
					'theme_location' => 'profile'
				));
				echo '</div>';
			}
			if (has_nav_menu('work')) {
				echo '<div class="menuContainer"><h3>'.get_menu_name('work').'</h3>';
				wp_nav_menu(array(
					'theme_location' => 'work'
				));
				echo '</div>';
			}
			if (has_nav_menu('social')) {
				echo '<div class="menuContainer"><h3>'.get_menu_name('social').'</h3>';
				wp_nav_menu(array(
					'theme_location' => 'social'
				));
				echo '</div>';
			}
			?>
		</div><!--end menusContainer-->
		
		<div id="title-info">
			<?php 
			////////////////
			//404 PAGE TITLE
			////////////////
			if( is_404() ) { ?>
			
				<h1 class="entrytitle"><?php _e('Page Not Found','themolitor'); ?> - <span class="error"><?php _e('Error 404','themolitor'); ?></span></h1>
				<p><?php _e('We recommend (1) double-checking the page URL, (2) searching the site using keywords, or (3) using the site map below...','themolitor'); ?></p>
				<?php get_search_form();
			} 
			
			///////////////////
			//SEARCH PAGE TITLE
			///////////////////
			elseif ( is_search() ) {
			
				global $wp_query; 
				$queryResults = $wp_query->found_posts;
				if($queryResults > 1) { $items = 'items'; } elseif($queryResults == 1) { $items = 'item'; }
				?>
				<h1 class="entrytitle"><?php _e('Search Results','themolitor');?></h1>
				<?php if(isset($items)) { ?>
					<p><?php _e('Your search for','themolitor'); echo ' "'; echo get_search_query(); echo '" '; _e('returned','themolitor');?> <?php echo $queryResults.' '.$items.'...'; ?></p>					
				<?php } else { ?>	
					<p><?php _e('Your search for','themolitor'); echo ' "'; echo get_search_query(); echo '" '; _e('returned 0 items. Try again?','themolitor');?></p>
					<?php get_search_form();
				}
			} 
			
			////////////////////
			//ARCHIVE PAGE TITLE
			////////////////////		
			elseif ( is_archive() ) {
			
				global $wp_query;
				$queryResults = $wp_query->found_posts; 
				if($queryResults > 1) { $items = 'items'; } elseif($queryResults == 1) { $items = 'item'; }
				?>
				<h1 class="entrytitle"><?php wp_title(''); ?></h1>
				<?php if( category_description() ){
					echo category_description(); 
				} elseif(isset($items)) { ?>
					<p><?php _e('This subject has','themolitor'); echo ' '.$queryResults.' '.$items.' '.__('assigned...','themolitor'); ?></p>					
				<?php } else { ?>	
					<p><?php _e('This subject currently has no items.','themolitor'); ?></p>
					<?php get_search_form();
				}	
			} 
			
			/////////////////////
			//STANDARD PAGE TITLE
			/////////////////////
			elseif ( is_page() ) { ?>
			
				<h1 class="entrytitle"><?php the_title(); edit_post_link('&#9998;',' &nbsp;<small>','</small>'); ?></h1>
				<?php if ( !empty( $post->post_excerpt ) ) { the_excerpt(); }
			} 
			
			/////////////////
			//POST PAGE TITLE
			/////////////////
			elseif ( is_single() ) { ?>
				 
				<h1 class="posttitle"><?php the_title(); edit_post_link('&#9998;',' &nbsp;'); ?> </h1>
				<?php 
				the_excerpt(); 
			} 
			
			/////////////////
			//HOME PAGE TITLE
			/////////////////
			elseif ( is_home() ) { ?>
			
				<h1 class="entrytitle"><?php bloginfo('name'); ?></h1>
				<p><?php bloginfo('description'); ?></p>
				
			<?php } ?>
		</div><!--title-info-->
	
	</div><!--end topContentStuff-->