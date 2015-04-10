<?php 
get_header();
if (have_posts()) : while (have_posts()) : the_post();

if(!$post->post_content=="") { ?>
	<div id="entryContainer">
	<div id="pageContent" class="entry">
		<?php the_content();?>
	</div>
	<?php wp_link_pages(array('before' => '<div class="postinate">','after' => '</div>','pagelink' => '<span>%</span>')); 
	echo '</div>';
}

//COMMENTS
if ('open' == $post->comment_status) { comments_template(); }

endwhile; endif; 
get_footer(); 
?>