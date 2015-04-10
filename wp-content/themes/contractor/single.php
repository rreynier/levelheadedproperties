<?php 
get_header();

if (have_posts()) : while (have_posts()) : the_post(); 

	//ADDRESS VAR
	$address = get_post_meta( $post->ID, 'post_location', TRUE );
	
	//IMAGE GALLERY
	$args = array('post_type' => 'attachment','post_mime_type' => 'image' ,'post_status' => null, 'post_parent' => $post->ID ); 
	$attachments = get_posts($args);
	if($attachments && has_post_format( 'gallery' )) { ?>
		<ul id="attachmentGallery">
			<?php molitor_attachment_postpage(); ?>
		</ul>
    <?php }
    
    //POST CONTENT
	if(!$post->post_content=="") { ?>
		<div id="entryContainer">
		<div <?php post_class('entry'); ?>>			
			<?php the_content(); ?>						
		</div>
		<?php wp_link_pages(array('before' => '<div class="postinate">','after' => '</div>','pagelink' => '<span>%</span>'));
		echo '</div>';
	} ?>
	
	<ul id="details">
		<?php 
		$archive_year  = get_the_time('Y');	
		$archive_month = get_the_time('m');
		echo '<li>'.__('Date:','themolitor').' <span><a href="'. get_month_link( $archive_year, $archive_month ).'">'. get_the_time('F Y').'</a></span></li>'; 
		if($address){echo '<li>'.__('Location:','themolitor').' <span>'.$address.'</span></li>';}  
		echo '<li>'.__('Category:','themolitor').' <span>'; the_category(', '); echo '</span></li>';  
		the_tags('<li>'.__('Tags:','themolitor').' <span>',', ','</span></li>'); 
		?>
	</ul>
		
	<?php
	//COMMENTS
	if ('open' == $post->comment_status) { comments_template(); }
	
endwhile; endif;

get_footer(); ?>