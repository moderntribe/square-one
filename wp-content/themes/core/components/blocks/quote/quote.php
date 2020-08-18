<?php

use Tribe\Project\Templates\Components\blocks\quote\Quote_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Quote_Block_Controller::factory( $args ); ?>

<section <?php echo $c->classes(); ?> <?php echo $c->attrs(); ?>>
	<div <?php echo $c->container_classes(); ?>>

		<?php if ( $c->media ) { ?>
			<div <?php echo $c->media_classes(); ?>>
				<?php get_template_part(
					'components/image/image',
					null,
					$c->get_media_args()
				); ?>
			</div>
		<?php } ?>

		<div <?php echo $c->content_classes(); ?>>
			<?php get_template_part(
				'components/quote/quote',
				null,
				$c->get_quote_args()
			); ?>
		</div>

	</div>
</section>
