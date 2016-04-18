<?php // Featured Image
if ( has_post_thumbnail() ) { ?>
	<figure class="page-featured-img">
		<?php the_post_thumbnail( 'tribe-full' ); ?>
   	</figure><!-- .page-featured-img -->
<?php } ?>

<?php // Content ?>
<div class="context-content">
	<?php the_content(); ?>
</div>