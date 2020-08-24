<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\content\page\index\Page_Controller;

$c = Page_Controller::factory();
?>

<?php if ( ! empty( $c->get_image_args() ) ) {
	get_template_part(
		'components/image/image',
		null,
		$c->get_image_args()
	);
} ?>

<div class="s-sink t-sink l-sink">
	<?php the_content(); ?>
</div>
