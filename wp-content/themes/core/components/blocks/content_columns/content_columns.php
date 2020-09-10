<?php
declare( strict_types=1 );


use Tribe\Project\Templates\Components\blocks\content_columns\Content_Columns_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Content_Columns_Block_Controller::factory( $args );
?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<?php if ( ! empty( $c->get_header_content() ) ) { ?>
			<?php get_template_part(
				'components/content_block/content_block',
				null,
				$c->get_header_content()
			); ?>
		<?php } ?>

		<?php foreach ( $c->get_columns() as $column ) {
			get_template_part(
				'components/content_block/content_block',
				null,
				$c->get_column_content_args( $column )
			);
		}
		?>
	</div>
</section>
