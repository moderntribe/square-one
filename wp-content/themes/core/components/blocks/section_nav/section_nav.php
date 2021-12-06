<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\blocks\section_nav\Section_Nav_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Section_Nav_Block_Controller::factory( $args );

/**
 * This block is nothing more than a data source for the Section Nav component.
 *
 * In order for the sticky-navigation feature to work properly, the section nav component
 * cannot be wrapped in a container element. This is a limitation of the `display: sticky`
 * styling used to create the sticky-on-scroll effect.
 */
get_template_part(
	'components/section_nav/section_nav',
	null,
	$c->get_section_nav_args()
);
