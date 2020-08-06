<?php
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$controller = \Tribe\Project\Templates\Components\blocks\hero\Controller::factory( $args );
?>
<section <?php echo $controller->classes(); ?> <?php echo $controller->attrs(); ?>>
    <div <?php echo $controller->container_classes(); ?>>

		<?php if ( ! empty( $controller->media ) ) { ?>
            <div <?php $controller->media_classes(); ?> >
				<?php get_template_part( 'components/image/image', null, $controller->media ); ?>
            </div>
		<?php } ?>

		<?php if ( $controller->content ) { ?>
            <div <?php echo $controller->content_classes(); ?>>
				<?php var_dump( $controller->content ); //TODO: Content component needs complete ?>
            </div>
		<?php } ?>
    </div>
</section>
