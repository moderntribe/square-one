<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\blocks\tribe_query_loop\Tribe_Query_Loop_Controller;

/**
 * @var array $args Arguments passed to the template
 */
$c = Tribe_Query_Loop_Controller::factory( $args );
?>
<div
	<?php echo $c->get_classes(); ?>
	<?php echo $c->get_attributes(); ?>
	>
	<div
		<?php echo $c->get_content_classes(); ?>
		>
		<?php foreach ( $c->get_posts_card_args() as $card_args ) { ?>
			<?php get_template_part( 'components/card/card', '', $card_args ); ?>
		<?php } ?>
	</div>
</div>
