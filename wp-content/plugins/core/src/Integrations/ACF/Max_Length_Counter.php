<?php declare(strict_types=1);

namespace Tribe\Project\Integrations\ACF;

class Max_Length_Counter {

	public const MAX_LENGTH_COUNTER_WRAPPER_CLASS = 'tribe-counter-wrapper';
	public const MAX_LENGTH_COUNTER_DIV_CLASS     = 'tribe-counter';

	/**
	 * Adds a div below any field that includes the self::MAX_LENGTH_COUNTER_WRAPPER_CLASS
	 * in the field[wrapper][class] and has a maxlength of more than zero.
	 *
	 * @hook acf/render_field
	 *
	 * @param array $field The field array coming from ACF.
	 */
	public function add_counter_div( array $field ): void {
		if ( ! isset( $field['wrapper'] ) || ! isset( $field['wrapper']['class'] ) ) {
			return;
		}

		if ( ! isset( $field['maxlength'] ) || ! intval( $field['maxlength'] ) > 0 ) {
			return;
		}

		if ( ! str_contains( $field['wrapper']['class'], self::MAX_LENGTH_COUNTER_WRAPPER_CLASS ) ) {
			return;
		}

		echo sprintf(
			'<div class="textright %s">%s / %s</div>',
			esc_attr( self::MAX_LENGTH_COUNTER_DIV_CLASS ),
			esc_html( (string) strlen( $field['value'] ) ),
			esc_html( $field['maxlength'] )
		);
	}

	/**
	 * Outputs the javascript to the footer on admin pages that include ACF files
	 * that increments that counter based on the value of the field.
	 *
	 * @hook acf/input/admin_footer
	 */
	public function add_counter_js(): void {
		?>
		<script type="text/javascript">
			( function () {
				document.addEventListener('click',function(e){
					if ( e.target.matches( '.<?php echo esc_attr( self::MAX_LENGTH_COUNTER_WRAPPER_CLASS ); ?> input[maxlength]' ) || e.target.matches( '.<?php echo esc_attr( self::MAX_LENGTH_COUNTER_WRAPPER_CLASS ); ?> textarea[maxlength]' ) ) {
						e.target.addEventListener( 'keyup', function( e ) {
							var maxLength = e.target.getAttribute( 'maxlength' );
							var counter   = e.target.closest( '.<?php echo esc_attr( self::MAX_LENGTH_COUNTER_WRAPPER_CLASS ); ?>' ).querySelector( '.<?php echo esc_attr( self::MAX_LENGTH_COUNTER_DIV_CLASS ); ?>' );
							if ( counter ) {
								counter.innerHTML = e.target.value.length + ' / ' + maxLength;
							}
						} );
					}
			   } );
			} ) ( );
		</script>
		<?php
	}

}
