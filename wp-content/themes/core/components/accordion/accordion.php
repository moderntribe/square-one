<?php
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$controller = \Tribe\Project\Templates\Components\accordion\Controller::factory( $args );
?>
<div
	<?php echo $controller->container_classes(); ?>
		role="tablist"
		aria-multiselectable="true"
	<?php echo $controller->container_attrs(); ?>
>
	<?php foreach ( $controller->rows as $key => $row ) { ?>
	<article <?php echo $controller->row_classes(); ?>>
		<<?php echo esc_attr( $controller->row_header_tag ); ?>>
		<button
				aria-controls="<?php echo esc_attr( $row[ 'content_id' ] ); ?>"
				aria-expanded="false"
				aria-selected="false"
			<?php echo $controller->row_header_classes(); ?>
				id="<?php echo esc_attr( $row[ 'header_id' ] ); ?>"
				data-index="<?php echo absint( $key ); ?>"
				role="tab"
		>
				<span
					<?php echo $controller->row_header_container_classes(); ?>
					data-depth="0"
					data-name="<?php echo esc_attr( $controller->row_header_name ); ?>"
					data-index="<?php echo absint( $key ); ?>"
					data-livetext
				>
					<?php echo $row[ 'header_text' ]; ?>
				</span>
		</button>
		</<?php echo esc_attr( $controller->row_header_tag ); ?>>
		<div
				hidden
				aria-hidden="true"
				aria-labelledby="<?php echo esc_attr( $row[ 'header_id' ] ); ?>"
			<?php echo $controller->row_content_classes(); ?>
				id="<?php echo esc_attr( $row[ 'content_id' ] ); ?>"
				role="tabpanel"
		>
			<div
				<?php echo $controller->row_content_container_classes(); ?>
					data-depth="0"
					data-name="<?php echo esc_attr( $controller->row_content_name ); ?>"
					data-index="<?php echo absint( $key ); ?>"
					data-autop="true"
					data-livetext
			>
				<?php echo $row[ 'content' ]; ?>
			</div>
		</div>
		</article>
	<?php } ?>
</div>
