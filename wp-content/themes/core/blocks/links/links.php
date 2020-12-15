<?php

declare( strict_types=1 );

use Tribe\Project\Blocks\Types\Links\Links_Model;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new Links_Model( $args[ 'block' ] );
get_template_part( 'components/blocks/links/links', null, $model->get_data() );
