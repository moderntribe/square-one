<?php
declare( strict_types=1 );

use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop;
use Tribe\Project\Templates\Components\card\Card_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\blocks\content_loop\Content_Loop_Controller::factory( $args );
?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<?php get_template_part(
			'components/content_block/content_block',
			null,
			$c->get_header_args()
		); ?>

		<div <?php echo $c->get_content_classes(); ?>>

			<?php if ( $c->get_layout() === Content_Loop::LAYOUT_FEATURE ) : ?>
				<!-- Feature Layout -->

				<div class="b-content-loop__featured">
					<?php foreach ( $c->get_posts_card_args() as $index => $card_args ) { ?>
						<?php if ( $index === 0 ) : ?>
							<?php get_template_part( 'components/card/card', null, $card_args ); ?>		
						<?php endif; ?>
					<?php } ?>
				</div>

				<div class="b-content-loop__secondary">
					<?php foreach ( $c->get_posts_card_args( Card_Controller::STYLE_INLINE ) as $index => $card_args ) { ?>
						<?php if ( $index !== 0 ) : ?>
							<?php get_template_part( 'components/card/card', null, $card_args ); ?>
						<?php endif; ?>
					<?php } ?>

					<?php if ( ! empty( $c->get_cta() ) ) : ?>
						<?php echo $c->get_cta(); ?>
					<?php endif; ?>
				</div>

			<?php elseif ( $c->get_layout() === Content_Loop::LAYOUT_COLUMNS ) : ?>

				<!-- Columns Layout -->
				<?php foreach ( $c->get_posts_card_args( Card_Controller::STYLE_INLINE ) as $index => $card_args ) { ?>
					<?php get_template_part( 'components/card/card', null, $card_args ); ?>
				<?php } ?>

			<?php else : ?>

				<!-- Row Layout -->
				<?php foreach ( $c->get_posts_card_args() as $card_args ) { ?>
					<?php get_template_part( 'components/card/card', null, $card_args ); ?>
				<?php } ?>

			<?php endif; ?>
	
		</div>

	</div>
</section>
