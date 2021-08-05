<?php declare(strict_types=1);

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\footer\single_footer\Single_Footer_Controller::factory( $args );
?>
<div <?php echo $c->get_classes(); ?> <?php echo $c->get_attributes(); ?> >
		<div class="c-tag-meta">
			<?php echo $c->tags_list_component->render(); ?>
			<?php get_template_part( 'components/share/share' ) ?>
		</div>
</div>
