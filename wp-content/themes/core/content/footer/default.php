<footer class="site-footer">

	<div class="content-wrap">

		<?php // Menu: Footer
		get_template_part( 'content/navigation/footer' ); ?>

		<?php // Components: Social Follow
		get_template_part( 'components/social/follow' ); ?>

		<h6>
			Copyright &copy; <?php echo date( 'Y' ); ?> 
			<a href="<?php echo esc_url( home_url() ); ?>" rel="external">
				<?php bloginfo( 'name' ); ?>
			</a>
		</h6>

	</div>

</footer>
