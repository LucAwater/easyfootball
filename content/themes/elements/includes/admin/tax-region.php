<?php
//create a function that will attach our new 'member' taxonomy to the 'post' post type
function taxonomy_custom_region(){

   //set the name of the taxonomy
   $taxonomy = 'region';
   //set the post types for the taxonomy
   $object_type = 'product';

   //populate our array of names for our taxonomy
   $labels = array(
     'name'               => 'Region',
     'singular_name'      => 'Region',
     'search_items'       => 'Search Regions',
     'all_items'          => 'All Regions',
     'parent_item'        => 'Parent Region',
     'parent_item_colon'  => 'Parent Region:',
     'update_item'        => 'Update Region',
     'edit_item'          => 'Edit Region',
     'add_new_item'       => 'Add New Region',
     'new_item_name'      => 'New Region Name',
     'menu_name'          => 'Region'
   );

   //define arguments to be used
   $args = array(
     'labels'            => $labels,
     'hierarchical'      => true,
     'show_ui'           => true,
     'how_in_nav_menus'  => true,
     'public'            => true,
     'show_admin_column' => true,
     'query_var'         => true,
     'rewrite'           => array('slug' => 'region')
   );

   //call the register_taxonomy function
   register_taxonomy($taxonomy, $object_type, $args);
}
add_action('init','taxonomy_custom_region');
?>