<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Links\Links_Model;

/**
 * @var array $args ACF block data.
 */
$model = new Links_Model( $args['block'] );
get_template_part( 'components/blocks/links/links', null, $model->get_data() );
