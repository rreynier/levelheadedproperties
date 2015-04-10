<?php 
/*
Template Name: Widgets Page
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
?>

<ul id="pageWidgets" >
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($post->post_title) ) : endif; ?>
</ul>

<?php 
endif; 
get_footer(); 
?>