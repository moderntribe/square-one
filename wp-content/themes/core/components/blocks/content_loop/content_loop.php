<?php
declare( strict_types=1 );

use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop;

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

			<!-- Feature Layout -->
			<?php if ( $c->get_layout() === Content_Loop::LAYOUT_FEATURE ) : ?>
				
				<div class="b-content-loop__featured">
				
					<?php foreach ( $c->get_posts_card_args() as $index => $card_args ) { ?>
						<?php get_template_part( 'components/card/card', null, $card_args ); ?>

						<?php if ( $index === 0 ) : ?>
							</div>
							<div class="b-content-loop__secondary">
						<?php endif; ?> 
					<?php } ?>

				</div>

			<?php else : ?>
				<!-- Columns and Row Layout -->
				<?php foreach ( $c->get_posts_card_args() as $card_args ) { ?>
					<?php get_template_part( 'components/card/card', null, $card_args ); ?>
				<?php } ?>

			<?php endif; ?>
	
		</div>

	</div>
</section>
