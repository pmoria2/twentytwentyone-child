<?php



/**
 * Register Blocks
 * @package CoreFunctionality
 * @author	Bonkaroo
 * @since	1.0.0
 * @license	GPL-2.0
 * @link https://www.Bonkaroo.com
 **/

/*
* Creating a function to create our CPT
*/
 
function pmm_register_post_types() {

	$textDomain ='twentytwentyone';
 
	// Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'Hyperlinks', 'Post Type General Name', $textDomain ),
			'singular_name'       => _x( 'Hyperlink', 'Post Type Singular Name', $textDomain ),
			'menu_name'           => __( 'Hyperlinks', $textDomain ),
			'view_item'           => __( 'View Links', $textDomain ),
			'add_new_item'        => __( 'Add new list of Hyperlinks', $textDomain ),
			'add_new'             => __( 'Add New', $textDomain ),
			'edit_item'           => __( 'Edit list', $textDomain ),
			'update_item'         => __( 'Update Hyperlinks', $textDomain ),
			'search_items'        => __( 'Search Hyperlinks', $textDomain ),
			'not_found'           => __( 'Not Found', $textDomain ),
			'not_found_in_trash'  => __( 'Not found in Trash', $textDomain ),
		);
		 
	// Set other options for Custom Post Type
		 
		$args = array(
			'label'               => __( 'links', $textDomain ),
			'description'         => __( 'Links to organizations that support women', $textDomain ),
			'labels'              => $labels,
			// Features this CPT supports in Post Editor
			'supports'            => array( 'title', 'revisions', 'custom-fields'),
			// You can associate this CPT with a taxonomy or custom taxonomy. 
			'taxonomies'          => array( 'tag' ),
			/* A hierarchical CPT is like Pages and can have
			* Parent and child items. A non-hierarchical CPT
			* is like Posts.
			*/ 
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'show_in_custom_fields'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest' => true,
	 
		);
		 
		// Registering your Custom Post Type
		register_post_type( 'network_links', $args );
	 
	}
	 
	/* Hook into the 'init' action so that the function
	* Containing our post type registration is not 
	* unnecessarily executed. 
	*/
	 
	add_action( 'init', 'pmm_register_post_types', 0 );


	
?>
