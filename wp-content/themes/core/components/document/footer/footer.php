<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\document\footer\Footer_Controller;

$c = Footer_Controller::factory();
?>

		<?php get_template_part( 'components/footer/site_footer/site_footer' ); ?>

	</div><!-- .l-wrapper -->

	<?php do_action( 'wp_footer' ); ?>

</body>
</html>
