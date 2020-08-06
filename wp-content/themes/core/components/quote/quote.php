<?php
declare( strict_types=1 );

$controller = \Tribe\Project\Templates\Components\quote\Controller::factory();

?>

<blockquote <?php $controller->stringify( $controller->classes ) ?> <?php $controller->stringify( $controller->attrs ) ?>>
	{{ component( 'text/Text.php', quote ) }}
	<?php if ($controller->cite ): ?>
		<cite <?php $controller->stringify( $controller->cite_classes ) ?> <?php $controller->stringify( $controller->cite_attrs ) ?>>
			<?php if( $controller->cite['image'] ): ?>
				{{ component( 'image/Image.php', cite.image ) }}
			<?php endif ?>
			<span class="c-quote__cite-text">
				{% if cite.name %}
					{{ component( 'text/Text.php', cite.name ) }}
				{% endif %}
				{% if cite.title %}
					{{ component( 'text/Text.php', cite.title ) }}
				{% endif %}
			</span>
		</cite>
	<? endif ?>
</blockquote>
