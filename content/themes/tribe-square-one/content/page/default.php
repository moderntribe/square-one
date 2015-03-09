<?php // Title ?>
<h1 class="page-title" itemprop="name"><?php the_title(); ?></h1>

<?php // Featured Image
if ( has_post_thumbnail() ) { ?>
	<figure class="page-featured-img">
		<?php
			$attr = array(
				'class'	   => '',
				'itemprop' => 'thumbnailUrl'
			);
			echo get_the_post_thumbnail( get_the_ID(), 'full', $attr );
		?>
   	</figure><!-- .page-featured-img -->
<?php } ?>

<?php // Content ?>
<div itemprop="text">
	<?php the_content(); ?>
</div>