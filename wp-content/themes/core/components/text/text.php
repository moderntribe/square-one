<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\text\Text_Controller;

/**
 * @var array $args Arguments passed to the template
 */
$c = Text_Controller::factory( $args );

if ( empty( $c->get_content() ) && $c->get_content() !== '0' ) {
	return;
}
?>

<<?php echo $c->get_tag(); ?> <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<?php echo $c->get_content(); ?>
</<?php echo $c->get_tag(); ?>>
