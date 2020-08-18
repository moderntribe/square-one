<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\video\Controller::factory( $args );
?>

<div
	<?php echo $c->classes(); ?>
	<?php echo $c->attributes(); ?>
>
	<a
		href="<?php echo esc_url( $c->video_url ); ?>"
		class="c-video__trigger"
		data-js="c-video-trigger"
		target="_blank"
		rel="noopener"
		aria-label="<?php echo esc_html( __( 'Click to Play Video', 'tribe' ) ); ?>"
	>
		<img
			class="c-video__thumbnail lazyload"
			src="<?php echo esc_url( $c->shim_url ); ?>"
			data-src="<?php echo esc_url( $c->thumbnail_url ); ?>"
			role="presentation"
			alt=""
		/>
		<div class="c-video__trigger-action">
			<span class="c-video__trigger-icon icon-play" aria-hidden="true"></span>
			<span class="c-video__trigger-label">
				<?php echo esc_html( $c->trigger_label ); ?>
			</span>
		</div>
	</a>
</div>
