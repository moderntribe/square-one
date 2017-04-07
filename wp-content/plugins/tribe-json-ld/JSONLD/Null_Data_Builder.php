<?php


namespace JSONLD;


class Null_Data_Builder extends Data_Builder {

	protected function build_data() {
		return [ ]; // no data
	}
}