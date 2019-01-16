<?php
/*
 * Plugin Name: Help System
 * Plugin URI: http://slipfire.com
 * Description: A simple help and documentation system for WPNYC Meetup presentation
 * Author: <a href="http://slipfire.com">SlipFire</a>
 * Version: 1.0
 * Author URI: http://slipfire.com
 */
 
 
 
// Only run in admin 
if (!is_admin()) {
    return;
}
 


add_action( 'init', 'help_system_cpt' );
add_action( 'init', 'help_system_create_help_taxonomies', 0 );

add_action( 'init', 'help_system_remove_post_type_support', 10 );
add_action( 'admin_menu', 'help_system_remove_meta_boxes' );

add_action( 'edit_form_after_title', 'help_system_add_help_content' );
add_action( 'admin_footer', 'help_system_css' );





// Register Custom Post Type
function help_system_cpt() {

	$labels = array(
		'name'                  => _x( 'Help Items', 'Post Type General Name', 'help-system' ),
		'singular_name'         => _x( 'Help Item', 'Post Type Singular Name', 'help-system' ),
		'menu_name'             => __( 'Help Item', 'help-system' ),
		'name_admin_bar'        => __( 'Help Items', 'help-system' ),
		'archives'              => __( 'Help Items', 'help-system' ),
		'attributes'            => __( 'Help Item Attributes', 'help-system' ),
		'parent_item_colon'     => __( 'Parent Help Item', 'help-system' ),
		'all_items'             => __( 'All Help Items', 'help-system' ),
		'add_new_item'          => __( 'Add New Help Item', 'help-system' ),
		'add_new'               => __( 'Add New', 'help-system' ),
		'new_item'              => __( 'New Help Item', 'help-system' ),
		'edit_item'             => __( 'Edit Help Item', 'help-system' ),
		'update_item'           => __( 'Update Help Item', 'help-system' ),
		'view_item'             => __( 'View Help Item', 'help-system' ),
		'view_items'            => __( 'View Help Items', 'help-system' ),
		'search_items'          => __( 'Search Help Items', 'help-system' ),
		'not_found'             => __( 'Not found', 'help-system' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'help-system' ),
		'featured_image'        => __( 'Featured Image', 'help-system' ),
		'set_featured_image'    => __( 'Set featured image', 'help-system' ),
		'remove_featured_image' => __( 'Remove featured image', 'help-system' ),
		'use_featured_image'    => __( 'Use as featured image', 'help-system' ),
		'insert_into_item'      => __( 'Insert into item', 'help-system' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Help Item', 'help-system' ),
		'items_list'            => __( 'Help Items list', 'help-system' ),
		'items_list_navigation' => __( 'Help Items list navigation', 'help-system' ),
		'filter_items_list'     => __( 'Filter items list', 'help-system' ),
  );
  
	$args = array(
		'label'                 => __( 'Help Item', 'help-system' ),
		'description'           => __( 'Post Type Description', 'help-system' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'revisions' ),
		'hierarchical'          => true,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-editor-help',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'rewrite'               => false,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	);
	register_post_type( 'help_system', $args );

}

  
  // Register Custom Taxonomy
function help_system_create_help_taxonomies() {

	$labels = array(
		'name'                       => _x( 'Sections', 'Taxonomy General Name', 'help-system' ),
		'singular_name'              => _x( 'Section', 'Taxonomy Singular Name', 'help-system' ),
		'menu_name'                  => __( 'Sections', 'help-system' ),
		'all_items'                  => __( 'All Sections', 'help-system' ),
		'parent_item'                => __( 'Parent Section', 'help-system' ),
		'parent_item_colon'          => __( 'Parent Item:', 'help-system' ),
		'new_item_name'              => __( 'New Section', 'help-system' ),
		'add_new_item'               => __( 'Add New Section', 'help-system' ),
		'edit_item'                  => __( 'Edit Section', 'help-system' ),
		'update_item'                => __( 'Update Section', 'help-system' ),
		'view_item'                  => __( 'View Section', 'help-system' ),
		'separate_items_with_commas' => __( 'Separate Sections with commas', 'help-system' ),
		'add_or_remove_items'        => __( 'Add or remove Sections', 'help-system' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'help-system' ),
		'popular_items'              => __( 'Popular Sections', 'help-system' ),
		'search_items'               => __( 'Search Sections', 'help-system' ),
		'not_found'                  => __( 'Not Found', 'help-system' ),
		'no_terms'                   => __( 'No Sections', 'help-system' ),
		'items_list'                 => __( 'Sections list', 'help-system' ),
		'items_list_navigation'      => __( 'Sections list navigation', 'help-system' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => false,
		'show_in_rest'               => true,
	);
	register_taxonomy( 'help_section', array( 'help_system' ), $args );

}


function help_system_remove_post_type_support() {

  if(!current_user_can('manage_options')) {
    remove_post_type_support( 'help_system', 'title' );
    remove_post_type_support( 'help_system', 'editor' );
	remove_post_type_support( 'help_system', 'revisions' );
  }
 
}

function help_system_remove_meta_boxes() {

	if(!current_user_can('manage_options')) {
		remove_meta_box('submitdiv', 'help_system', 'side');
		remove_meta_box('help_sectiondiv', 'help_system', 'side');
	}
}


function help_system_add_help_content() {

	if(!current_user_can('manage_options')) {
		$post_id = $_GET['post'];

		$post_content = get_post($post_id);
		$content = $post_content->post_content;
		$content = apply_filters('the_content',$content);
		
		echo '<h1>' . $post_content->post_title . '</h1>';

		$sections = wp_get_post_terms($post_id, 'help_section');
		echo '<strong>Sections:</strong>';
		foreach($sections as $key => $section) {
			echo $section->name;
		}

		echo apply_filters('the_content',$content);
	}
}


function help_system_css() {

    if (!current_user_can('manage_options')) {
        ?>

		<style>
		.metabox-holder .postbox-container .empty-container {
			display: none;
		}
		</style>

		<?php
    }

}
