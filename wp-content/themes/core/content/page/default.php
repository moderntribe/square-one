<?php // Featured Image
$options = [ 'wrapper_class' => 'page-featured-img' ];
the_tribe_image( get_post_thumbnail_id(), $options ); ?>

<?php // Content ?>
<div class="context-content">
	<?php the_content(); ?>
</div>
