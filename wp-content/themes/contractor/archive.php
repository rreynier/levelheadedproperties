<?php
get_header();

//////////////////////////////////////
//IGNORE STICKY POSTS IF HOME + SEARCH
//////////////////////////////////////
if( is_home() || is_search() ) { 
	global $query_string;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args = array(
		'ignore_sticky_posts'=> 1,
		'paged' => $paged
	);
	query_posts($query_string . '&ignore_sticky_posts=true&paged='.$paged);
}

//////////
//IF POSTS
//////////
if ( have_posts() ) { 

	///////////////////////////////
	//POST FILTER FOR HOME + SEARCH
	///////////////////////////////
	global $wp_query;
	$queryResults = $wp_query->found_posts; 
	if( $queryResults > 1 && is_home() || $queryResults > 1 && is_search()) {
		$filterItems = get_categories(array(
			'type'           => 'post',
			'child_of'       => 0,
			'parent'         => '',
			'orderby'        => 'slug',
			'order'          => 'ASC',
			'hide_empty'     => 1,
			'hierarchical'   => 1,
			'exclude'        => '',
			'include'        => '',
			'number'         => '',
			'taxonomy'       => 'category',
			'pad_counts'     => false 
		));
		$filterHTML = '<div id="postFilter">';
		$filterHTML .= '<a href="#" title="'.__('All Items','themolitor').'" class="category-all activeFilter">'.__('All Items','themolitor').'</a>';
		foreach ( $filterItems as $filterItem ) {			
			$filterHTML .= '<a href="#" title="'.__('Show only','themolitor').' '.$filterItem->name.' '.__('items','themolitor').'" class="category-'.$filterItem->slug.'">';
			$filterHTML .= $filterItem->name.'</a>';
		}
		if( is_search() ) { $filterHTML .= '<a href="#" title="'.__('Show only Pages','themolitor').'" class="type-page">'.__('Pages','themolitor').'</a>'; }
		$filterHTML .= '</div>';
		echo $filterHTML;
	}
	
	//////////////////////
	//START AJAX CONTAINER
	//////////////////////
	echo '<div id="ajaxContainer">';
	
	///////////////////////
	//START POSTS CONTAINER
	///////////////////////
	echo '<div class="postsContainer">';
	
	//////////
	//THE LOOP
	//////////
	while (have_posts()) : the_post(); ?>
	
		<div <?php post_class('category-all'); ?>>
		
			<?php 
			//GALLERY FORMAT FEAUTED IMAGE
			if( has_post_thumbnail() && has_post_format( 'gallery' ) ){?>
				<a class="featuredLink hasGallery" href="<?php the_permalink();?>"><?php the_post_thumbnail('grid'); molitor_attachment_archivepage(); ?></a>
			<?php 
			//ASIDE FEATURED IMAGE
			} else if ( has_post_thumbnail() && has_post_format( 'aside' ) ) { ?>
				<div class="asideImage"><?php the_post_thumbnail('grid'); ?></div>
			<?php
			//FEATURED IMAGE - EXCEPT FOR QUOTE FORMAT
			} else if ( has_post_thumbnail() && !has_post_format( 'quote' ) ) { ?>
				<a class="featuredLink" href="<?php the_permalink();?>"><?php the_post_thumbnail('grid'); ?></a>
			<?php } ?>
			
			<div class="postInfo">
				<?php 
				//IF QUOTE POST FORMAT
				if( has_post_format( 'quote' ) ) {
				
					//QUOTE CONTENT
					echo '<div class="quote-content">';
					the_content();
					echo'</div>';
					
					//QUOTE FEATURED IMAGE
					the_post_thumbnail('quote'); 
					
					//QUOTE TITLE STUFF
					$quoteTitle = str_replace('|','<br />',get_the_title());
					//CHECK FOR LINKS IN QUOTE POST TITLE
					$reg_exUrl = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
					if(preg_match($reg_exUrl, $quoteTitle, $url)) {
						$quoteTitle = preg_replace($reg_exUrl, ' <a title="'.__('Visit Site','themolitor').'" class="quote-link" href="'.$url[0].'">&infin;</a>', $quoteTitle);
					}
					$quoteTitle = preg_replace('/\B\@([a-zA-Z0-9_]{1,20})/', '<a target="_blank" href="http://twitter.com/$1">$0</a>', $quoteTitle);
					?>
					<h4 class="quote-title overflow-scroll"><?php echo $quoteTitle;?></h4>
					
				<?php 
				//IF ASIDE POST FORMAT
				} elseif( has_post_format( 'aside' ) ) { ?>
					
					<?php
					//ASIDE TITLE STUFF
					$asideTitle = str_replace('|','<br />',get_the_title());
					//CHECK FOR TWITTER USERNAME
					$asideTitle = preg_replace('/\B\@([a-zA-Z0-9_]{1,20})/', '<a target="_blank" href="http://twitter.com/$1">$0</a>', $asideTitle);
					echo '<h2 class="posttitle aside-title overflow-scroll">'.$asideTitle.'</h2>';
					
					//ASIDE CONTENT
					echo '<div class="aside-content">';
					the_content();
					echo'</div>';

				//IF NOT QUOTE OR ASIDE POST
				} else { ?>
					<h2 class="posttitle"><a href="<?php the_permalink();?>"><?php echo the_title();?></a></h2>
					<?php echo the_excerpt();?>
					<a class="readMore" href="<?php the_permalink();?>">+</a>
					<p class="theDate"><?php echo get_the_date();?></p>
				<?php } ?>
			</div><!--end postInfo-->
			
		</div><!--end post-->
		
	<?php 
	//////////
	//END LOOP
	//////////
	endwhile;  
	
	/////////////////////
	//END POSTS CONTAINER
	/////////////////////
	echo '</div><!--end postsContainer-->';
	
	/////////////////////
	//SHOW LOAD MORE LINK
	/////////////////////
	$nextPostLink = get_next_posts_link('+');
	if( $nextPostLink ){
		echo '<div id="loadMore">'. $nextPostLink.'</div>';
	}
	
	////////////////////
	//END AJAX CONTAINER
	////////////////////
	echo '</div>';

}//END IF POSTS

//////////////////////////////
//GET SITE MAP IF SEARCH FAILS
//////////////////////////////
else if ( is_search() ) {

	get_template_part('site-map');
}

get_footer();
?>