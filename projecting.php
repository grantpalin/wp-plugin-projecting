<?php
/**
 * Plugin Name: Projecting
 * Plugin URI:  http://grantpalin.com
 * Description: Provides the means to create and organize projects being worked on currently or in the past.
 * Version:     1.0.0
 * Author:      Grant Palin
 * Author URI:  http://grantpalin.com
 * License:     GPLv3
 * Text Domain: projecting
 * Domain Path: /languages
 *
 * @package Projecting
 * @since 1.0.0
 * @version 1.0.0
 */

// Register Custom Post Type
function projecting_project() {
	$labels = array(
		'name'                  => _x( 'Projects', 'Post Type General Name', 'projecting' ),
		'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'projecting' ),
		'menu_name'             => __( 'Projects', 'projecting' ),
		'name_admin_bar'        => __( 'Project', 'projecting' ),
		'archives'              => __( 'Project Archives', 'projecting' ),
		'attributes'            => __( 'Project Attributes', 'projecting' ),
		'parent_item_colon'     => __( 'Parent Project:', 'projecting' ),
		'all_items'             => __( 'All Projects', 'projecting' ),
		'add_new_item'          => __( 'Add New Project', 'projecting' ),
		'add_new'               => __( 'Add New', 'projecting' ),
		'new_item'              => __( 'New Project', 'projecting' ),
		'edit_item'             => __( 'Edit Project', 'projecting' ),
		'update_item'           => __( 'Update Project', 'projecting' ),
		'view_item'             => __( 'View Project', 'projecting' ),
		'view_items'            => __( 'View Projects', 'projecting' ),
		'search_items'          => __( 'Search Project', 'projecting' ),
		'not_found'             => __( 'Not found', 'projecting' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'projecting' ),
		'featured_image'        => __( 'Featured Image', 'projecting' ),
		'set_featured_image'    => __( 'Set featured image', 'projecting' ),
		'remove_featured_image' => __( 'Remove featured image', 'projecting' ),
		'use_featured_image'    => __( 'Use as featured image', 'projecting' ),
		'insert_into_item'      => __( 'Insert into project', 'projecting' ),
		'uploaded_to_this_item' => __( 'Uploaded to this project', 'projecting' ),
		'items_list'            => __( 'Projects list', 'projecting' ),
		'items_list_navigation' => __( 'Projects list navigation', 'projecting' ),
		'filter_items_list'     => __( 'Filter Projects list', 'projecting' ),
	);
	$rewrite = array(
		'slug'                  => 'projects',
		'with_front'            => false,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Project', 'projecting' ),
		'description'           => __( 'Managing and displaying projects', 'projecting' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-portfolio',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
	);

	register_post_type( 'project', $args );
}
add_action( 'init', 'projecting_project', 0 );

function projecting_enqueue_styles() {
	wp_enqueue_style('projecting-admin', plugins_url('assets/projecting-admin.css', __FILE__), array(), '1.0.0');
}
add_action('admin_enqueue_scripts', 'projecting_enqueue_styles');

// Add custom content types to the 'At a Glance' dashboard widget
function projecting_right_now_content_table_end() {
	$num_posts = wp_count_posts( 'project' );

	// Only proceed if one or more published instances of this content type
	if ( $num_posts && $num_posts->publish ) {
		// Create the plural or singular text matching the count, then add the count to the string
		$text = sprintf( _n( '%s Project', '%s Projects', $num_posts->publish ), $num_posts->publish );

		$post_type_object = get_post_type_object( 'project' );

		// Output a link only if the current user is able to edit content of this type
		if ( $post_type_object && current_user_can( $post_type_object->cap->edit_posts ) ) {
			printf( '<li class="project-count"><a href="edit.php?post_type=%1$s">%1$s</a></li>', $text );
		} else { // Else output just the number and text
			printf( '<li class="project-count"><span>%1$s</span></li>', $text );
		}
	}
}
add_action( 'dashboard_glance_items' , 'projecting_right_now_content_table_end' );
