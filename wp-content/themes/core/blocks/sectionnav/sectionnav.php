<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Section_Nav\Section_Nav_Model;

/**
 * @var array $args ACF block data.
 */
$model = new Section_Nav_Model( $args['block'] );
get_template_part( 'components/blocks/section_nav/section_nav', null, $model->get_data() );
