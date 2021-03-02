<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\slider\Slider_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Slider_Controller::factory( $args );

if ( empty( $c->get_slides() ) ) {
	return;
}
?>

<div <?php echo $c->get_classes(); ?>>

	<?php if ( $c->should_show_arrows() ) { ?>
		<div class="c-slider__arrows">
			<div class="c-slider__button c-slider__button--prev swiper-button-prev"></div>
			<div class="c-slider__button c-slider__button--next swiper-button-next"></div>
		</div>
	<?php } ?>

	<div <?php echo $c->get_main_classes(); ?> <?php echo $c->get_main_attrs(); ?>>

		<div <?php echo $c->get_wrapper_classes(); ?>>
			<?php foreach ( $c->get_slides() as $slide ) { ?>
				<div <?php echo $c->get_slide_classes(); ?>>
					<?php echo $slide; ?>
				</div>
			<?php } ?>
		</div>

		<?php if ( $c->should_show_pagination() ) { ?>
			<div class="c-slider__pagination swiper-pagination" data-js="c-slider-pagination"></div>
		<?php } ?>

	</div>

	<?php if ( $c->should_show_carousel() && ! empty( $c->get_carousel_slides() ) ) { ?>
		<div
			class="c-slider__carousel swiper-container"
			<?php echo $c->get_carousel_attrs(); ?>
		>
			<div class="swiper-wrapper">
				<?php foreach ( $c->get_carousel_slides() as $index => $slide  ) { ?>
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
