<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Interstitial\Interstitial_Model;

/**
 * @var array $args ACF block data..
 */
$model = tribe_project()->container()->make( Interstitial_Model::class, $args );

get_template_part(
	'components/blocks/interstitial/interstitial',
	'',
	$model->get_data()
);
