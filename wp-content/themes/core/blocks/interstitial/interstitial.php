<?php

declare( strict_types=1 );

use Tribe\Project\Blocks\Types\Interstitial\Interstitial_Model;

// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new Interstitial_Model( $args[ 'block' ] );

get_template_part(
	'components/blocks/interstitial/interstitial',
	null,
	$model->get_data()
);
