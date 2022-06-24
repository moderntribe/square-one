<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Tabs\Tabs_Model;

/**
 * @var array $block ACF block data.
 */
$model = tribe_project()->container()->make( Tabs_Model::class, [ 'block' => $block ] );

get_template_part( 'components/blocks/tabs/tabs', '', $model->get_data() );
