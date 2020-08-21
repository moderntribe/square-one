<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\footer\site_footer\Site_Footer_Controller;

$c = Site_Footer_Controller::factory();
?>

<footer class="site-footer">

	<div class="l-container">
		<?php get_template_part( 'components/footer/navigation/navigation' ); ?>

		<?php get_template_part( 'components/follow/follow' ); ?>

		<p>
			<?php echo $c->get_copyright(); ?>
			<a href="<?php echo $c->get_home_url(); ?>" rel="external">
				<?php echo $c->get_site_name(); ?>
			</a>
		</p>

	</div>

</footer>
