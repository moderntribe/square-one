<?php

declare( strict_types=1 );

use Tribe\Project\Blocks\Types\Post_List\Post_List_Model;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new Post_List_Model( $args[ 'block' ] );

get_template_part(
	'components/blocks/post_list/post_list',
	null,
	$model->get_data()
);
