<?php
declare( strict_types=1 );

use \Tribe\Project\Blocks\Types\Logos\Logos_Model;

// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new Logos_Model( $args[ 'block' ] );

get_template_part( 'components/blocks/logos/logos', null, $model->get_data() );
