<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Gallery_Grid\Gallery_Grid_Model;

/**
 * @var array $args ACF block data.
 */
$model = tribe_project()->container()->make( Gallery_Grid_Model::class, $args );

get_template_part(
	'components/blocks/gallery_grid/gallery_grid',
	'',
	$model->get_data()
);
