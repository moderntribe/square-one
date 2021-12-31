<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\footer\navigation\Navigation_Controller;

/**
 * @var array $args Arguments passed to the template
 */
$c = Navigation_Controller::factory( $args );

if ( ! $c->has_menu() ) {
	return;
}
?>

<nav <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>

	<ul <?php echo $c->get_nav_list_classes(); ?>>
		<?php echo $c->get_menu(); ?>
	</ul>

</nav>
