<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new \Tribe\Project\Blocks\Types\Gallery_Grid\Gallery_Grid_Model( $args['block'] );
get_template_part( 'components/blocks/gallery_grid/gallery_grid', null, $model->get_data() );
