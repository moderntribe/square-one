<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Section_Nav\Section_Nav_Model;

/**
 * @var array $args ACF block data.
 */
$model = tribe_project()->container()->make( Section_Nav_Model::class, $args );

get_template_part(
	'components/blocks/section_nav/section_nav',
	'',
	$model->get_data()
);
