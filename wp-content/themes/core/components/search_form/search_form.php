<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\search_form\Search_Form_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Search_Form_Controller::factory( $args );
?>

<form
	<?php echo $c->get_classes(); ?>
	role="search"
	method="get"
	action="<?php echo $c->get_action(); ?>"
>

	<label class="c-search__label" for="<?php echo $c->get_form_id(); ?>">
		<?php echo $c->get_label(); ?>
	</label>

	<input
		class="c-search__input"
		type="text"
		id="<?php echo $c->get_form_id(); ?>"
		name="s"
		placeholder="<?php echo $c->get_placeholder(); ?>"
	/>

	<?php get_template_part(
		'components/button/button',
		null,
		$c->get_button_args()
	); ?>

</form>
