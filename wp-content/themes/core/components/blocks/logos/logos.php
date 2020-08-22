<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\blocks\logos\Logos_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Logos_Block_Controller::factory( $args );

if ( empty( $c->logos ) ) {
	return;
}
?>

<section <?php echo $c->get_classes(); ?><?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<?php get_template_part(
			'components/content_block/content_block',
			null,
			$c->get_header_args()
		); ?>

		<ul <?php echo $c->get_content_classes(); ?>>
			<?php foreach ( $c->get_logos() as $logo ) { ?>
				<li class="b-logo">
					<?php get_template_part(
						'components/image/image',
						null,
						$logo
					); ?>
				</li>
			<?php } ?>
		</ul>

	</div>
</section>
