<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\site_footer\Site_Footer_Controller;

/**
 * @var array $args Arguments passed to the template
 */
$c = Site_Footer_Controller::factory( $args );
?>

<footer class="c-site-footer t-theme--light">

	<div class="l-container">

		<?php get_template_part( 'components/container/container', '', $c->get_logo_args() ); ?>

		<div class="c-site-footer__wrap">

			<div class="c-site-footer__content">
				<?php get_template_part( 'components/container/container', '', $c->get_description_args() ); ?>
			</div>

			<div class="c-site-footer__nav">
				<?php get_template_part( 'components/navigation/navigation', '', $c->get_footer_nav_args() ); ?>
			</div>

		</div>

		<div class="c-site-footer__legal">
			<?php get_template_part( 'components/navigation/navigation', '', $c->get_legal_nav_args() ); ?>
			<?php get_template_part( 'components/container/container', '', $c->get_copyright_args() ); ?>
		</div>

	</div>

</footer>
