<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\share\Share_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Share_Controller::factory( $args );
?>
<div class="social-share">

	<p class="social-share__title"><?php esc_html_e( 'Share This', 'tribe' );?></p>

	<ul class="social-share-networks" data-js="social-share-networks">
		<?php foreach ( $c->get_links() as $link ) { ?>
			<li class="social-share-networks__item">
				<?php get_template_part( 'components/link/link', null, $link ); ?>
			</li>
		<?php } ?>
	</ul>
</div>
