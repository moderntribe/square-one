<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Hero\Hero_Model;

/**
 * @var array $args ACF block data..
 */
$model = tribe_project()->container()->make( Hero_Model::class, $args );

get_template_part(
	'components/blocks/hero/hero',
	'',
	$model->get_data()
);
