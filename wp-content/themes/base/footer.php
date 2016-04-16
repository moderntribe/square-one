
		<?php // Content: Footer
		get_template_part( 'content/footer/default' ); ?>

	</div><!-- #site-wrap -->

	<?php wp_footer(); ?>

	<?php // Schema: WebSite
	the_website_schema_as_json_ld(); ?>

	<?php // Schema: WebPage
	the_webpage_schema_as_json_ld(); ?>

	<?php // Schema: Organization
	the_organization_schema_as_json_ld(); ?>

</body>
</html>