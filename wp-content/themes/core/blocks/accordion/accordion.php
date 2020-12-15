<?php
declare( strict_types=1 );

use Tribe\Project\Blocks\Types\Accordion\Accordion_Model;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new Accordion_Model( $args[ 'block' ] );
get_template_part(
	'components/blocks/accordion/accordion',
	null,
	$model->get_data()
);
