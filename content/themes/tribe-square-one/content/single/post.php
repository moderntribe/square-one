<article class="<?php echo get_post_type() .'-'. get_the_ID(); ?>" itemscope itemtype="http://schema.org/BlogPosting">

	<header>
		
		<?php // Title ?>
		<h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>

	</header>

	<?php // Featured Image
	if ( has_post_thumbnail() ) { ?>
		<figure class="entry-featured-img">
			<?php
				$attr = array(
					'class'	   => '',
					'itemprop' => 'thumbnailUrl'
				);
				echo get_the_post_thumbnail( get_the_ID(), 'full', $attr );
			?>
    	</figure><!-- .entry-featured-img -->
	<?php } ?>
	
	<?php // Content ?>		
	<div itemprop="text">	
		<?php the_content(); ?>
	</div>
	
	<footer>

		<ul class="entry-meta">

			<?php // Meta: Date ?>
			<li>
				<time itemprop="datePublished" datetime="<?php echo esc_attr( get_the_time( 'D, d M Y H:i:s' ) ); ?>">
					<?php the_date();?>
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

</article><!-- post -->