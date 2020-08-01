<?php

$controller = \Tribe\Project\Templates\Components\content\loop_item_search\Controller::factory();

?>
<article class="item-loop item-loop--search item-loop--<?php echo esc_attr( get_post_type() ); ?>">

	<header class="item-loop__header">

		<h3 class="item-loop__title">
			<a href="<?php the_permalink(); ?>>" rel="bookmark">
				<?php echo esc_html( get_the_title() ); ?>
			</a>
		</h3>

	</header>

	<?php the_post_thumbnail(); // TODO: Do we need to replace this with our image handling? ?>

	<?php the_excerpt(); ?>

</article>
