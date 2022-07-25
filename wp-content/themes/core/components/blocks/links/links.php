<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\blocks\links\Links_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

/**
 * @var array $args Arguments passed to the template
 */
$c = Links_Block_Controller::factory( $args );

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

			<?php if ( ! empty( $c->get_links()->count() ) ) : ?>
				<ul class="b-links__list">
					<?php foreach ( $c->get_links() as $link ) : ?>
						<?php if ( ! empty( $link->cta->link->url ) ) : ?>
						<li class="b-links__list-item">
							<?php get_template_part( 'components/link/link', '', [
								Link_Controller::URL            => $link->cta->link->url,
								Link_Controller::CONTENT        => $link->cta->link->title,
								Link_Controller::TARGET         => $link->cta->link->target,
								Link_Controller::ADD_ARIA_LABEL => $link->cta->add_aria_label,
								Link_Controller::ARIA_LABEL     => $link->cta->aria_label,
								Link_Controller::CLASSES        => [ 'b-links__list-link' ],
								Link_Controller::HEADER         => $link->link_header,
								Link_Controller::DESCRIPTION    => $link->link_content,
							] ); ?>
						</li>
						<?php endif;
					endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

	</div>
</section>
