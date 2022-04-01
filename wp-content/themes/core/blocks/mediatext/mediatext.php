<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Media_Text\Media_Text_Model;

/**
 * @var array $args ACF block data..
 */
$model = tribe_project()->container()->make( Media_Text_Model::class, $args );

get_template_part(
	'components/blocks/media_text/media_text',
	null,
	$model->get_data()
);
