<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Lead_Form\Lead_Form_Model;

/**
 * @var array $args ACF block data..
 */
$model = new Lead_Form_Model( $args['block'] );

get_template_part(
	'components/blocks/lead_form/lead_form',
	null,
	$model->get_data()
);
