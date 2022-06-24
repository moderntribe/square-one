<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Gallery_Slider\Gallery_Slider_Model;

/**
 * @var array $block ACF block data.
 */
$model = tribe_project()->container()->make( Gallery_Slider_Model::class, [ 'block' => $block ] );

get_template_part( 'components/blocks/gallery_slider/gallery_slider', '', $model->get_data() );
