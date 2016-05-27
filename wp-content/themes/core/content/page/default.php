<?php // Featured Image
if ( has_post_thumbnail() ) { ?>
	<figure class="page-featured-img">
		<?php the_post_thumbnail( 'core-full' ); ?>
   	</figure>
<?php } ?>

<?php // Content ?>
<div class="context-content">
	<?php the_content(); ?>
</div>
