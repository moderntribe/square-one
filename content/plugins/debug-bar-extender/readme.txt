=== Debug-Bar-Extender ===
Contributors: tott, automattic
Tags: debug, debug bar, development
Donate link: http://hitchhackerguide.com
Requires at least: 3.1
Tested up to: 3.2.1
Stable tag: trunk

Extends the debug-bar plugin with additional tabs to measure runtimes between checkpoints and lookup variable content.

== Description ==

This plugins adds more features to the debug-bar and is mainly aimed at developers who like to debug their code or want to measure runtimes to find glitches in their code.
It also allows lookup of variables by adding simple code snippets in your source.

Please note that this plugin should be used solely for debugging or on a development environment and is not intended for use in a production site.

== Requirements ==

This plugin requires [the debug-bar plugin >0.5](http://wordpress.org/extend/plugins/debug-bar) and a developers' brain to work correctly.

== Usage ==

= Usage example to debug the loop of a theme =

Add your checkpoints in the index.php or any other template file as shown below.

`
<?php if (have_posts()) : ?>

<?php Debug_Bar_Extender::instance()->checkpoint('loop start'); ?>

<?php while (have_posts()) : the_post(); ?>

<?php Debug_Bar_Extender::instance()->trace_var( $post ); ?>

<?php Debug_Bar_Extender::instance()->checkpoint('loop1'); ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
<?php //before_post(); ?>
<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'kubrick'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h2>
<small><?php the_time(get_option('date_format')) ?> by <?php the_author() ?></small>
<div class="entry">
<?php the_content(__('Read the rest of this entry &raquo;', 'kubrick')); ?>
</div>
<p class="postmetadata"><?php the_tags(__('Tags:', 'kubrick') . ' ', ', ', '<br />'); ?> <?php printf(__('Posted in %s', 'kubrick'), get_the_category_list(', ')); ?> | <?php edit_post_link(__('Edit', 'kubrick'), '', ' | '); ?> <?php comments_popup_link(__('No Comments &#187;', 'kubrick'), __('1 Comment &#187;', 'kubrick'), __('% Comments &#187;', 'kubrick')); ?></p>
<?php after_post(); ?>
</div>

<?php Debug_Bar_Extender::instance()->checkpoint('loop2'); ?>

<?php endwhile; ?>

<?php Debug_Bar_Extender::instance()->checkpoint('loop end'); ?>

<div class="navigation">
<div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries', 'kubrick')) ?></div>
<div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;', 'kubrick')) ?></div>
</div>
<?php else : ?>
`

== Advanced usage == 

Looking at the source of the debug-bar-extender.php you will notice that there are various filters to control the default settings. This is useful when you are working on a multisite install and want to use the debug bar without having to adjust the settings every time. Here's an example of how settings enforced by a php file in mu-plugins/ could look like

`add_filter( 'debugbarextender_default_settings', 'my_debug_bar_extender_settings' );
// enforce debug bar settings
function my_debug_bar_extender_settings( $default_settings ) {
	$default_settings = array(
			'enable_profiler'				=> 1,
			'enable_variable_lookup'		=> 1,
			'track_default_vars'			=> 0,
			'add_default_checkpoints'		=> 1,
			'savequeries_debugmode_enable'	=> 1,
			'use_error_log'					=> 0,
			'allow_url_settings'			=> 1,
			'enable_admin_bar_menu'			=> 1,
	);
	return $default_settings;
}

add_filter( 'debugbarextender_default_checkpoint_actions', 'my_debug_bar_extender_checkpoints' );
// initiate some default checkpoints
function my_debug_bar_extender_checkpoints( $default_checkpoints ) {
	$default_checkpoints = array(
					'muplugins_loaded',
					'wp_head',
					'wp_footer',
					'loop_start',
					'loop_end',
					'shutdown',
	);
	return $default_checkpoints;
}
`

== Wishlist ==

This is a work in progress, so feel free to pass by on [our plugin page](http://hitchhackerguide.com/wordpress-plugins/debug-bar-extender/) and leave us
your suggestions in the comments.

== Screenshots ==

1. Settings screen to enable/disable various features.
2. Profiler output showing the runtimes between default checkpoints.
3. Lookup of various default variables

== ChangeLog ==

= Version 0.5 =

* added filters to control settings `debugbarextender_default_settings`, `debugbarextender_default_checkpoint_actions`
* added wrapper functions `dbgx_checkpoint( $note = '' )` and `dbgx_trace_var( $value, $var_name = false )` for easier access to debugging functions.
* added new setting option to allow control of some settings via $_GET parameters in the urls. Allows dbgx_use_error_log, dbgx_track_default_vars, dbgx_add_default_checkpoints to be added as query strings in order to enable respective features. Combinations are possible - for example: /wp-admin/options-general.php?page=debug-bar-extender&dbgx_use_error_log=1&dbgx_add_default_checkpoints=1&dbgx_track_default_vars=1 
* added new setting option to enable a admin bar menu with shortcuts to this urls

= Version 0.4 =

* Moving style enqueuing to init hook. We don't want to be doing it wrong http://core.trac.wordpress.org/changeset/18556

= Version 0.3 =

* Fixed conflicting enqueueing which should fix issues with Debug Console plugin. Thanks to AaronCampbell and Westi

= Version 0.2 =

* fixed: prevent filters which have same names as actions to fail.

= Version 0.1 =

* Initial version of this plugin.
