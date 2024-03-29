<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Links\Links_Model;

/**
 * @var array $args ACF block data.
 */
$model = tribe_project()->container()->make( Links_Model::class, $args );

get_template_part(
	'components/blocks/links/links',
	'',
	$model->get_data()
);
