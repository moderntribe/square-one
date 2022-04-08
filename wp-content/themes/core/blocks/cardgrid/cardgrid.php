<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Card_Grid\Card_Grid_Model;

/**
 * @var array $args ACF block data.
 */
$model = tribe_project()->container()->make( Card_Grid_Model::class, $args );

get_template_part(
	'components/blocks/card_grid/card_grid',
	'',
	$model->get_data()
);
