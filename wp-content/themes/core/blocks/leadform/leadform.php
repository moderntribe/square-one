<?php declare(strict_types=1);

use Tribe\Project\Blocks\Types\Lead_Form\Lead_Form_Model;

/**
 * @var array $args ACF block data..
 */
$model = tribe_project()->container()->make( Lead_Form_Model::class, $args );

get_template_part(
	'components/blocks/lead_form/lead_form',
	'',
	$model->get_data()
);
