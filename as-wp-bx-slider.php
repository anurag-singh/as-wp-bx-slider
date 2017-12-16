<?php
/*
Plugin Name: As Wp Bx Slider
Plugin URI: http://wordpress.org/
Description: A simple plugin to add "Bx Slider" support in wordpress theme.
Author: Anurag Singh
Version: 1.0
Author URI: http://wordpress.org/
*/

// Ref - https://bxslider.com/
// Ref - https://github.com/stevenwanderski/bxslider-4

// #1 Add new image sizeof
add_image_size('bx_slider_thumb', 120, 120, true);
add_image_size('bx_slider_full', 400, 400, true);


// #2 Enqueue Scripts for plugin
add_action('wp_enqueue_scripts', 'load_bx_slider_scripts');
function load_bx_slider_scripts() {
	// 	wp_enqueue_script('bx-slider', '//cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js', array('jquery'), '4.2.12', true);
	wp_enqueue_script( 'bx-slider', plugins_url( 'as-wp-bx-slider/assets/js/jquery.bxslider.js' ), array('jquery'), '4.2.12', true  );
	wp_enqueue_script( 'bx-functions', plugins_url( 'as-wp-bx-slider/assets/js/bx-functions.js' ), array('jquery'), '1.0', true  );

	wp_enqueue_style('bx-slider', plugin_dir_url(__FILE__) . 'assets/css/style-bxslider.css');
}


// #3 Register CPT & Taxo
add_action( 'init', 'add_new_slider_cpt' );
function add_new_slider_cpt() {
	$labels = array(
		'name'               => _x( 'Slides', 'post type general name', 'as-wp-bx-slider' ),
		'singular_name'      => _x( 'Slide', 'post type singular name', 'as-wp-bx-slider' ),
		'menu_name'          => _x( 'Bx Slider', 'admin menu', 'as-wp-bx-slider' ),
		'name_admin_bar'     => _x( 'Slide', 'add new on admin bar', 'as-wp-bx-slider' ),
		'add_new'            => _x( 'Add New', 'Slide', 'as-wp-bx-slider' ),
		'add_new_item'       => __( 'Add New Slide', 'as-wp-bx-slider' ),
		'new_item'           => __( 'New Slide', 'as-wp-bx-slider' ),
		'edit_item'          => __( 'Edit Slide', 'as-wp-bx-slider' ),
		'view_item'          => __( 'View Slide', 'as-wp-bx-slider' ),
		'all_items'          => __( 'All Slides', 'as-wp-bx-slider' ),
		'search_items'       => __( 'Search Slides', 'as-wp-bx-slider' ),
		'parent_item_colon'  => __( 'Parent Slides:', 'as-wp-bx-slider' ),
		'not_found'          => __( 'No Slides found.', 'as-wp-bx-slider' ),
		'not_found_in_trash' => __( 'No Slides found in Trash.', 'as-wp-bx-slider' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'as-wp-bx-slider' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'Slide' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		// 	'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		'supports'           => array( 'title','thumbnail', )
	);

	register_post_type( 'slide', $args );
}


// #4 Add shortcode 
add_shortcode('bx-slider', 'render_bx_slider');
function render_bx_slider() {
	$sliderArg = array(
		'post_type' => 'slide'						// Set post-type to 'slide'
	);
	$bxSlider = new WP_Query( $sliderArg ); 		// Store query data in var 
	
	if($bxSlider->have_posts()) : 					// If post found

        $html = '<ul class="bxslider">';			// Setup main container for 'bx slider'
        
            while ($bxSlider->have_posts()) : 		// #Start a loop to fetch each post 

            	$bxSlider->the_post();	 			// Setup post data
        
                $attachedImageId = get_post_thumbnail_id(get_the_ID());			// Get the img ID
                $attachedImageSrc = wp_get_attachment_url($attachedImageId);	// Get img source
                $imageHtml = '<img src="' . $attachedImageSrc . '" alt="' . get_the_title() . '" title="' . get_the_title() . '">';				// Setup img tag

                $html .= '<li>'.$imageHtml.'</li>';								// Add each image in var
        
            endwhile;								// #Exit from loop, when all post fetched
        
        $html .= '</ul>';							// Close main container
	
	endif;											// Close 'if' condition
	return $html;									// Return the var 
}
