	private function get_field_%1$s() {
		$field = new ACF\Field( self::NAME . '_' . self::%2$s );
		$field->set_attributes( [
			'label' => __( '%3$s', 'tribe' ),
			'name'  => self::%2$s,
			'type'  => '%4$s',
		] );

		return $field;
	}
