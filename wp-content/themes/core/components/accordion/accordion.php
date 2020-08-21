<?php

use Tribe\Project\Templates\Components\accordion\Accordion_Controller;

// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Accordion_Controller::factory( $args );
?>
<div <?php echo $c->get_container_classes(); ?>
	<?php echo $c->get_container_attrs(); ?>>
	<?php foreach ( $c->get_rows() as $key => $row ) { ?>
	<article <?php echo $c->get_row_classes(); ?>>
		<<?php echo esc_attr( $c->get_row_header_tag() ); ?>>
		<button <?php echo $c->get_row_header_classes(); ?>
			<?php echo $c->get_row_header_attrs( $key ); ?>>
					<span <?php echo $c->get_row_header_container_classes(); ?>>
						<?php echo $row->header_text; ?>
					</span>
		</button>
		</<?php echo esc_attr( $c->get_row_header_tag() ); ?>>
		<div <?php echo $c->get_row_content_classes(); ?>
			<?php echo $c->get_row_content_attrs( $key ); ?>>
			<div <?php echo $c->get_row_content_container_classes(); ?>
				<?php echo $c->get_row_content_container_attrs(); ?>>
				<?php echo wp_kses_post( $row->content ); ?>
			</div>
		</div>
		</article>
	<?php } ?>
</div>
