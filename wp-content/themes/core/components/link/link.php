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

	<?php echo $c->get_content(); ?>

</a>

<?php get_template_part(
	'components/text/text',
	null,
	$c->get_link_description_args()
); ?>
