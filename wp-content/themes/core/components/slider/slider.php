<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\slider\Controller::factory( $args );

if ( empty( $c->slides ) ) {
	return;
}
?>

<div <?php echo $c->classes(); ?>>

	<div
		<?php echo $c->main_classes(); ?>
		<?php echo $c->main_attributes(); ?>
	>
		<div <?php echo $c->wrapper_classes(); ?>>
			<?php foreach ( $c->slides as $slide ) { ?>
				<div <?php echo $c->slide_classes(); ?>>
					<?php echo $slide; ?>
				</div>
			<?php } ?>
		</div>

		<?php if ( $c->show_arrows ) { ?>
			<div class="c-slider__arrows">
				<div class="c-slider__button c-slider__button--prev swiper-button-prev"></div>
				<div class="c-slider__button c-slider__button--next swiper-button-next"></div>
			</div>
		<?php } ?>

		<?php if ( $c->show_pagination ) { ?>
			<div class="c-slider__pagination swiper-pagination" data-js="c-slider-pagination"></div>
		<?php } ?>

	</div>

	<?php if ( $c->show_carousel && ! empty( $c->carousel_slides ) ) { ?>
		<div
			class="c-slider__carousel swiper-container"
			<?php echo $c->carousel_attributes(); ?>
		>
			<div class="swiper-wrapper">
				<?php foreach ( $c->carousel_slides as $index => $slide  ) { ?>
					<button
						class="c-slider__thumbnail swiper-slide"
						data-js="c-slider-thumb-trigger"
						data-index="<?php echo esc_attr( $index ); ?>"
						aria-label="<?php printf( '%s %s', __( 'Slide navigation for image', 'tribe' ), $index + 1 ); ?>"
					>
						<?php echo $slide; ?>
					</button>
				<?php } ?>
			</div>
		</div>
	<?php } ?>

</div>
