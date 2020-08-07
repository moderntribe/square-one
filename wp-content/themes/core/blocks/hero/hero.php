<?php
declare( strict_types=1 );

$model = new \Tribe\Project\Blocks\Types\Hero\Model( $args['block'] );// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
get_template_part( 'components/blocks/hero/hero', null, $model->get_data() );
