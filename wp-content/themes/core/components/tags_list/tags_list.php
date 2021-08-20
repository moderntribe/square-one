<?php declare(strict_types=1);

/**
 * @var array $args Arguments passed to the template
 */

// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
use Tribe\Project\Templates\Components\link\Link_Controller;

$c = \Tribe\Project\Templates\Components\tags_list\Tags_List_Controller::factory( $args );

if ( empty( $c->get_tags() ) ) {
	return;
}
?>
<div class="tags <?php echo $c->get_classes(); ?> <?php echo $c->get_attributes(); ?>>
	<?php
	foreach ($c->get_tags() as $tag_name => $tag_link) {
		get_template_part('components/link/link', null, [
			Link_Controller::CONTENT => $tag_name,
			Link_Controller::URL     => $tag_link,
			Link_Controller::CLASSES => ['a-tag-link','a-tag-link--secondary','c-tags-list__list_item'],
		]);
	}
	?>
</div>
