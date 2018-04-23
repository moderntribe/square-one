	private function get_field_%1$s() {
		$repeater = new ACF\Repeater( self::NAME . '_' . self::%2$s );
		$repeater->set_attributes(
			%3$s
		);

		return $repeater;
	}

