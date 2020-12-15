<?php
declare( strict_types=1 );
/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new \Tribe\Project\Blocks\Types\Hero\Hero_Model( $args[ 'block' ] );
get_template_part( 'components/blocks/hero/hero', null, $model->get_data() );
