<?php
//create a function that will attach our new 'member' taxonomy to the 'post' post type
function taxonomy_custom_league(){

   //set the name of the taxonomy
   $taxonomy = 'league';
   //set the post types for the taxonomy
   $object_type = 'product';

   //populate our array of names for our taxonomy
   $labels = array(
     'name'               => 'League',
     'singular_name'      => 'League',
     'search_items'       => 'Search Leagues',
     'all_items'          => 'All Leagues',
     'parent_item'        => 'Parent League',
     'parent_item_colon'  => 'Parent League:',
     'update_item'        => 'Update League',
     'edit_item'          => 'Edit League',
     'add_new_item'       => 'Add New League',
     'new_item_name'      => 'New League Name',
     'menu_name'          => 'League'
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
     'rewrite'           => array('slug' => 'league')
   );

   //call the register_taxonomy function
   register_taxonomy($taxonomy, $object_type, $args);
}
add_action('init','taxonomy_custom_league');
?>