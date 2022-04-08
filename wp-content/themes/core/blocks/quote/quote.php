<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Quote\Quote_Model;

/**
 * @var array $args ACF block data.
 */
$model = tribe_project()->container()->make( Quote_Model::class, $args );

get_template_part(
	'components/blocks/quote/quote',
	'',
	$model->get_data()
);
