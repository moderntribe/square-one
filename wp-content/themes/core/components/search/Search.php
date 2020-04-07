<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Search
 *
 * @property string[] $form_classes
 * @property string[] $form_attrs
 * @property string[] $label_classes
 * @property string[] $label_attrs
 * @property string   $label_text
 * @property string[] $input_classes
 * @property string[] $input_attrs
 * @property string   $submit_button
 */
class Search extends Context {
	public const FORM_CLASSES  = 'form_classes';
	public const FORM_ATTRS    = 'form_attrs';
	public const LABEL_CLASSES = 'label_classes';
	public const LABEL_ATTRS   = 'label_attrs';
	public const LABEL_TEXT    = 'label_text';
	public const INPUT_CLASSES = 'input_classes';
	public const INPUT_ATTRS   = 'input_attrs';
	public const SUBMIT_BUTTON = 'submit_button';

	protected $path = __DIR__ . '/search.twig';

	protected $properties = [
		self::FORM_CLASSES  => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
		self::FORM_ATTRS    => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::LABEL_CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
		self::LABEL_ATTRS   => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::LABEL_TEXT    => [
			self::DEFAULT => '',
		],
		self::INPUT_CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
		self::INPUT_ATTRS   => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::SUBMIT_BUTTON => [
			self::DEFAULT => '',
		],
	];
}
