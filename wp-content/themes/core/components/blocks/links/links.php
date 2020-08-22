<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\blocks\links\Links_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Links_Block_Controller::factory( $args );

if ( empty( $c->get_links() ) ) {
	return;
}
?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<?php get_template_part(
			'components/content_block/content_block',
			null,
			$c->get_header_args()
		); ?>

		<div <?php echo $c->get_content_classes(); ?>>
			<?php if ( ! empty( $c->get_links_title_args() ) ) { ?>
				<?php get_template_part(
					'components/text/text',
					null,
					$c->get_links_title_args()
				); ?>
			<?php } ?>

			<?php if ( ! empty( $c->get_links() ) ) { ?>
				<ul class="b-links__list">
					<?php foreach ( $c->get_links() as $link ) { ?>
						<li class="b-links__list-item">
							<?php get_template_part(
								'components/link/link',
								null,
								$link
							); ?>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>

	</div>
</section>
