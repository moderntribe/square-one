<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new \Tribe\Project\Blocks\Types\Content_Loop\Content_Loop_Model( $args['block'] );
get_template_part( 'components/blocks/content_loop/content_loop', null, $model->get_data() );
