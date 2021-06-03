<?php declare(strict_types=1);

/**
 * @var array $args Arguments passed to the template
 */

// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
use Tribe\Project\Blocks\Types\Spacer\Spacer_Model;

$model = new Spacer_Model( $args['block'] );
get_template_part( 'components/blocks/spacer/spacer', null, $model->get_data() );
