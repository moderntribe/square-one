<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Stats\Stats_Model;

/**
 * @var array $args ACF block data..
 */
$model = new Stats_Model( $args['block'] );
get_template_part( 'components/blocks/stats/stats', null, $model->get_data() );
