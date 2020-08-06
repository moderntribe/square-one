<?php
$model      = new \Tribe\Project\Blocks\Types\Hero\Model( $block );
$controller = \Tribe\Project\Templates\Components\blocks\hero\Controller::factory( $model->get_data() );
?>
<section <?php echo $controller->classes(); ?> <?php echo $controller->attrs(); ?>>
    <div <?php echo $controller->container_classes(); ?>>

		<?php if ( ! empty( $controller->media ) ) { ?>
            <div <?php $controller->media_classes(); ?> >
				<?php get_template_part( 'components/image/image.php', null, $controller->media ); ?>
            </div>
		<?php } ?>

		<?php if ( $controller->content ) { ?>
            <div <?php echo $controller->content_classes(); ?>>
				<?php var_dump( $controller->content );?>
            </div>
		<?php } ?>
    </div>
</section>
