<?php 

// adding custom post type - 
function custom_post_type_services() {
    $supports = array(
    'title', 
    'editor', 
    'author',
    'thumbnail', 
    'excerpt', 
    'custom-fields', 
    'comments',
    'revisions', 
    'post-formats',
    );
    $labels = array(
    'name' => _x('Services', 'plural'),
    'singular_name' => _x('Services', 'singular'),
    'menu_name' => _x('Services', 'admin menu'), // shows in admin panel
    'name_admin_bar' => _x('Services', 'admin bar'),
    'add_new' => _x('Add New Service', 'add new'),
    'add_new_item' => __('Add New Service'),
    'new_item' => __('New Service'),
    'edit_item' => __('Edit Services'),
    'view_item' => __('View Services'),
    'all_items' => __('All Services'),
    'search_items' => __('Search Services'),
    'not_found' => __('No Services found.'),
    );
    $args = array(
    'supports' => $supports,
    'labels' => $labels,
    'public' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'services'),
    'has_archive' => true,
    'hierarchical' => false,
    'show_in_menu' => true,  // Ensure this is set to true  
    'menu_icon' => 'dashicons-list-view',  
    );
    register_post_type('services', $args);
    }
// activation hook    
//  add_action('init', 'custom_post_type_services');


/**adding taxonomy to post type ~ services */
function add_custom_taxonomies() {
    // Add new "Category" taxonomy to Posts
    register_taxonomy('service_category', 'services', array(
      // Hierarchical taxonomy (like categories)
      'hierarchical' => true,
      'show_ui' => true,
      'show_admin_column' => true,  // Ensures the taxonomy shows up in the admin post listing

      // This array of options controls the labels displayed in the WordPress Admin UI
      'labels' => array(
        'name' => _x( 'Service Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Service Categories', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Service Categories' ),
        'all_items' => __("All Service's Categories"),
        'parent_item' => __( 'Parent Service Category' ),
        'parent_item_colon' => __( 'Parent Service Category:' ),
        'edit_item' => __( 'Edit Service Category' ),
        'update_item' => __( 'Update Service Category' ),
        'add_new_item' => __( 'Add New Service Category' ),
        'new_item_name' => __( 'New Service Category Name' ),
        'menu_name' => __( 'Service Category' ),
      ),
      // Control the slugs used for this taxonomy
      'rewrite' => array(
        'slug' => 'service-category', // This controls the base slug that will display before each term
        'with_front' => false, // Don't display the category base before "/Category/"
        'hierarchical' => true // This will allow URL's like "/Category/boston/cambridge/"
      ),
    ));
  }
// activation hook
// add_action( 'init', 'add_custom_taxonomies', 0 );


?>