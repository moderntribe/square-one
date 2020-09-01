<?php

declare( strict_types=1 );

use Tribe\Project\Blocks\Types\Lead_Form\Lead_Form_Model;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new Lead_Form_Model( $args[ 'block' ] );

get_template_part(
	'components/blocks/lead_form/lead_form',
	null,
	$model->get_data()
);
