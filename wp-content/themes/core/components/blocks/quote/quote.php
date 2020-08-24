<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\blocks\quote\Quote_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Quote_Block_Controller::factory( $args ); ?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<?php if ( $c->has_image() ) { ?>
			<div <?php echo $c->get_media_classes(); ?>>
				<?php get_template_part(
					'components/image/image',
					null,
					$c->get_media_args()
				); ?>
			</div>
		<?php } ?>

		<div <?php echo $c->get_content_classes(); ?>>
			<?php get_template_part(
				'components/quote/quote',
				null,
				$c->get_quote_args()
			); ?>
		</div>

	</div>
</section>
