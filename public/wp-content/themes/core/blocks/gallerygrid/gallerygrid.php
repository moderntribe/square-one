<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Gallery_Grid\Gallery_Grid_Model;

/**
 * @var array $args ACF block data.
 */
$model = new Gallery_Grid_Model( $args['block'] );
get_template_part( 'components/blocks/gallery_grid/gallery_grid', null, $model->get_data() );
