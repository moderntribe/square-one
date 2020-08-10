<?php
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\accordion\Controller::factory( $args );
?>
<div <?php echo $c->container_classes(); ?>
	<?php echo $c->container_attrs(); ?>>
	<?php foreach ( $c->rows as $key => $row ) { ?>
	<article <?php echo $c->row_classes(); ?>>
		<<?php echo esc_attr( $c->row_header_tag ); ?>>
			<button <?php echo $c->row_header_classes(); ?>
				<?php echo $c->row_header_attrs($row); ?>>
					<span <?php echo $c->row_header_container_classes(); ?>>
						<?php echo $row[ 'header_text' ]; ?>
					</span>
			</button>
		</<?php echo esc_attr( $c->row_header_tag ); ?>>
		<div <?php echo $c->row_content_classes(); ?>
			<?php echo $c->row_content_attrs($row); ?>>
			<div <?php echo $c->row_content_container_classes(); ?>
				<?php echo $c->row_content_container_attrs(); ?>>
				<?php echo $row[ 'content' ]; ?>
			</div>
		</div>
	</article>
	<?php } ?>
</div>
