<article class="<?php echo get_post_type() .'-'. get_the_ID(); ?> loop-item" itemscope itemtype="http://schema.org/BlogPosting">

	<header>
		
		<?php // Title ?>
		<h3 class="loop-item-title" itemprop="headline">
			<a href="<?php the_permalink(); ?>" itemprop="url" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h3><!-- .loop-item-title -->

	</header>

	<?php // Featured Image
	if ( has_post_thumbnail() ) { ?>
		<figure class="loop-item-featured-img">
			<a href="<?php the_permalink(); ?>" itemprop="url" rel="bookmark">
    			<?php
					$attr = array(
						'class'	   => '',
						'itemprop' => 'thumbnailUrl'
					);
					echo get_the_post_thumbnail( get_the_ID(), 'full', $attr );
				?>
			</a>
    	</figure><!-- .loop-item-featured-img -->
	<?php } ?>

	<?php // Content ?>
	<div itemprop="description">
		<?php the_content(); ?>
	</div>

</article><!-- .loop-item -->