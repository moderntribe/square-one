<?php declare(strict_types=1);

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$model = new \Tribe\Project\Blocks\Types\Gallery_Slider\Gallery_Slider_Model( $args['block'] );
get_template_part( 'components/blocks/gallery_slider/gallery_slider', null, $model->get_data() );
