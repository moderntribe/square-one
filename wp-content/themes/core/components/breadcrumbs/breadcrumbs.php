<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Breadcrumbs_Controller::factory( $args );

?>
<div
	<?php echo $c->get_classes(); ?>
	<?php echo $c->get_attrs(); ?>
>

	<ul itemscope itemtype="http://schema.org/BreadcrumbList" <?php echo $c->get_main_classes(); ?> <?php echo $c->get_main_attrs(); ?>>

		<?php foreach ( $c->get_items() as $item ) : ?>
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" <?php echo $c->get_item_classes(); ?> <?php echo $c->get_item_attrs(); ?>>
				<?php if ( ! empty( $item->url ) ) : ?>
					<a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="<?php echo esc_url( $item->url ); ?>" <?php echo $c->get_link_classes(); ?> <?php echo $c->get_link_attrs(); ?>>
						<span itemprop="name">
							<?php echo esc_html( $item->label ); ?>
						</span>
					</a>
				<?php else : ?>
					<span itemscope itemtype="http://schema.org/Thing" itemprop="item">
						<span itemprop="name">
							<?php echo esc_html( $item->label ); ?>
						</span>
					</span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>

	</ul>

</div>
