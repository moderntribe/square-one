<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\video\Video_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Video_Controller::factory( $args );
?>

<div
	<?php echo $c->get_classes(); ?>
	<?php echo $c->get_attrs(); ?>
>
	<a
		href="<?php echo $c->get_video_url(); ?>"
		class="c-video__trigger"
		data-js="c-video-trigger"
		target="_blank"
		rel="noopener"
		aria-label="<?php echo esc_html( __( 'Click to Play Video', 'tribe' ) ); ?>"
	>
		<img
			class="c-video__thumbnail lazyload"
			src="<?php echo $c->get_image_shim_url(); ?>"
			data-src="<?php echo $c->get_image_url(); ?>"
			role="presentation"
			alt=""
		/>
		<div class="c-video__trigger-action">
			<span class="c-video__trigger-icon icon-play" aria-hidden="true"></span>
			<span class="c-video__trigger-label">
				<?php echo $c->get_trigger_label(); ?>
			</span>
		</div>
	</a>
</div>
