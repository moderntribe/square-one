<?php
declare( strict_types=1 );

namespace Tribe\Project\Integrations\Gravity_Forms;

use Tribe\Libs\Container\Abstract_Subscriber;

class Gravity_Forms_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_filter( 'gform_confirmation_anchor', '__return_false' );
		add_filter( 'gform_tabindex', '__return_false' );
		add_filter( 'pre_option_rg_gforms_disable_css', '__return_true' );
		add_filter( 'pre_option_rg_gforms_enable_html5', '__return_true' );


		add_filter( 'gform_field_choice_markup_pre_render', function( ... $args ) {
			return $this->container->get( Form_Markup::class )->customize_gf_choice_other( ... $args );
		}, 10, 4 );

		add_filter( 'gform_field_css_class', function( ... $args ) {
			return $this->container->get( Form_Markup::class )->add_gf_select_field_class( ... $args );
		}, 10, 3 );

		add_action( 'gform_enqueue_scripts', function() {
			$this->container->get( Form_Styles::class )->enqueue_gravity_forms_jquery_ui_styles();
		});

		add_filter( 'gform_pre_render', function( $form ) {
			return $this->container->get( Form_Styles::class )->deactivate_gf_animations( $form );
		});
	}
}
