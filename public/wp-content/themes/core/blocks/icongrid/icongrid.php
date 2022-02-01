<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Icon_Grid\Icon_Grid_Model;

/**
 * @var array $args ACF block data..
 */
$model = new Icon_Grid_Model( $args['block'] );
get_template_part( 'components/blocks/icon_grid/icon_grid', null, $model->get_data() );
