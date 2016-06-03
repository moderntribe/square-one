<aside class="social-share">

	<h6>Share This</h6>

	<?php
	$social = new Social();
	$social_links = $social->the_social_share_links( array( 'facebook', 'twitter', 'google', 'linkedin', 'email' ), false );
	if ( ! empty( $social_links ) ) {
		echo $social_links;
	} ?>

</aside>
