<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\search_form\Search_Form_Controller;

/**
 * @var array $args Arguments passed to the template
 */
$c = Search_Form_Controller::factory( $args );
?>

<form
	<?php echo $c->get_classes(); ?>
	role="search"
	method="get"
	action="<?php echo $c->get_action(); ?>"
	data-js="search-form"
>

	<label class="c-search__label u-visually-hidden" for="<?php echo $c->get_form_id(); ?>">
		<?php echo $c->get_label(); ?>
	</label>

	<input
		class="c-search__input"
		type="text"
		id="<?php echo $c->get_form_id(); ?>"
		name="s"
		placeholder="<?php echo $c->get_placeholder(); ?>"
		data-js="search-form-input"
		value="<?php echo esc_attr( $c->get_search_value() ); ?>"
	/>

	<?php get_template_part(
		'components/button/button',
		null,
		$c->get_submit_button_args()
	); ?>

</form>
