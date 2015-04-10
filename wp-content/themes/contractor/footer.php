<?php
///////////
//VAR SETUP
///////////
$autoLoad = get_theme_mod('themolitor_customizer_autoload',TRUE);
$parallax = get_theme_mod('themolitor_customizer_parallax',TRUE);
$footerText = get_theme_mod('themolitor_customizer_footer','Handcrafted by <a target="_blank" href="http://themolitor.com">THE MOLITOR</a>');
$headerImages = get_uploaded_header_images();
$slideshowOn = get_theme_mod('themolitor_customizer_slideshow_on',TRUE);
$slideshowTime = get_theme_mod('themolitor_customizer_slideshow_time','8') * 1000;
if(is_single() || is_page()){
	$post = $wp_query->post;
	$displayStyle = get_post_meta( $post->ID, 'header-style', TRUE );
	if( $displayStyle == '' ) {
		$displayStyle = get_theme_mod('themolitor_customizer_display','standard-display');
	}
} else {
	$displayStyle = get_theme_mod('themolitor_customizer_display','standard-display');
}
?>
<div class="clear"></div>

	<div id="footer">  		
		<?php if ( has_nav_menu('footer') ) { 
			wp_nav_menu(array(
				'depth'=> 1,
				'theme_location' => 'footer', 
				'container_id' => 'footerMenuContainer', 
				'menu_id' => 'footerMenu'
			))
		;} ?>
		<div id="copyright">&copy; <?php echo date('Y '); bloginfo('name'); ?>. <?php echo $footerText;?></div>
	</div><!--end footer-->
	
</div><!--end content-->
</div><!--end contentContainer-->

<?php 
//GET STICKY OR NEXT/PREV POST ITEMS
get_template_part('sticky-posts'); 

wp_footer();

//IF MAP PAGE, GET MAP SCRIPT
if ( is_page_template('location-map.php') || is_page_template('location-map-widgets.php') ){ 
	get_template_part('location-script'); 
} 
?>

<script>
jQuery(document).ready(function(){	
	
	//RUN FUNCTIONs
	<?php 
	if ($autoLoad == 1) { echo 'autoLoadPosts();'; }
	if ($parallax == 1) { echo 'parallaxin();'; }
	?>	
	
	//WINDOW LOAD
	jQuery(window).load(function(){
	
		<?php if($slideshowOn == 1){ ?>
		//HEADER SLIDESHOW
		if(headerImage.length > 1){
			setInterval(function(){ headerSlideshow(); }, <?php echo $slideshowTime; ?>);
		}
		<?php } ?>
	
	//WINDOW SCROLL	
	}).scroll(function(){
	
		//RUN FUNCTIONs
		<?php 
		if ($autoLoad == 1) { echo 'autoLoadPosts();'; }
		if ($parallax == 1) { echo 'parallaxin();'; }
		?>
	});	
});
</script>

</body>
</html>