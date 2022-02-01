<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Tabs\Tabs_Model;

/**
 * @var array $args ACF block data.
 */
$model = new Tabs_Model( $args['block'] );
get_template_part( 'components/blocks/tabs/tabs', null, $model->get_data() );
