<?php 
/*
Template Name: Location Map w/ Widgets
*/

get_header();
?>

<div id="mapWrapper">
	<div id="map-canvas"></div>
	<div id="locationDetails"></div>
	<div id="loc-list"></div>
	<a title="<?php _e('Previous Location','themolitor'); ?>" id="prev-loc" class="loc-nav" href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow_left.png" alt="" /></a>
	<a title="<?php _e('Next Location','themolitor'); ?>" id="next-loc" class="loc-nav" href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow_right.png" alt="" /></a>
	<a title="<?php _e('Toggle Map Size','themolitor'); ?>" id="full-map-toggle" href="#"><span>&#x2196;</span><span>&#x2198;</span></a>
	<a title="<?php _e('Toggle List Items','themolitor'); ?>" id="list-item-toggle" href="#">&#9776;</a>
</div>

<?php if (have_posts()) : while (have_posts()) : the_post();

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