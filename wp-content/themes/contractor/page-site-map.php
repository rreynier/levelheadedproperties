<?php 
/*
Template Name: Site Map Page
*/

get_header();

if (have_posts()) : while (have_posts()) : the_post();

//CONTENT CONTAINER
echo '<div id="content-container">';

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

//END CONTENT CONTAINER
echo '</div>';

endwhile;

get_template_part('site-map');

endif; 
get_footer(); 
?>