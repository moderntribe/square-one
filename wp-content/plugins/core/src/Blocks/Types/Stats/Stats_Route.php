<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Stats\Stats_Model;

/**
 * @var array $block ACF block data.
 */
$model = tribe_project()->container()->make( Stats_Model::class, [ 'block' => $block ] );

get_template_part( 'components/blocks/stats/stats', '', $model->get_data() );
