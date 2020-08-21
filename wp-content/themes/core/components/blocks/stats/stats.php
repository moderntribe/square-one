<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\blocks\stats\Stats_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Stats_Block_Controller::factory( $args ); ?>
<section <?php echo $c->classes(); ?> <?php echo $c->attrs(); ?>>
	<div <?php echo $c->container_classes(); ?>>

		<?php get_template_part(
			'components/content_block/content_block',
			null,
			$c->get_content_args()
		) ?>

		<div <?php echo $c->content_classes(); ?>>
			<ul class="b-stats__list">
				<?php foreach ( $c->get_stats() as $item ) { ?>
				<li class="b-stats__list-item">
					<?php get_template_part( 'components/statistic/statistic', null, $item ); ?>
				</li>
				<?php } ?>
			</ul>
		</div>

	</div>
</section>
