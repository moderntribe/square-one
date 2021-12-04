<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new \Tribe\Project\Blocks\Types\Section_Nav\Section_Nav_Model( $args['block'] );
get_template_part( 'components/blocks/section_nav/section_nav', null, $model->get_data() );
