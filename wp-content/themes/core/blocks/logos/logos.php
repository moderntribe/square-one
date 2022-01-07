<?php declare(strict_types=1);

use \Tribe\Project\Blocks\Types\Logos\Logos_Model;

/**
 * @var array $args Arguments passed to the module
 */
$model = new Logos_Model( $args['block'] );

get_template_part( 'components/blocks/logos/logos', null, $model->get_data() );
