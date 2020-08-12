<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\share\Controller::factory( $args );
?>
<aside class="social-share">

	<h6 class="social-share__title"><?php esc_html_e( 'Share This', 'tribe' );?></h6>

	<ul class="social-share-networks" data-js="social-share-networks">
		<?php foreach ( $c->get_links() as $link ) { ?>
			<li class="social-share-networks__item">
				<?php get_template_part( 'component/link/link', null, $link ); ?>
			</li>
		<?php } ?>
	</ul>
</aside>
