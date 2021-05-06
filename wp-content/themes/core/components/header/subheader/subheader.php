<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\header\subheader\Subheader_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Subheader_Controller::factory( $args );
?>

<header <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>

	<?php if ( ! empty( $c->get_image_args() ) ) { ?>
					
		<?php get_template_part(
			'components/image/image',
			null,
			$c->get_image_args()
		); ?>
		
	<?php } ?>

	<div class="l-container">

		<?php $c->render_breadcrumbs(); ?>	
	
		<?php get_template_part( 'components/text/text', null, $c->get_title_args() ); ?>
	</div>
</header>
