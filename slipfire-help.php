<?php
/*
 * Plugin Name: Help System
 * Plugin URI: http://slipfire.com
 * Description: A simple help and documentation system
 * Author: <a href="http://slipfire.com">SlipFire</a>
 * Version: .22
 * Author URI: http://slipfire.com
 * License: GPL2+
 */
 
 
 
// Only run in admin 
if (is_admin() ) {
 
/**
 * Define Path constants
 */
define( 'SLIPFIRE_HELP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SLIPFIRE_HELP_PLUGIN_NAME', trim( dirname( SLIPFIRE_HELP_PLUGIN_BASENAME ), '/' ) );
define( 'SLIPFIRE_HELP_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . SLIPFIRE_HELP_PLUGIN_NAME );
define( 'SLIPFIRE_HELP_PLUGIN_URL', WP_PLUGIN_URL . '/' . SLIPFIRE_HELP_PLUGIN_NAME );
define( 'SLIPFIRE_HELP_CSS_URL', SLIPFIRE_HELP_PLUGIN_URL . '/css' );
define( 'SLIPFIRE_HELP_IMG_URL', SLIPFIRE_HELP_PLUGIN_URL . '/img' );
 
/**
 * Add CSS for help page
 */
 function sfire_help_css() {
    echo '<link rel="stylesheet" type="text/css" href="' . SLIPFIRE_HELP_CSS_URL . '/help-styles.css" media="screen" />';
}
add_action('admin_head', 'sfire_help_css');


function sfire_help_system() 
{
  $labels = array(
    'name' => _x('Help Items', 'post type general name'),
    'singular_name' => _x('Help Item', 'post type singular name'),
    'add_new' => _x('Add New', 'Help Item'),
    'add_new_item' => __('Add New Help Item'),
    'edit_item' => __('Edit Help Item'),
    'new_item' => __('New Help Item'),
    'view_item' => __('View Help Item'),
    'search_items' => __('Search Help Items'),
    'not_found' =>  __('No help items found'),
    'not_found_in_trash' => __('No help items found in Trash'), 
    'parent_item_colon' => '',
    'menu_name' => 'Help Items'
  );
  $args = array(
    'labels' => $labels,
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => true, 
    'show_in_menu' => true,
	'show_in_nav_menus' => false,
    'query_var' => false,
    'rewrite' => false,
	'exclude_from_search' => true,
    'capability_type' => 'post',
    'has_archive' => false, 
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','editor','revisions')
  ); 
  register_post_type('help_system',$args);
}
add_action( 'init', 'sfire_help_system' );
 
 
 function sfire_help_system_menu() {

    add_submenu_page('index.php', __('Help','slipfire'), __('Help','slipfire'), 'manage_options', 'online_help', 'sfire_help_do_page');
};
add_action('admin_menu', 'sfire_help_system_menu');


/* Create Help Page
 *
 */
function sfire_help_do_page() {

	global $post;
	$args = array(
				'numberposts' => '999999',
				'post_type' => 'help_system',
				'orderby' => 'post_date',
				'order' => 'ASC'
			);
?>

<div class="wrap slipfire-help">
	<h2>Help for:&nbsp;<?php bloginfo('name');
		if (current_user_can ('edit_others_posts') ) { ?>
			<a href="<?php bloginfo('wpurl');?>/wp-admin/post-new.php?post_type=help_system" title="Add New Help Item" class="button add-new-h2">Add New</a>
		<?php
		} ?>
	</h2>
	
	<?php // If no help items have been created let the user know.
		$myposts = get_posts( $args );
		if ($myposts == false) { echo 'No Help items'; exit(); } ?>
		
	<div id="toc">
		<h3>Table of Contents</h3>
		<ul>
			<?php
			$myposts = get_posts( $args );
			foreach( $myposts as $post ) :	setup_postdata($post); ?>
				<li><a href="#<?php the_id(); ?>"><?php the_title(); ?><?php get_the_taxonomies(', ') ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	
	<div id="answer">
			<?php
			$myposts = get_posts( $args );
			foreach( $myposts as $post ) :	setup_postdata($post); ?>
				<div id="<?php the_id(); ?>" style="float: left; width: 100%;">
						<?php if (current_user_can ('edit_others_posts') ) {
							echo '<h3><a title="Edit Help Item: '. get_the_title() . '" class="edit-help" href="/wp-admin/post.php?post=' . get_the_id() . '&action=edit">' . get_the_title() . '</a></h3>';
						} else {
							echo '<h3>' . get_the_title() . '</h3>';
						} ?>

					<?php the_content();?>
				</div>
			<?php endforeach; ?>
	</div>

</div>
				
				

	
	
<?php
}



} // End is_admin