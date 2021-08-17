<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\link\Link_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Link_Controller::factory( $args );
?>
<?php get_template_part(
	'components/text/text',
	null,
	$c->get_link_header_args()
); ?>

<a <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>

	<?php if ( $c->icon_has_classes() && $c->is_icon_before() ) : ?>
			<i  <?php echo $c->get_icon_classes(); ?> aria-hidden="true"></i>
	<?php endif; ?>

	<?php echo $c->get_content(); ?>

	<?php if ( $c->icon_has_classes() && ! $c->is_icon_before() ) : ?>
			<i  <?php echo $c->get_icon_classes(); ?> aria-hidden="true"></i>
	<?php endif; ?>
</a>

<?php get_template_part(
	'components/text/text',
	null,
	$c->get_link_description_args()
); ?>
