<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\comments;

trait Comment_Edit_Link {
	private function build_edit_link( $text ): string {
		ob_start();
		edit_comment_link( $text, '<p class="comment__action-edit">', '</p>' );

		return ob_get_clean();
	}
}
