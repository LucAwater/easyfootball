<?php
//create a function that will attach our new 'member' taxonomy to the 'post' post type
function taxonomy_custom_team(){

   //set the name of the taxonomy
   $taxonomy = 'team';
   //set the post types for the taxonomy
   $object_type = 'product';

   //populate our array of names for our taxonomy
   $labels = array(
     'name'               => 'Team',
     'singular_name'      => 'Team',
     'search_items'       => 'Search Teams',
     'all_items'          => 'All Teams',
     'parent_item'        => 'Parent Team',
     'parent_item_colon'  => 'Parent Team:',
     'update_item'        => 'Update Team',
     'edit_item'          => 'Edit Team',
     'add_new_item'       => 'Add New Team',
     'new_item_name'      => 'New Team Name',
     'menu_name'          => 'Team'
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
     'rewrite'           => array('slug' => 'team')
   );

   //call the register_taxonomy function
   register_taxonomy($taxonomy, $object_type, $args);
}
add_action('init','taxonomy_custom_team');
?>