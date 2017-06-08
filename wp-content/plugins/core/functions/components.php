<?php

function load_component( string $component ) {
	/**
	 * @var $template \Tribe\Project\Twig\Twig_Template.
	 */
	$filename  = sprintf( 'components/%s.twig', $component );
	$basename  = basename( $component );
	$classname = ucwords( str_replace( '-', ' ', $basename ), '_' );

	$parent_class_name = 'Tribe\\Project\\Templates\\Component';
	$child_class_name  = 'Tribe\\Project\\Templates\\Components\\' . $classname;

	if ( ! class_exists( $child_class_name ) ) {
		$template = new $parent_class_name( $filename );
		echo $template->render();

		return;
	}

	$template = new $child_class_name( $filename );
	echo $template->render();
}