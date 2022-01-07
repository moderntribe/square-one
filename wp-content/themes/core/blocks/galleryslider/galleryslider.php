<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Gallery_Slider\Gallery_Slider_Model;

/**
 * @var array $args ACF block data..
 */
$model = new Gallery_Slider_Model( $args['block'] );
get_template_part( 'components/blocks/gallery_slider/gallery_slider', null, $model->get_data() );
