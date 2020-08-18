<?php
declare( strict_types=1 );
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new \Tribe\Project\Blocks\Types\Hero\Hero_Model( $args['block'] );
get_template_part( 'components/blocks/hero/hero', null, $model->get_data() );
