<article class="<?php echo get_post_type() .'-'. get_the_ID(); ?> loop-item" itemscope itemprop="blogPost" itemtype="http://schema.org/BlogPosting">

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

	<footer>

		<ul class="entry-meta">

			<?php // Meta: Date ?>
			<li>
				<time itemprop="datePublished" datetime="<?php echo esc_attr( get_the_time( 'D, d M Y H:i:s' ) ); ?>">
					<?php the_time( 'F j, Y' );?>
				</time>
			</li>

			<?php // Meta: Author ?>
			<li itemprop="author" itemscope itemtype="http://schema.org/Person">
				by
				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" itemprop="url" rel="author">
					<span itemprop="name"><?php the_author(); ?></span>
				</a>
			</li>

		</ul><!-- .entry-meta -->

	</footer>

</article><!-- .loop-item -->