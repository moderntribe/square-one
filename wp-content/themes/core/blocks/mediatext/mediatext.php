<?php

declare( strict_types=1 );

use Tribe\Project\Blocks\Types\Media_Text\Media_Text_Model;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new Media_Text_Model( $args[ 'block' ] );

get_template_part(
	'components/blocks/media_text/media_text',
	null,
	$model->get_data()
);
