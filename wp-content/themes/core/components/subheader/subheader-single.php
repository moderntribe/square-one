<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\subheader\Subheader_Single_Controller;

/**
 * @var array $args Arguments passed to the template
 */
$c = Subheader_Single_Controller::factory( $args );
?>

<header <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>

	<div <?php echo $c->get_container_classes(); ?>>

		<div <?php echo $c->get_content_classes(); ?>>

			<?php
			if ( $c->get_tag_name() !== null && $c->get_tag_link() !== null ) { ?>
				<div>
					<?php get_template_part( 'components/link/link', 'null', $c->get_tag_args() ); ?>
				</div>
			<?php } ?>

			<?php get_template_part( 'components/text/text', null, $c->get_title_args() ); ?>

			<div class="c-subheader__meta">
				<?php if ( $c->should_render_byline() ) : ?>
					<div>
						<div class="c-subheader__meta-author">
							<?php echo esc_html( $c->get_author() ); ?>
						</div>
						<div class="c-subheader__meta-date">
							<time datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>">
								<?php echo esc_html( $c->get_date() ); ?>
							</time>
						</div>
					</div>
				<?php endif; ?>
				<?php get_template_part( 'components/share/share' ) ?>
			</div>

		</div>

	</div>

</header>
