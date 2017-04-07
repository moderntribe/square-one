<footer class="site-footer">

	<div class="l-wrapper">

		<?php // Menu: Footer
		get_template_part( 'content/navigation/footer' ); ?>

		<?php // Components: Social Follow
		get_template_part( 'components/social/follow' ); ?>

		<p>
			<?php printf( '%s &copy %s', __( 'Copyright', 'tribe' ), date( 'Y' ) ); ?>
			<a href="<?php echo esc_url( home_url() ); ?>" rel="external">
				<?php bloginfo( 'name' ); ?>
			</a>
		</p>

	</div>

</footer>
