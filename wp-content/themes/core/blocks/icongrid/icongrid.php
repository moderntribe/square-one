<?php declare(strict_types=1);

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new \Tribe\Project\Blocks\Types\Icon_Grid\Icon_Grid_Model( $args['block'] );
get_template_part( 'components/blocks/icon_grid/icon_grid', null, $model->get_data() );
