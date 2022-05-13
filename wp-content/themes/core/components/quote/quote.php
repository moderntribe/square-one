<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\quote\Quote_Controller;
use Tribe\Project\Templates\Components\text\Text_Controller;

/**
 * @var array $args Arguments passed to the template
 */
$c = Quote_Controller::factory( $args );
?>

<blockquote <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<?php if ( $c->has_quote() ) : ?>
		<?php get_template_part( 'components/text/text', '', [
			Text_Controller::TAG     => 'h2',
			Text_Controller::CLASSES => [
				'c-quote__text',
				'h4',
			],
			Text_Controller::CONTENT => esc_html( $c->get_quote()->quote_text ),
		] );
		?>

		<cite class="c-quote__cite">
			<?php if ( ! empty( ( $c->get_image_args() ) ) ) {
				get_template_part( 'components/image/image', null, $c->get_image_args() );
			} ?>

			<span class="c-quote__cite-text">
				<span class="c-quote__cite-name">
					<?php echo esc_html( $c->get_quote()->cite_name ); ?>
				</span>
				<span class="c-quote__cite-title">
					<?php echo esc_html( $c->get_quote()->cite_title ); ?>
				</span>
			</span>
		</cite>
	<?php endif; ?>
</blockquote>
