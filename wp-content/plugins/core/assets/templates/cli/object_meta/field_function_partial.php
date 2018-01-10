	private function get_field_%1$s() {
		$field = new ACF\Field( self::NAME . '_' . self::%2$s );
		$field->set_attributes(
			%3$s
		);

		return $field;
	}
