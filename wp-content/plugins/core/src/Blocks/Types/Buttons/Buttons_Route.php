<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Buttons\Buttons_Model;

/**
 * @var array $block ACF block data.
 */
$model = tribe_project()->container()->make( Buttons_Model::class, [ 'block' => $block ] );

get_template_part( 'components/blocks/buttons/buttons', '', $model->get_data() );
