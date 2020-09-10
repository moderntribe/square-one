<?php
declare( strict_types=1 );

use Tribe\Project\Blocks\Types\Content_Columns\Content_Columns_Model;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new Content_Columns_Model( $args[ 'block' ] );
get_template_part( 'components/blocks/content_columns/content_columns', null, $model->get_data() );
