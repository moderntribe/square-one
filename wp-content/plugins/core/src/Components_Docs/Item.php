<?php

namespace Tribe\Project\Components_Docs;

interface Item {
	public function get_slug(): string;

	public function get_label(): string;

	public function get_constants(): array;

	public function get_sales_docs(): string;

	public function get_dev_docs(): string;

	public function get_twig_src(): string;

	public function get_rendered_template( $options = [] ): string;
}