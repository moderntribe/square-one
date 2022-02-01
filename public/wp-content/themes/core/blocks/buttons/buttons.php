<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Buttons\Buttons_Model;

/**
 * @var array $args ACF block data.
 */
$model = new Buttons_Model( $args['block'] );
get_template_part( 'components/blocks/buttons/buttons', null, $model->get_data() );
