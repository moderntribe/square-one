<?php get_header(); ?>

	<main>

		<?php // Content: Sub-header
		get_template_part( 'content/header/sub' ); ?>

		<div class="l-wrapper">

			<p><?php esc_attr_e( 'Houston, we have a problem...', 'tribe' ); ?></p>

		</div>

	</main>

<?php get_footer(); ?>
