<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\quote\Quote_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Quote_Controller::factory( $args );

?>

<blockquote <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<?php if ( ! empty( $c->get_quote() ) ) { ?>
		<h2 class="c-quote__text h4">
			<?php echo esc_html( $c->get_quote() ); ?>
		</h2>
	<?php } ?>

	<?php if ( $c->has_citation() ) { ?>
		<cite class="c-quote__cite">
			<?php if ( ! empty( ( $c->get_image_args() ) ) ) {
				get_template_part( 'components/image/image', null, $c->get_image_args() );
			} ?>

			<span class="c-quote__cite-text">
				<?php if ( ! empty( $c->get_cite_name() ) ) { ?>
					<span class="c-quote__cite-name">
						<?php echo esc_html( $c->get_cite_name() ); ?>
					</span>
				<?php } ?>
				<?php if ( ! empty( $c->get_cite_title() ) ) { ?>
					<span class="c-quote__cite-title">
						<?php echo esc_html( $c->get_cite_title() ); ?>
					</span>
				<?php } ?>
			</span>

		</cite>
	<?php } ?>

</blockquote>
