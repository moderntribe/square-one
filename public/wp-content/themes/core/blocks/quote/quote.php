<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Quote\Quote_Model;

/**
 * @var array $args ACF block data.
 */
$model = new Quote_Model( $args['block'] );
get_template_part( 'components/blocks/quote/quote', null, $model->get_data() );
