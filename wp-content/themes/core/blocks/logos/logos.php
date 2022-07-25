<?php declare(strict_types=1);

use \Tribe\Project\Blocks\Types\Logos\Logos_Model;

/**
 * @var array $args Arguments passed to the module
 */
$model = tribe_project()->container()->make( Logos_Model::class, $args );

get_template_part(
	'components/blocks/logos/logos',
	'',
	$model->get_data()
);
