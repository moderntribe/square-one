<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Icon_Grid\Icon_Grid_Model;

/**
 * @var array $block ACF block data.
 */
$model = tribe_project()->container()->make( Icon_Grid_Model::class, [ 'block' => $block ] );

get_template_part(
	'components/blocks/icon_grid/icon_grid',
	'',
	$model->get_data()
);
