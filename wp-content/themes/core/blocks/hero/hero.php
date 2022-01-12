<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Hero\Hero_Model;

/**
 * @var array $args ACF block data..
 */
$model = new Hero_Model( $args['block'] );
get_template_part( 'components/blocks/hero/hero', null, $model->get_data() );
