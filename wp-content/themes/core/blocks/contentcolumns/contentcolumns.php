<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Content_Columns\Content_Columns_Model;

/**
 * @var array $args ACF block data..
 */
$model = new Content_Columns_Model( $args['block'] );
get_template_part( 'components/blocks/content_columns/content_columns', null, $model->get_data() );
