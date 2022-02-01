<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Content_Loop\Content_Loop_Model;

/**
 * @var array $args ACF block data.
 */
$model = new Content_Loop_Model( $args['block'] );
get_template_part( 'components/blocks/content_loop/content_loop', null, $model->get_data() );
