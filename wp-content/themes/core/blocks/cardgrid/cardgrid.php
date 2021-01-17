<?php

declare( strict_types=1 );

use Tribe\Project\Blocks\Types\Card_Grid\Card_Grid_Model;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new Card_Grid_Model( $args[ 'block' ] );

get_template_part(
	'components/blocks/card_grid/card_grid',
	null,
	$model->get_data()
);
