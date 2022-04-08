<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Accordion\Accordion_Model;

/**
 * @var array $args ACF block data.
 */
$model = tribe_project()->container()->make( Accordion_Model::class, $args );

get_template_part(
	'components/blocks/accordion/accordion',
	'',
	$model->get_data()
);
