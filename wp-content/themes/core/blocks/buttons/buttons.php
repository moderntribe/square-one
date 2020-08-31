<?php

declare( strict_types=1 );

use Tribe\Project\Blocks\Types\Buttons\Buttons_Model;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new Buttons_Model( $args[ 'block' ] );
get_template_part( 'components/blocks/buttons/buttons', null, $model->get_data() );
