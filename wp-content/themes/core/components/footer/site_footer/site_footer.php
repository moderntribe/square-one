<?php
declare( strict_types=1 );

$controller = \Tribe\Project\Templates\Components\footer\site_footer\Controller::factory();

?>

<footer class="site-footer">

	<div class="l-container">
		<?php get_template_part( 'components/footer/navigation/navigation' ); ?>

		<?php get_template_part( 'components/follow/follow' ); ?>

		<p>
			<?php echo $controller->copyright(); ?>
			<a href="<?php echo esc_url( $controller->home_url() ); ?>" rel="external">
				<?php echo esc_html( $controller->name() ); ?>
			</a>
		</p>

	</div>

</footer>
