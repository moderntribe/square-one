	
<?php if ( is_active_sidebar( 'sidebar-main' ) ) { ?>
	
	<section class="sidebar-content" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">
		
		<?php dynamic_sidebar( 'sidebar-main' ); ?>

	</section><!-- complementary -->

<?php } ?>