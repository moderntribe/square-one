<?php
declare( strict_types=1 );

$controller = \Tribe\Project\Templates\Components\follow\Controller::factory();

?>
<div class="social-follow">

	<ul class="social-follow__list">
		<?php foreach ( $controller->social_links() as $item ) { ?>
			<li class="social-follow__item social-follow__item--<?php echo esc_attr( $item->key ); ?>">
				<a href="<?php echo esc_url( $item->url ); ?>" class="social-follow__anchor" rel="me noopener"
				   title="<?php echo esc_attr( $item->title ); ?>" target="_blank">
					<i class="icon icon-<?php echo esc_attr( $item->key ); ?>"></i>
					<span class="u-visually-hidden"><?php echo esc_html( $item->title ); ?></span>
				</a>
			</li>
		<?php } ?>
	</ul>

</div>
