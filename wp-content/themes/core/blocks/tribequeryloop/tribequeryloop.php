<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Tribe_Query_Loop\Tribe_Query_Loop_Model;

/**
 * @var array $args ACF block data.
 */
$model = tribe_project()->container()->make( Tribe_Query_Loop_Model::class, $args );

get_template_part(
	'components/blocks/tribe_query_loop/tribe_query_loop',
	'',
	$model->get_data()
);
