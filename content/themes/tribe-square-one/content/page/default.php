<?php // Featured Image
if ( has_post_thumbnail() ) { ?>
	<figure class="page-featured-img">
		<?php
			$attr = array( 'class' => '' );
			echo get_the_post_thumbnail( get_the_ID(), 'tribe-full', $attr );
		?>
   	</figure><!-- .page-featured-img -->
<?php } ?>

<?php // Content ?>
<div class="context-content">
	<?php the_content(); ?>
</div>