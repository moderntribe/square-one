<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	
	<label for="s"><?php esc_html_e( 'Search', 'tribe' ); ?></label>
	<input type="text" name="s" />
	<input type="submit" name="submit" value="<?php esc_attr_e( 'Search', 'tribe' ); ?>" />

</form>
