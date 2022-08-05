<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Patterns;

/**
 * Class Block_Pattern_Base
 *
 * Edits the block categories.
 */
class Loop_Pattern extends Pattern_Base {

	protected string $name = 'tribe/loop_pattern';

	protected function get_args(): array {
		return [
			self::TITLE       => esc_html__( 'Loop Pattern', 'tribe' ),
			self::DESCRIPTION => esc_html__( 'Loop Pattern', 'tribe' ),
			self::CONTENT     => $this->get_content(),
			self::CATEGORIES  => [ Pattern_Category::CUSTOM_PATTERN_CATEGORY_SLUG ],
			self::KEYWORDS    => [ esc_html__( 'loop', 'tribe' ), esc_html__( 'query', 'tribe' ) ],
		];
	}

	private function get_content(): string {
		return '<!-- wp:group {"tagName":"section","className":"c-block b-content-loop l-container"} -->
			<section class="wp-block-group c-block b-content-loop l-container"><!-- wp:columns -->
			<div class="wp-block-columns"><!-- wp:column {"width":"66.66%"} -->
			<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:heading {"level":2,	"placeholder":"Enter header text here"} -->
			<h2></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"placeholder":"Content description goes here."} -->
			<p></p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"bottom","width":"33.33%"} -->
			<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:33.33%"><!-- wp:acf/buttons {"id":"block_62ed5039cabb1","name":"acf/buttons","data":{"buttons_0_cta_link":	{"title":"Select a Link","url":"#hello","target":"_self"},	"_buttons_0_cta_link":"field_buttons_link","buttons_0_cta_add_aria_label":"0",	"_buttons_0_cta_add_aria_label":"field_buttons_add_aria_label","buttons_0_cta":"",	"_buttons_0_cta":"field_buttons_cta","buttons_0_button_style":"cta",	"_buttons_0_button_style":"field_buttons_button_style","buttons":1,	"_buttons":"field_buttons_buttons"},"align":"","mode":"preview"} /--></div>
			<!-- /wp:column --></div>
			<!-- /wp:columns -->

			<!-- wp:acf/tribequeryloop {"id":"block_62ed47d8228fb","name":"acf/tribequeryloop","data":	{"query_group_query_post_types":["post"],	"_query_group_query_post_types":"field_tribequeryloop_query_post_types",	"query_group_query_limit":"3","_query_group_query_limit":"field_tribequeryloop_query_limit",	"query_group_offset":"0","_query_group_offset":"field_tribequeryloop_offset",	"query_group_query_taxonomy_terms":"",	"_query_group_query_taxonomy_terms":"field_tribequeryloop_query_taxonomy_terms","query_group":"",	"_query_group":"field_tribequeryloop_query_group","hide_topic":"0",	"_hide_topic":"field_tribequeryloop_hide_topic","hide_excerpt":"0",	"_hide_excerpt":"field_tribequeryloop_hide_excerpt","layout":"layout_columns",	"_layout":"field_tribequeryloop_layout"},"align":"","mode":"preview"} /--></section>
			<!-- /wp:group -->';
	}

}
