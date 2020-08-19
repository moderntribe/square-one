<?php
declare( strict_types=1 );

$model = new \Tribe\Project\Blocks\Types\Stats\Stats_Model( $args['block'] );// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
get_template_part( 'components/blocks/stats/stats', null, $model->get_data() );
