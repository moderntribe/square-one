<?php

namespace AttachmentHelper;

class Field {
	private $args = array();

	private $label = 'thumbnail image';
	private $value = NULL;
	private $size = 'thumbnail';
	private $name = 'attachment-helper';
	private $type = 'image';
	private $id = NULL;
	private $settings = NULL;

	public function __construct( $args = array() ) {
		$defaults = array(
			'label' => __('thumbnail image', 'attachment-helper'),
			'value' => $this->value,
			'size'  => $this->size,
			'name'  => $this->name,
			'type'  => $this->type,
			'id' => $this->id,
			'settings' => $this->settings,
		);
		$this->args = wp_parse_args($args, $defaults);
		foreach ( array_keys($defaults) as $key ) {
			$this->{$key} = $this->args[$key];
		}
		if ( empty($this->id) ) {
			$this->id = $this->args['id'] = esc_attr(preg_replace('/[^\w]/', '_', $this->name));
		}
		if ( empty($this->settings) ) {
			$this->settings = $this->args['settings'] = $this->id;
		}
	}

	public function render() {
		UI::instance()->enqueue_scripts( $this->args );
		?>
		
		<div style="margin-top: 10px;" class="uploadContainer attachment-helper-uploader" data-settings="<?php echo $this->settings; ?>" data-size="<?php echo $this->size; ?>" data-type="<?php echo $this->type; ?>">

			<!-- Current image -->
			<div class="<?php echo !empty( $this->value ) ? 'open' : 'closed'; ?> current-uploaded-image">
				<img class="attachment-<?php echo $this->size; ?>" />
				<div class="wp-caption"></div>
				<p class="remove-button-container">
					<a class="button-secondary remove-image" href="#">
						<?php printf(__('Remove %s', 'attachment-helper'), $this->label ); ?>
					</a>
				</p>
			</div>

			<!-- Uploader section -->
			<div class="uploaderSection">
				<div class="loading">
					<img src="<?php echo admin_url('images/wpspin_light.gif'); ?>" alt="<?php _e( 'Loading', 'attachment-helper' ); ?>" />
				</div>
				<div class="hide-if-no-js plupload-upload-ui">
					<div class="drag-drop-area">
						<div class="drag-drop-inside">
							<p class="drag-drop-info"><?php _e('Drop files here'); ?></p>
							<p><?php _e('or', 'attachment-helper'); ?></p>
							<p class="drag-drop-buttons">
								<a href="#" class="button attachment_helper_library_button" title="Add Media" data-size="<?php echo $this->size; ?>" data-type="<?php echo $this->type; ?>" >
									<span class="wp-media-buttons-icon"></span><?php _e( 'Select Files', 'attachment-helper' ); ?>
								</a>
							</p>
							<p class="drag-drop-buttons" style="display:none"><input type="button" value="<?php esc_attr_e('Select Files'); ?>" class="plupload-browse-button button" /></p>
						</div>
					</div>
				</div>
			</div>

			<input type="hidden" name="<?php echo $this->name; ?>" value="<?php echo $this->value; ?>" class="attachment_helper_value" />
		</div>
		<?php

		
	}
}
 