<?php

/**
 * Nothing should be here. If you are tempted to add functions here, consult with your
 * team to determine how best to include that functionality in the core plugin.
 */

// TODO: Remove after design UI/UX pass on blocks is complete

/*
 * Blocks
 */
add_action( 'acf/init', 'design_acf_init_block_types' );
function design_acf_init_block_types() {

	if ( function_exists( 'acf_register_block_type' ) ) {

		// Hero
		acf_register_block_type( [
			'name'            => 'hero-design',
			'title'           => 'Hero Blank',
			'description'     => 'A hero block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'hero', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Accordion
		acf_register_block_type( [
			'name'            => 'accordion-design',
			'title'           => 'Accordion Blank',
			'description'     => 'An accordion block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'accordion', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Buttons
		acf_register_block_type( [
			'name'            => 'buttons-design',
			'title'           => 'Buttons Blank',
			'description'     => 'A buttons block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'button', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Interstitial
		acf_register_block_type( [
			'name'            => 'interstitial-design',
			'title'           => 'Interstitial Blank',
			'description'     => 'An interstitial block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'interstitial', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Lead Form
		acf_register_block_type( [
			'name'            => 'lead-form-design',
			'title'           => 'Lead Form Blank',
			'description'     => 'A lead form block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'lead form', 'form', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Links
		acf_register_block_type( [
			'name'            => 'links-design',
			'title'           => 'Links Blank',
			'description'     => 'A links block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'links', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Logos
		acf_register_block_type( [
			'name'            => 'logos-design',
			'title'           => 'Logos Blank',
			'description'     => 'A logos block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'logos', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Media + Text
		acf_register_block_type( [
			'name'            => 'media-text-design',
			'title'           => 'Media + Text Blank',
			'description'     => 'A media and text block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'media text', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Post List
		acf_register_block_type( [
			'name'            => 'post-list-design',
			'title'           => 'Post List Blank',
			'description'     => 'A post list block showing our post list ACF field.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'post list', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Quote
		acf_register_block_type( [
			'name'            => 'quote-design',
			'title'           => 'Quote Blank',
			'description'     => 'A quote block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'quote', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Stats
		acf_register_block_type( [
			'name'            => 'stats-design',
			'title'           => 'Stats Blank',
			'description'     => 'A stats block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'stats', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Tabs
		acf_register_block_type( [
			'name'            => 'tabs-design',
			'title'           => 'Tabs Blank',
			'description'     => 'A tabs block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'tabs', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// WYSIWYG
		acf_register_block_type( [
			'name'            => 'wysiwyg-design',
			'title'           => 'WYSIWYG Blank',
			'description'     => 'A WYSIWYG block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'wysiwyg', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Icon Grid
		acf_register_block_type( [
			'name'            => 'icon-grid-design',
			'title'           => 'Icon Grid Blank',
			'description'     => 'An icon grid block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'icon grid', 'icon', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Card Grid
		acf_register_block_type( [
			'name'            => 'card-grid-design',
			'title'           => 'Content Card Grid Blank',
			'description'     => 'A content card grid block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'card grid', 'card', 'content', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Content Loop
		acf_register_block_type( [
			'name'            => 'content-loop-design',
			'title'           => 'Content Loop Blank',
			'description'     => 'A content loop block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'content loop', 'content', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Content Carousel
		acf_register_block_type( [
			'name'            => 'content-carousel-design',
			'title'           => 'Content Carousel Blank',
			'description'     => 'A content carousel block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'content carousel', 'content', 'carousel', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Gallery Grid
		acf_register_block_type( [
			'name'            => 'gallery-grid-design',
			'title'           => 'Gallery Grid Blank',
			'description'     => 'A gallery grid block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'gallery grid', 'gallery', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Gallery Slider
		acf_register_block_type( [
			'name'            => 'gallery-slider-design',
			'title'           => 'Gallery Slider Blank',
			'description'     => 'A gallery slider block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'gallery slider', 'gallery', 'slider', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Events
		acf_register_block_type( [
			'name'            => 'events-design',
			'title'           => 'Events Blank',
			'description'     => 'An events block.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'events', 'content', 'blank canvas' ],
			'mode'            => 'preview',
		] );

		// Mode: Auto
		acf_register_block_type( [
			'name'            => 'mode-auto-design',
			'title'           => 'Mode: Auto Blank',
			'description'     => 'A block showing ACF "auto" mode.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'mode auto', 'blank canvas' ],
			'mode'            => 'auto',
		] );

		// Mode: Edit
		acf_register_block_type( [
			'name'            => 'mode-edit-design',
			'title'           => 'Mode: Edit Blank',
			'description'     => 'A block showing ACF "edit" mode.',
			'render_template' => '',
			'category'        => 'blank-canvas',
			'icon'            => 'buddicons-activity',
			'keywords'        => [ 'mode edit', 'blank canvas' ],
			'mode'            => 'auto',
		] );

	}
}

/*
 * Block Categories
 */
add_filter( 'block_categories', 'design_block_categories', 10, 2 );
function design_block_categories( $categories, $post ) {
	return array_merge(
		$categories,
		[
			[
				'slug'  => 'blank-canvas',
				'title' => 'Blank Canvas',
				'icon'  => 'buddicons-activity',
			],
		]
	);
}
