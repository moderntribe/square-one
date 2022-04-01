<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Content_Columns\Content_Columns_Model;

/**
 * @var array $args ACF block data..
 */
$model = tribe_project()->container()->make( Content_Columns_Model::class, $args );

get_template_part(
	'components/blocks/content_columns/content_columns',
	'',
	$model->get_data()
);
