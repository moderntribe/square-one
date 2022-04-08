<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Tabs\Tabs_Model;

/**
 * @var array $args ACF block data.
 */
$model = tribe_project()->container()->make( Tabs_Model::class, $args );

get_template_part(
	'components/blocks/tabs/tabs',
	'',
	$model->get_data()
);
