<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\section_nav\Section_Nav_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Section_Nav_Controller::factory( $args );

?>
<div <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>

	<?php echo $c->get_mobile_toggle(); ?>

	<div <?php echo $c->get_container_classes(); ?> <?php echo $c->get_container_attrs(); ?>>

		<?php echo $c->get_nav_menu(); ?>

		<!--<ul>

			<li>
				<span>Menu Item</span>
				<ul>
					<li>Child Menu Item</li>
					<li>Child Menu Item</li>
					<li>Child Menu Item</li>
					<li>Child Menu Item</li>
					<li>Child Menu Item</li>
					<li>Child Menu Item</li>
					<li>Child Menu Item</li>
				</ul>
			</li>
			<li>
				<span>Menu Item</span>
				<ul>
					<li>Child Menu Item</li>
					<li>Child Menu Item</li>
					<li>Child Menu Item</li>
					<li>Child Menu Item</li>
					<li>Child Menu Item</li>
					<li>Child Menu Item</li>
					<li>Child Menu Item</li>
				</ul>
			</li>

			The following should be dynamically created & injected only when necessary.
			<li>
				<button>Dynamic More Toggle</button>
				<div>
					<ul>
						<li>Dynamic Child Menu Item</li>
						<li>Dynamic Child Menu Item</li>
						<li>Dynamic Child Menu Item</li>
					</ul>
				</div>
			</li>
			End dynamic

		</ul>-->
	</div>

</div>
