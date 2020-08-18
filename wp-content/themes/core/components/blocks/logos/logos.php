<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Tribe\Project\Templates\Components\blocks\logos\Logos_Block_Controller::factory( $args );

if ( empty( $c->logos ) ) {
	return;
}
?>

<section <?php echo $c->classes(); ?> <?php echo $c->attributes(); ?>>
	<div class="l-container b-logos__container">

		<?php echo $c->render_header(); ?>

		<ul class="b-logos__list <?php printf( 'b-logos--count-%d', count( $c->logos ) ); ?>">
			<?php foreach ( $c->logos as $logo ) { ?>
				<li class="b-logo">
					{{ component( 'image/Image.php', logo ) }}
				</li>
			<?php } ?>
		</ul>

	</div>
</section>
