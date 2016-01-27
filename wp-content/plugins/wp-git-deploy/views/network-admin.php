<?php
/**
 * @var $title
 */
?>
<div class="wrap">
	<?php if(!empty($_GET['updated']) && $_GET['updated'] == 'true') { ?>
		<div class="updated fade" id="crosspost-network-settings-updated"><p><strong><?php _e('Settings Saved'); ?></strong></p></div>
	<?php } ?>
		<?php screen_icon('options-general'); ?>
		<h2><?php echo $title; ?></h2>
	<form method="post" action="edit.php?action=<?php echo self::SLUG; ?>">
		<?php do_settings_sections(self::SLUG); ?>
		<p class="submit">
			<?php wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME); ?>
			<input type="submit" name="<?php echo self::NONCE_ACTION; ?>" class="button-primary" value="<?php _e('Save Changes'); ?>" />
		</p>
	</form>
</div>