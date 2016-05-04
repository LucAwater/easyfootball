<?php
// Content (variables)
$title = get_the_title();
$category = get_the_category( $post->ID );
( $category ) ? $category_link = get_category_link( $category[0]->term_id ) : $category_link = '';
( $category ) ? $category_name = $category[0]->cat_name : $category_name = '';
$content = wpautop( get_the_content() );
$permalink = get_the_permalink();
$date = get_the_date();
$thumb = get_the_post_thumbnail( $post->ID, 'medium' );
?>

<li>
  <div>
    <a href="<?php echo $category_link; ?>" class="post-category"><?php echo $category_name; ?></a>
    <a class="post-title" href="<?php echo $permalink; ?>"><?php echo $title; ?></a>
  </div>

  <?php echo $thumb; ?>

  <div>
    <p><?php echo $date; ?></p>
  </div>
</li>