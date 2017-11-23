<?php


namespace Tribe\Project\Permissions\Roles;


class Null_Role implements Section_Role_Interface {
	const NAME = 'not_a_role';

	public function get_name() {
		return '';
	}

	public function get_label() {
		return '';
	}

	public function general_capabilities(): array {
		return [];
	}

	public function post_capabilities( $post_id ): array {
		return [];
	}

	public function can_edit_section(): bool {
		return false;
	}


}