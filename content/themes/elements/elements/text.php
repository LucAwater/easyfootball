<?php
$title = get_sub_field( 'title' );
$text = wpautop( get_sub_field('text') );

// Classes
$class_section = 'text';
$class_header = 'section-header';
$class_body = 'section-body';

// Build section
section_start( $class_section );

  // Header
  section_header( $class_header, $title );

  // Body
  section_body_start( $class_body );

    /*
     * Body content
     * This is the flexible part, that is different for each element
     */
    echo $text;

  section_body_end();

section_end();
?>
