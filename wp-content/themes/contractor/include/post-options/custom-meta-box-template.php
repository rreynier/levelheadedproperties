<?php
/**
 * Adds a meta box to the post editing screen
 */
function prfx_custom_meta() {
	add_meta_box( 'prfx_meta', __( 'Options', 'prfx-textdomain' ), 'prfx_meta_callback', 'post', 'advanced','high' );
	add_meta_box( 'prfx_meta', __( 'Options', 'prfx-textdomain' ), 'prfx_meta_callback', 'page', 'advanced','high' );
}
add_action( 'add_meta_boxes', 'prfx_custom_meta' );

/**
 * Outputs the content of the meta box
 */
function prfx_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
	$prfx_stored_meta = get_post_meta( $post->ID );
	?>

	<p>
		<label for="post_location" class="prfx-row-title"><?php _e( 'Location', 'prfx-textdomain' )?></label>
		<input type="text" name="post_location" id="post_location" value="<?php if ( isset ( $prfx_stored_meta['post_location'] ) ) echo $prfx_stored_meta['post_location'][0]; ?>" /> <small><?php _e( 'Example: Seattle, WA', 'prfx-textdomain' )?></small>
	</p>
	
	<p>
		<label for="header-transparency" class="prfx-row-title"><?php _e( 'Header Overlay Transparency', 'prfx-textdomain' )?></label>
		<input type="text" name="header-transparency" id="header-transparency" value="<?php if ( isset ( $prfx_stored_meta['header-transparency'] ) ) echo $prfx_stored_meta['header-transparency'][0]; ?>" /> <small><?php _e( 'Enter a value between .00 and 1', 'prfx-textdomain' )?></small>
	</p>
	
	<p>
		<label for="column-count" class="prfx-row-title"><?php _e( 'Column Count', 'prfx-textdomain' )?></label>
		<input type="text" name="column-count" id="column-count" value="<?php if ( isset ( $prfx_stored_meta['column-count'] ) ) echo $prfx_stored_meta['column-count'][0]; ?>" /> <small><?php _e( 'Enter a value from 1 - 6', 'prfx-textdomain' )?></small>
	</p>

	<p>
		<label for="header-style" class="prfx-row-title"><?php _e( 'Header Display Style', 'prfx-textdomain' )?></label>
		<select name="header-style" id="header-style">
			<option value="" <?php if ( isset ( $prfx_stored_meta['header-style'] ) ) selected( $prfx_stored_meta['header-style'][0], '' ); ?>><?php _e( 'Default', 'prfx-textdomain' )?></option>';
			<option value="large-display" <?php if ( isset ( $prfx_stored_meta['header-style'] ) ) selected( $prfx_stored_meta['header-style'][0], 'large-display' ); ?>><?php _e( 'Large', 'prfx-textdomain' )?></option>';
			<option value="standard-display" <?php if ( isset ( $prfx_stored_meta['header-style'] ) ) selected( $prfx_stored_meta['header-style'][0], 'standard-display' ); ?>><?php _e( 'Standard', 'prfx-textdomain' )?></option>';
		</select>
	</p>
 

	<?php
}



/**
 * Saves the custom meta input
 */
function prfx_meta_save( $post_id ) {
 
	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'prfx_nonce' ] ) && wp_verify_nonce( $_POST[ 'prfx_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}
 
	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'post_location' ] ) ) {
		update_post_meta( $post_id, 'post_location', sanitize_text_field( $_POST[ 'post_location' ] ) );
	}
	if( isset( $_POST[ 'header-transparency' ] ) ) {
		update_post_meta( $post_id, 'header-transparency', sanitize_text_field( $_POST[ 'header-transparency' ] ) );
	}
	if( isset( $_POST[ 'column-count' ] ) ) {
		update_post_meta( $post_id, 'column-count', sanitize_text_field( $_POST[ 'column-count' ] ) );
	}

	// Checks for input and saves if needed
	if( isset( $_POST[ 'header-style' ] ) ) {
		update_post_meta( $post_id, 'header-style', $_POST[ 'header-style' ] );
	}

}
add_action( 'save_post', 'prfx_meta_save' );


/**
 * Adds the meta box stylesheet when appropriate
 */
function prfx_admin_styles(){
	global $typenow;
	if( $typenow == 'post' || $typenow == 'page' ) {
		wp_enqueue_style( 'prfx_meta_box_styles', get_template_directory_uri() . '/include/post-options/meta-box-styles.css' );
	}
}
add_action( 'admin_print_styles', 'prfx_admin_styles' );


/**
 * Loads the color picker javascript
 */
function prfx_color_enqueue() {
	global $typenow;
	if( $typenow == 'post' || $typenow == 'page' ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'meta-box-color-js', get_template_directory_uri() . '/include/post-options/meta-box-color.js', array( 'wp-color-picker' ) );
	}
}
add_action( 'admin_enqueue_scripts', 'prfx_color_enqueue' );

/**
 * Loads the image management javascript
 */
function prfx_image_enqueue() {
	global $typenow;
	if( $typenow == 'post' || $typenow == 'page' ) {
		wp_enqueue_media();
 
		// Registers and enqueues the required javascript.
		wp_register_script( 'meta-box-image', get_template_directory_uri() . '/include/post-options/meta-box-image.js', array( 'jquery' ) );
		wp_localize_script( 'meta-box-image', 'meta_image',
			array(
				'title' => __( 'Choose or Upload an Image', 'prfx-textdomain' ),
				'button' => __( 'Use this image', 'prfx-textdomain' ),
			)
		);
		wp_enqueue_script( 'meta-box-image' );
	}
}
add_action( 'admin_enqueue_scripts', 'prfx_image_enqueue' );
