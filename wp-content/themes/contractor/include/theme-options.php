<?php
add_action( 'customize_register', 'themolitor_customizer_register' );

function themolitor_customizer_register($wp_customize) {

	//CREATE TEXTAREA OPTION
	class Example_Customize_Textarea_Control extends WP_Customize_Control {
    	public $type = 'textarea';
 
    	public function render_content() { ?>
        	<label>
        	<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        	<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
        	</label>
        <?php }
	}
	
	//CREATE CATEGORY DROP DOWN OPTION
	$options_categories = array();  
	$options_categories_obj = get_categories();
	$options_categories[''] = 'Select a Category';
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

	//-------------------------------
	//SITE TITLE SECTION
	//-------------------------------
	
	//LOGO
	$wp_customize->add_setting( 'themolitor_customizer_logo' );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'themolitor_customizer_logo', array(
    	'label'    => __('Logo', 'themolitor'),
    	'section'  => 'title_tagline',
    	'settings' => 'themolitor_customizer_logo',
    	'priority' => 1
	)));
	
	//CONTACT INFO
    $wp_customize->add_setting( 'themolitor_customizer_contact', array(
		'default' => '<a target="_blank" href="http://tinyurl.com/p5f3y3u">1000 4th Avenue Seattle, WA 98104</a> <br /> 1.234.567.8910  &nbsp;/&nbsp;  <a target="_blank" href="http://twitter.com/themolitor">@theMOLITOR</a>'
	));
	$wp_customize->add_control( new Example_Customize_Textarea_Control( $wp_customize, 'themolitor_customizer_contact', array(
   		'label'   => __( 'Contact Info', 'themolitor'),
    	'section' => 'title_tagline',
    	'settings'   => 'themolitor_customizer_contact',
    	'priority' => 100
	)));
	
	//-------------------------------
	//COLORS SECTION
	//-------------------------------
	
	//TEXT COLOR
	$wp_customize->add_setting( 'themolitor_customizer_text_color', array(
		'default' => '#666666'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'themolitor_customizer_text_color', array(
		'label'   => __( 'Text Color', 'themolitor'),
		'section' => 'colors',
		'settings'   => 'themolitor_customizer_text_color',
    	'priority' => 1
	)));
	
	//H TAG COLOR
	$wp_customize->add_setting( 'themolitor_customizer_h_color', array(
		'default' => '#333333'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'themolitor_customizer_h_color', array(
		'label'   => __( 'Text Heading Color', 'themolitor'),
		'section' => 'colors',
		'settings'   => 'themolitor_customizer_h_color',
    	'priority' => 2
	)));
	
	//LINK COLOR
	$wp_customize->add_setting( 'themolitor_customizer_link_color', array(
		'default' => '#3b92c4'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'themolitor_customizer_link_color', array(
		'label'   => __( 'Link Color', 'themolitor'),
		'section' => 'colors',
		'settings'   => 'themolitor_customizer_link_color',
    	'priority' => 3
	)));
	
	//-------------------------------
	//HEADER IMAGE SECTION
	//-------------------------------
	
	//PARALLAX EFFECT
	$wp_customize->add_setting( 'themolitor_customizer_parallax', array(
    	'default' => 1
	));
	$wp_customize->add_control( 'themolitor_customizer_parallax', array(
    	'label' => 'Parallax Scrolling On',
    	'type' => 'checkbox',
    	'section' => 'header_image',
    	'settings' => 'themolitor_customizer_parallax',
    	'priority' => 1
	));
	
	//SLIDESHOW ON
	$wp_customize->add_setting( 'themolitor_customizer_slideshow_on', array(
    	'default' => 1
	));
	$wp_customize->add_control( 'themolitor_customizer_slideshow_on', array(
    	'label' => 'Slideshow On',
    	'type' => 'checkbox',
    	'section' => 'header_image',
    	'settings' => 'themolitor_customizer_slideshow_on',
    	'priority' => 2
	));
	
	//SLIDESHOW TIME
   	$wp_customize->add_setting( 'themolitor_customizer_slideshow_time',array(
   		'default' => '8'
   	));
	$wp_customize->add_control('themolitor_customizer_slideshow_time', array(
    	'label'    => __('Slideshow Pause Time (seconds)', 'themolitor'),
    	'section'  => 'header_image',
    	'settings' => 'themolitor_customizer_slideshow_time',
    	'type' => 'text',
    	'priority' => 3
	));
	
	//HEADER DISPLAY STYLE
	$wp_customize->add_setting('themolitor_customizer_display', array(
	    'capability'     => 'edit_theme_options',
	    'default'        => 'standard-display'
	));
	$wp_customize->add_control( 'themolitor_customizer_display', array(
 	   	'label'   => __('Header Display Style','themolitor'),
		'section' => 'header_image',
   	 	'type'    => 'select',
   	 	'choices' => array('large-display' => 'Large','standard-display' => 'Standard'),
   	 	'settings' => 'themolitor_customizer_display',
    	'priority' => 4
	));

	//COLOR	
	$wp_customize->add_setting( 'themolitor_customizer_bg_color', array(
		'default' => '#1a1a1a'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'themolitor_customizer_bg_color', array(
		'label'   => __( 'Overlay Color', 'themolitor'),
		'section' => 'header_image',
		'settings'   => 'themolitor_customizer_bg_color',
    	'priority' => 5
	)));
	
	//OPACITY
   	$wp_customize->add_setting( 'themolitor_customizer_bg_opacity',array(
   		'default' => '.5'
   	));
	$wp_customize->add_control( 'themolitor_customizer_bg_opacity', array(
	    'type'        => 'range',
	    'priority'    => 6,
	    'section'     => 'header_image',
	    'settings' => 'themolitor_customizer_bg_opacity',
	    'label'       => 'Overlay Transparency',
	    'input_attrs' => array(
	        'min'   => 0,
	        'max'   => 1,
	        'step'  => .05,
	        'class' => 'test-class test',
	        'style' => 'color: #0a0',
	    ),
	));
	
	
	//-------------------------------
	//GENERAL SECTION
	//-------------------------------

	//ADD GENERAL SECTION
	$wp_customize->add_section( 'themolitor_customizer_general_section', array(
		'title' => __( 'General', 'themolitor' ),
		'priority' => 198
	));
	
	//AUTO LOAD POSTS
	$wp_customize->add_setting( 'themolitor_customizer_autoload', array(
    	'default' => 1
	));
	$wp_customize->add_control( 'themolitor_customizer_autoload', array(
    	'label' => 'Automatically load more posts',
    	'type' => 'checkbox',
    	'section' => 'themolitor_customizer_general_section',
    	'settings' => 'themolitor_customizer_autoload',
    	'priority' => 1
	));
	
	//COLUMN COUNT
    $wp_customize->add_setting( 'themolitor_customizer_columns', array(
    	'default' => '2'
	));
	$wp_customize->add_control('themolitor_customizer_columns', array(
   		'label'   => __( 'Column Count for Content', 'themolitor'),
    	'section' => 'themolitor_customizer_general_section',
    	'settings'   => 'themolitor_customizer_columns',
    	'type' => 'text',
    	'priority' => 2
	));
	
	
	//-------------------------------
	//FOOTER SECTION
	//-------------------------------

	//ADD FOOTER SECTION
	$wp_customize->add_section( 'themolitor_customizer_footer_section', array(
		'title' => __( 'Footer', 'themolitor' ),
		'priority' => 199
	));
	
	//FOOTER TEXT
    $wp_customize->add_setting( 'themolitor_customizer_footer',array(
    	'default' => 'Handcrafted by <a target="_blank" href="http://themolitor.com">THE MOLITOR</a>'
    ));
	$wp_customize->add_control('themolitor_customizer_footer', array(
   		'label'   => __( 'Footer Text', 'themolitor'),
    	'section' => 'themolitor_customizer_footer_section',
    	'settings'   => 'themolitor_customizer_footer',
    	'type' => 'text',
    	'priority' => 1
	));
	
	//-------------------------------
	//GOOGLE FONT SECTION
	//-------------------------------

	//ADD GOOGLE FONT SECTION
	$wp_customize->add_section( 'themolitor_customizer_googlefont_section', array(
		'title' => __( 'Google Font', 'themolitor' ),
		'description' => 'For available fonts, visit <a target="_blank" href="http://google.com/fonts">google.com/fonts</a> ',
		'priority' => 200
	));
	
	//GOOGLE KEYWORD
    $wp_customize->add_setting( 'themolitor_customizer_google_key', array(
    	'default' => 'Open Sans'
	));
	$wp_customize->add_control('themolitor_customizer_google_key', array(
   		'label'   => __( 'Font Name', 'themolitor'),
    	'section' => 'themolitor_customizer_googlefont_section',
    	'settings'   => 'themolitor_customizer_google_key',
    	'type' => 'text',
    	'priority' => 1
	));
	
	//GOOGLE WEIGHT
    $wp_customize->add_setting( 'themolitor_customizer_google_weight', array(
    	'default' => '400,600'
	));
	$wp_customize->add_control('themolitor_customizer_google_weight', array(
   		'label'   => __( 'Font Weight', 'themolitor'),
    	'section' => 'themolitor_customizer_googlefont_section',
    	'settings'   => 'themolitor_customizer_google_weight',
    	'type' => 'text',
    	'priority' => 2
	));
			
	//-------------------------------
	//CUSTOM CSS SECTION
	//-------------------------------
	
	//ADD CSS SECTION
	$wp_customize->add_section( 'themolitor_customizer_custom_css', array(
		'title' => __( 'CSS', 'themolitor' ),
		'priority' => 201
	));
			
	//CUSTOM CSS
    $wp_customize->add_setting( 'themolitor_customizer_css');
	$wp_customize->add_control( new Example_Customize_Textarea_Control( $wp_customize, 'themolitor_customizer_css', array(
   		'label'   => __( 'Custom CSS', 'themolitor'),
    	'section' => 'themolitor_customizer_custom_css',
    	'settings'   => 'themolitor_customizer_css',
    	'priority' => 1
	)));
	
}