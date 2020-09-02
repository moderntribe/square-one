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
			'title'           => 'Hero (UI/UX)',
			'description'     => 'A hero block.',
			'render_template' => 'template-parts/blocks/testimonial/testimonial.php',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'hero', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Accordion
		acf_register_block_type( [
			'name'            => 'accordion-design',
			'title'           => 'Accordion (UI/UX)',
			'description'     => 'An accordion block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'accordion', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Buttons
		acf_register_block_type( [
			'name'            => 'buttons-design',
			'title'           => 'Buttons (UI/UX)',
			'description'     => 'A buttons block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'button', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Interstitial
		acf_register_block_type( [
			'name'            => 'interstitial-design',
			'title'           => 'Interstitial (UI/UX)',
			'description'     => 'An interstitial block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'interstitial', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Lead Form
		acf_register_block_type( [
			'name'            => 'lead-form-design',
			'title'           => 'Lead Form (UI/UX)',
			'description'     => 'A lead form block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'lead form', 'form', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Links
		acf_register_block_type( [
			'name'            => 'links-design',
			'title'           => 'Links (UI/UX)',
			'description'     => 'A links block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'links', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Logos
		acf_register_block_type( [
			'name'            => 'logos-design',
			'title'           => 'Logos (UI/UX)',
			'description'     => 'A logos block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'logos', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Media + Text
		acf_register_block_type( [
			'name'            => 'media-text-design',
			'title'           => 'Media + Text (UI/UX)',
			'description'     => 'A media and text block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'media text', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Post List
		acf_register_block_type( [
			'name'            => 'post-list-design',
			'title'           => 'Post List (UI/UX)',
			'description'     => 'A post list block showing our post list ACF field.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'post list', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Quote
		acf_register_block_type( [
			'name'            => 'quote-design',
			'title'           => 'Quote (UI/UX)',
			'description'     => 'A quote block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'quote', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Stats
		acf_register_block_type( [
			'name'            => 'stats-design',
			'title'           => 'Stats (UI/UX)',
			'description'     => 'A stats block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'stats', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Tabs
		acf_register_block_type( [
			'name'            => 'tabs-design',
			'title'           => 'Tabs (UI/UX)',
			'description'     => 'A tabs block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'tabs', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// WYSIWYG
		acf_register_block_type( [
			'name'            => 'wysiwyg-design',
			'title'           => 'WYSIWYG (UI/UX)',
			'description'     => 'A WYSIWYG block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'wysiwyg', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Icon Grid
		acf_register_block_type( [
			'name'            => 'icon-grid-design',
			'title'           => 'Icon Grid (UI/UX)',
			'description'     => 'An icon grid block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'icon grid', 'icon', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Card Grid
		acf_register_block_type( [
			'name'            => 'card-grid-design',
			'title'           => 'Content Card Grid (UI/UX)',
			'description'     => 'A content card grid block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'card grid', 'card', 'content', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Content Loop
		acf_register_block_type( [
			'name'            => 'content-loop-design',
			'title'           => 'Content Loop (UI/UX)',
			'description'     => 'A content loop block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'content loop', 'content', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Content Carousel
		acf_register_block_type( [
			'name'            => 'content-carousel-design',
			'title'           => 'Content Carousel (UI/UX)',
			'description'     => 'A content carousel block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'content carousel', 'content', 'carousel', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Gallery Grid
		acf_register_block_type( [
			'name'            => 'gallery-grid-design',
			'title'           => 'Gallery Grid (UI/UX)',
			'description'     => 'A gallery grid block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'gallery grid', 'gallery', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Gallery Slider
		acf_register_block_type( [
			'name'            => 'gallery-slider-design',
			'title'           => 'Gallery Slider (UI/UX)',
			'description'     => 'A gallery slider block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'gallery slider', 'gallery', 'slider', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Events
		acf_register_block_type( [
			'name'            => 'events-design',
			'title'           => 'Events (UI/UX)',
			'description'     => 'An events block.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'events', 'content', 'ui', 'ux' ],
			'mode'            => 'preview',
		] );

		// Mode: Auto
		acf_register_block_type( [
			'name'            => 'mode-auto-design',
			'title'           => 'Mode: Auto (UI/UX)',
			'description'     => 'A block showing ACF "auto" mode.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'mode auto', 'ui', 'ux' ],
			'mode'            => 'auto',
		] );

		// Mode: Edit
		acf_register_block_type( [
			'name'            => 'mode-edit-design',
			'title'           => 'Mode: Edit (UI/UX)',
			'description'     => 'A block showing ACF "edit" mode.',
			'render_template' => '',
			'category'        => 'design-play',
			'icon'            => 'dashicons-carrot',
			'keywords'        => [ 'design', 'mode edit', 'ui', 'ux' ],
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
				'slug'  => 'design-play',
				'title' => 'UI/UX',
				'icon'  => 'dashicons-buddicons-activity',
			],
		]
	);
}
