<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Spacer\Spacer_Model;

/**
 * @var array $args ACF block data..
 */
$model = new Spacer_Model( $args['block'] );
get_template_part( 'components/blocks/spacer/spacer', null, $model->get_data() );
