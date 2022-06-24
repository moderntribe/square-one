<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop_Model;

/**
 * @var array $block ACF block data.
 */
$model = tribe_project()->container()->make( Content_Loop_Model::class, [ 'block' => $block ] );

get_template_part( 'components/blocks/content_loop/content_loop', '', $model->get_data() );
