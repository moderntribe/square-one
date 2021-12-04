<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\section_nav\Section_Nav_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Section_Nav_Controller::factory( $args );

?>
<div <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>

	<?php echo $c->get_mobile_toggle(); ?>

	<div <?php echo $c->get_container_classes(); ?> <?php echo $c->get_container_attrs(); ?>>
		<?php echo $c->get_desktop_label(); ?>
		<?php echo $c->get_nav_menu(); ?>
	</div>

	<template data-template="more">
		<li
			class="section-nav__list-item section-nav__list-item--depth-0 section-nav__list-item--more"
			data-js="c-section-nav__list-item--more"
		>
			<?php echo $c->get_more_toggle(); ?>
			<div <?php echo $c->get_more_classes(); ?> <?php echo $c->get_more_attrs(); ?>>
				<ul class="c-section-nav__list--more" data-js="c-section-nav__list--more"></ul>
			</div>
		</li>
	</template>

</div>
