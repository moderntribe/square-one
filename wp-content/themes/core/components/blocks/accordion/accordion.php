<?php
$c = \Tribe\Project\Templates\Components\blocks\accordion\Controller::factory($args);
?>

<section <?php echo $c->classes(); ?><?php echo $c->attrs();?>>
	<div <?php echo $c->container_classes(); ?><?php echo $c->container_attrs();?>>

		<?php echo $c->render_header(); ?>

		<div <?php echo $c->content_classes();?>>
			<?php echo $c->render_content(); ?>
		</div>

	</div>
</section>
