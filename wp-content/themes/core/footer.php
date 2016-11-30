
		<?php // Content: Footer
		get_template_part( 'content/footer/default' ); ?>

	</div><!-- .l-site-wrapper -->

    <?php
    use \Tribe\Project\Theme\Nav\Menu;
    use \Tribe\Libs\Nav\JSON_Nav_Walker;
    ?>
    <script id="nav-primary-data" type="application/json">
        <?php
            Menu::menu( array(
                'echo'           => true,
                'depth'          => 3,
                'walker'         => new JSON_Nav_Walker(),
                'theme_location' => 'nav-primary',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'fallback_cb'    => false,
            ) );
        ?>
    </script>

	<?php wp_footer(); ?>

</body>
</html>
