<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$controller = \Tribe\Project\Templates\Components\content_block\Controller::factory( $args );

// text component still makes sense for less painful ability to
// have fine grain control over text tag & classes (for sink) as
// otherwise the component will have completely fixed markup/styles
// which is appropriate in some cases, otherwise, if we want that now,
// it means you need to spin up specific component args for those areas
// you want control over and that feels less than ideal...
// Same q for quote component too, too much?

// Make sure to set reasonable, most used case defaults

// Is there some amount of fixed stuff we can bake in here with design
// we can simplify in this way below, say tags are fixed, but can tweak classes as an arg
// which does provide simplicity and ability to trust what is here all the time
// and also have more of the markup in the FE template

// Only way we could simplify this and burn in more markup/classes here is if
// design was comfy with global impacting changes when make a request or want a tweak

// question of flexiblity / control vs. simplicity, best balance?

// simplest: only args would be classes, attributes, content
// middle: simplest + allow for ability to customize classes of text
// control: everything is composable / render method with legit defaults

// there is an arg for / question around for these mid level components
// I don't know that we want to introduce the flexibility of tag/markup as it is
// and instead we want to dictate that to simplify it's scope/what it does
// might just mean we bake those in the controller as default for the text component
// and don't allow for them to be customized? Would be a vote for middle option above

// have tag just be a header or div as options?

// chat out with Davee on Monday
?>

<<?php echo $controller->tag; ?>
	<?php echo $controller->wrapper_classes(); ?>
	<?php echo $controller->wrapper_attributes(); ?>
>

	<p class="c-content-block__leadin h6"></p>

	<h2 class="c-content-block__title h1"></h2>

	<div class="c-content-block__content t-sink s-sink"></div>

	<?php echo $controller->render_cta(); ?>

</<?php echo $controller->tag; ?>>
