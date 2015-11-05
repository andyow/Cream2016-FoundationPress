<?php
/*=============================================
=                RE-WRITE RULES               =
 =============================================*/
/**
*
* Add the BRANDS post type
*
**/
add_action('init', 'cptui_register_my_cpt_brands');
function cptui_register_my_cpt_brands() {
	register_post_type('brands', array(
		'label' => 'Brands',
		'description' => '',
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-tag',
		'hierarchical' => true,
		//'rewrite' => true,
		'rewrite' => array(
			'hierarchical' => true,
			'slug' => 'brands',
			'with_front' => false),
		'query_var' => true,
		'has_archive' => true,
		'supports' => array('title','editor','thumbnail','page-attributes'),
		'labels' => array (
		  'name' => 'Brands',
		  'singular_name' => 'Brand',
		  'menu_name' => 'Brands',
		  'add_new' => 'Add Brand',
		  'add_new_item' => 'Add New Brand',
		  'edit' => 'Edit',
		  'edit_item' => 'Edit Brand',
		  'new_item' => 'New Brand',
		  'view' => 'View Brand',
		  'view_item' => 'View Brand',
		  'search_items' => 'Search Brands',
		  'not_found' => 'No Brands Found',
		  'not_found_in_trash' => 'No Brands Found in Trash',
		  'parent' => 'Parent Brand',
		)
	) );
}

/**
*
* Add the EVENTS post type
*
**/
add_action('init', 'cptui_register_my_cpt_events');
function cptui_register_my_cpt_events() {
	register_post_type('events', array(
		'label' => 'Events',
		'description' => '',
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-calendar-alt',
		'hierarchical' => false,
		'rewrite' => array(
		    'slug' => 'brands/%brands%/events',
		    'with_front' => false
		    ),
		'query_var' => true,
		'has_archive' => true,
		'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes','post-formats'),
		'labels' => array (
		  'name' => 'Events',
		  'singular_name' => 'Event',
		  'menu_name' => 'Events',
		  'add_new' => 'Add Event',
		  'add_new_item' => 'Add New Event',
		  'edit' => 'Edit',
		  'edit_item' => 'Edit Event',
		  'new_item' => 'New Event',
		  'view' => 'View Event',
		  'view_item' => 'View Event',
		  'search_items' => 'Search Events',
		  'not_found' => 'No Events Found',
		  'not_found_in_trash' => 'No Events Found in Trash',
		  'parent' => 'Parent Event',
		)
	) );
}

/**
*
* Add the NEWS post type
*
**/

add_action('init', 'cptui_register_my_cpt_news');
function cptui_register_my_cpt_news() {
	register_post_type('news', array(
		'label' => 'News',
		'description' => '',
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-format-aside',
		'hierarchical' => false,
		'rewrite' => array(
		    'slug' => 'brands/%brands%/news',
		    'with_front' => false
		    ),
		'query_var' => true,
		'has_archive' => true,
		'supports' => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes','post-formats'),
		'labels' => array (
		  'name' => 'News',
		  'singular_name' => 'News',
		  'menu_name' => 'News',
		  'add_new' => 'Add News',
		  'add_new_item' => 'Add New News',
		  'edit' => 'Edit',
		  'edit_item' => 'Edit News',
		  'new_item' => 'New News',
		  'view' => 'View News',
		  'view_item' => 'View News',
		  'search_items' => 'Search News',
		  'not_found' => 'No News Found',
		  'not_found_in_trash' => 'No News Found in Trash',
		  'parent' => 'Parent News',
		)
	) );
}

/***********************************************************************************************************
*
* CHANGE THE PERMALINKS - ADD IN %brands%
* 
* http://shibashake.com/wordpress-theme/add-custom-taxonomy-tags-to-your-wordpress-permalinks
*
***********************************************************************************************************/

/**
*
* Add the %brands% rewrite tag
*
**/
add_action('init', 'brands_rewrite_tag', 10, 0);
function brands_rewrite_tag() {
	add_rewrite_tag("%brands%", '([^/]+)', "brands=");
}

add_filter('post_link', 'rating_permalink', 10, 3);
add_filter('post_type_link', 'rating_permalink', 10, 3);
 
function rating_permalink($permalink, $post_id, $leavename) {
    if (strpos($permalink, '%brands%') === FALSE) return $permalink;
     
        // Get post
        $post = get_post($post_id);
        if (!$post) return $permalink;
 
        // Get taxonomy terms
        $terms = wp_get_object_terms($post->ID, 'brands');   
        //if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) $taxonomy_slug = $terms[0]->slug;
        if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])){
        	$parent_term = basename( get_permalink($terms[0]->parent) ); 
        	$taxonomy_slug = $parent_term . "/" . $terms[0]->slug;
        } else { $taxonomy_slug = 'not-rated'; }
 
    return str_replace('%brands%', $taxonomy_slug, $permalink);
}


/**
*
* Add the rewrite rules to support events, news and brands
*
**/
add_action( 'init', 'cream_rewrite_rules' );
function cream_rewrite_rules() {
	/* Add in the EVENTS rules */
	add_rewrite_rule( 'brands/([^/]+)/([^/]+)/events/([^/]*)?$', 'index.php?post_type=events&name=$matches[3]', 'top' ); //Adds an event with a child level brand
	add_rewrite_rule( 'brands/([^/]+)/events/([^/]*)?$', 'index.php?post_type=events&name=$matches[2]', 'top' ); //Adds an event with a parent level brand
	add_rewrite_rule( 'brands/([^/]+)/([^/]+)/events?$', 'index.php?brands=$matches[2]&post_type=events&cpt_onomy_archive=1', 'top' ); //Adds a child brand event archive page
	add_rewrite_rule( 'brands/([^/]+)/events/?$', 'index.php?brands=$matches[1]&post_type=events&cpt_onomy_archive=1', 'top' ); //Adds a parent brand event archive page

	/* Add in the NEWS rules */
	add_rewrite_rule( 'brands/([^/]+)/([^/]+)/news/([^/]*)?$', 'index.php?post_type=news&name=$matches[3]', 'top' ); //Adds news with a child level brand
	add_rewrite_rule( 'brands/([^/]+)/news/([^/]*)?$', 'index.php?post_type=news&name=$matches[2]', 'top' ); //Adds news with a parent level brand
	add_rewrite_rule( 'brands/([^/]+)/([^/]+)/news?$', 'index.php?brands=$matches[2]&post_type=news&cpt_onomy_archive=1', 'top' ); //Adds a child brand news archive page
	add_rewrite_rule( 'brands/([^/]+)/news/?$', 'index.php?brands=$matches[1]&post_type=news&cpt_onomy_archive=1', 'top' ); //Adds a parent brand news archive page

	/* Add in the BRANDS rules */
	add_rewrite_rule( 'brands/([^/]+)/([^/]+)/?$', 'index.php?brands=$matches[2]&cpt_onomy_archive=1', 'top' ); //Adds a child brand archive page (news and events)
	add_rewrite_rule( 'brands/([^/]+)/?$', 'index.php?brands=$matches[1]&cpt_onomy_archive=1', 'top' ); //Adds a parent brand archive page (news and events)
    
    add_rewrite_rule( 'brands/?$', 'index.php?post_type=brands', 'top' ); //Adds an overall brands archive page
    add_rewrite_rule( 'events/?$', 'index.php?post_type=events', 'top' ); //Adds an overall events archive page
    add_rewrite_rule( 'news/?$', 'index.php?post_type=news', 'top' ); //Adds an overall news archive page
}