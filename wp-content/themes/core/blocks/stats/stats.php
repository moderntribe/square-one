<?php
declare( strict_types=1 );

use \Tribe\Project\Blocks\Types\Stats\Stats_Model;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new Stats_Model( $args['block'] );

get_template_part( 'components/blocks/stats/stats', null, $model->get_data() );
