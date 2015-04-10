<div id="pageContent" class="entry">
		
	<div>
		<h3><?php _e('Categories','themolitor');?></h3>
	   	<ul><?php wp_list_categories('title_li=&sort_column=name&optioncount=1&hierarchical=true'); ?></ul>
	</div>
	
	<div>
		<h3><?php _e('Pages','themolitor');?></h3>
	    <ul><?php wp_list_pages("title_li=" ); ?></ul>
    </div>
    
    <div>
		<h3><?php _e('Archives','themolitor');?></h3>
		<ul>
	   		<?php wp_get_archives('type=monthly'); ?>
	 	</ul>
	</div>
    
    <div>
		<h3><?php _e('Last 100 Items','themolitor');?></h3>
	 	<ul>
	 		<?php $archive_query = new WP_Query('showposts=100&ignore_sticky_posts=true'); while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
	  		<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
	        <?php endwhile; ?>
	   	</ul>
   	</div>
	
</div><!--end entry-->