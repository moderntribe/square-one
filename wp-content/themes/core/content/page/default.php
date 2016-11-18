<?php // Featured Image
$options = [ 'wrapper_class' => 'page__image' ];
the_tribe_image( get_post_thumbnail_id(), $options ); ?>

<?php // Content ?>
<div class="t-content">
	<?php the_content(); ?>
</div>
