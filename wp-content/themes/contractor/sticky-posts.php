<?php 
$next_post = get_adjacent_post( true,'',false );
$prev_post = get_adjacent_post( true,'',true );

/////////////////
//NEXT/PREV POSTS
/////////////////
if( is_single() && $next_post || is_single() && $prev_post ) {
	
	echo '<div id="stickyPosts">';

	//IF THERE IS A NEXT POST...
	if( $next_post ) {
		$next_id = $next_post->ID;
		$nextCategory = get_the_category($next_id);
		$nextLink = get_permalink( $next_id ); 
		$nextThumb = wp_get_attachment_image_src( get_post_thumbnail_id($next_id), 'large' );
		$nextThumburl = $nextThumb['0'];
		?>
		<a href="<?php echo $nextLink; ?>" class="nextPrevItem" style="background-image:url('<?php echo $nextThumburl;?>');">
			<div><p><span>&larr; <?php _e('Prev','themolitor'); echo ' '.$nextCategory[0]->cat_name.' '; _e('Item','themolitor');?></span><?php echo get_the_title($next_id); ?></p></div>
		</a><!--end rightNextItem-->
	<?php }
	
	//IF THERE IS A PREV POST...
	if ( $prev_post ) {
	 	$prev_id = $prev_post->ID;
		$prevCategory = get_the_category($prev_id);
		$prevLink = get_permalink( $prev_id ); 
		$prevThumb = wp_get_attachment_image_src( get_post_thumbnail_id($prev_id), 'large' );
		$prevThumbUrl = $prevThumb['0'];
		?>
		<a href="<?php echo $prevLink; ?>" class="nextPrevItem" style="background-image:url('<?php echo $prevThumbUrl;?>');">
			<div><p><span><?php _e('Next','themolitor'); echo ' '.$prevCategory[0]->cat_name.' ';  _e('Item','themolitor');?> &rarr;</span><?php echo get_the_title($prev_id); ?></p></div>
		</a><!--end leftNextItem-->
	<?php }
	
	echo '<div class="clear"></div></div><!--end stickyPosts-->';

} //END NEXT/PREV POSTS
	
//////////////
//STICKY POSTS
//////////////
else {
	
	//THE QUERY
	$query1 = new WP_Query(array(
		'posts_per_page' => 2,
		'post__in'  => get_option( 'sticky_posts' ),
		'ignore_sticky_posts' => 1,
		'orderby' => 'rand',
		'tax_query' => array(
			array(
			'taxonomy' => 'post_format',
			'field'    => 'slug',
			'terms'    => array( 'post-format-aside', 'post-format-quote' ),
			'operator' => 'NOT IN',
			)
	 	)
	));
	
	//IF STUFF EXISTS...
	if( $query1->have_posts() ){
	
		echo '<div id="stickyPosts">';
		
		while ( $query1->have_posts() ) {
			$query1->the_post();
			$post_id = $query1->post->ID;
			$postCategory = get_the_category($post_id);
			$postLink = get_permalink( $post_id ); 
			$postThumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'larg' );
			$postThumburl = $postThumb['0'];
			?>
			<a href="<?php echo $postLink; ?>" class="nextPrevItem" style="background-image:url('<?php echo $postThumburl;?>');">
				<div><p><span><?php if( is_sticky() ) { _e('Featured','themolitor'); } else { echo _e('Latest','themolitor'); } echo ' '.$postCategory[0]->cat_name.' '; _e('Item','themolitor');?></span><?php echo get_the_title($post_id); ?></p></div>
			</a><!--end rightNextItem-->
		<?php } //END WHILE
		
		echo '<div class="clear"></div></div><!--end stickyPosts-->';
	
	}//END IF
	
	wp_reset_postdata();
	
}//END STICKY POSTS
?>