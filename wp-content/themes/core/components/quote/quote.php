<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$controller = \Tribe\Project\Templates\Components\quote\Controller::factory( $args );
?>

<blockquote <?php echo $controller->render_classes( $controller->classes ) ?> <?php echo $controller->render_attrs( $controller->attrs ) ?>>

	<?php if ( !empty($controller->text_text) ) : ?>
		<<?php echo $controller->render_text_tag_and_attrs() ?>>
			<?php echo $controller->text_text ?>
		</<?php echo esc_attr( $controller->text_tag ) ?>>
	<?php endif; ?>

	<?php if ( !empty( $controller->cite_name) ) : ?>
		<cite <?php echo $controller->render_classes( $controller->cite_classes ) ?> <?php echo $controller->render_attrs( $controller->cite_attrs ) ?>>

			<?php if ( !empty( $controller->cite_imag ) ) : ?>
				<?php
					$controller->render_cite_image();
				?>
			<?php endif ?>

			<span class="c-quote__cite-text">
				<?php if ( !empty( $controller->cite_name) ) : ?>
					<span>
						<?php echo $controller->cite_name ?>
					</span>
				<?php endif; ?>
				<?php if ( !empty( $controller->cite_title) ) : ?>
					<span>
						<?php echo $controller->cite_title ?>
					</span>
				<?php endif; ?>
			</span>

		</cite>
	<?php endif ?>
</blockquote>
