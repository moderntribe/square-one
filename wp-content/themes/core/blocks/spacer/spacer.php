<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Spacer\Spacer_Model;

/**
 * @var array $args ACF block data.
 */
$model = tribe_project()->container()->make( Spacer_Model::class, $args );

get_template_part(
	'components/blocks/spacer/spacer',
	'',
	$model->get_data()
);
