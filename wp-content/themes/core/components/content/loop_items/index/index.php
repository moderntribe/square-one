<?php
declare( strict_types=1 );
$controller = \Tribe\Project\Templates\Components\content\loop_items\index\Controller::factory();
?>

<article class="item-loop item-loop--<?php echo esc_attr( get_post_type() ) ?>">

	<header class="item-loop__header">

		<h3 class="item-loop__title">
			<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
				<?php echo get_the_title(); ?>
			</a>
		</h3>

	</header>

	<?php // TODO: featured image ?>

	<?php the_excerpt(); ?>

	<footer class="item-loop__footer">

		<ul class="item-loop__meta">

			<li class="item-loop__meta-date">
				<time datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>">
					<?php echo esc_html( get_the_time( 'F j, Y' ) ); ?>
				</time>
			</li>

			<li class="item-loop__meta-author">
				<?php
				printf(
					esc_html_x( 'by %s', 'link to the author archive', 'tribe' ),
					sprintf(
						'<a href="%s" rel="author">%s</a>',
						esc_url( $controller->author_url() ),
						esc_html( $controller->author_name() )
					)
				);
				?>
			</li>

		</ul>

	</footer>

</article>
