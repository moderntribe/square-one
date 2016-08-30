<?php // Featured Image
$options = [ 'wrapper_class' => 'page__image' ];
the_tribe_image( get_post_thumbnail_id(), $options ); ?>

<?php // Content ?>
<div class="t-typography">
	<?php the_content(); ?>
</div>
