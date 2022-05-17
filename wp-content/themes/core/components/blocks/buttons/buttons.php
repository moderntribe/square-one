<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\blocks\buttons\Buttons_Block_Controller;
use Tribe\Project\Templates\Components\link\Link_Controller;

/**
 * @var array $args Arguments passed to the template
 */
$c = Buttons_Block_Controller::factory( $args );

if ( empty( $c->get_buttons()->count() ) ) {
	return;
}
?>

<div <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<?php foreach ( $c->get_buttons() as $button ) {
		if ( ! $button->cta->link->url ) {
			continue;
		}

		$style = $button->button_style ? sanitize_html_class( sprintf( 'b-style__%s', $button->button_style ) ) : '';

		get_template_part( 'components/link/link', '', [
			Link_Controller::URL            => $button->cta->link->url,
			Link_Controller::CONTENT        => $button->cta->link->title,
			Link_Controller::TARGET         => $button->cta->link->target,
			Link_Controller::ADD_ARIA_LABEL => $button->cta->add_aria_label,
			Link_Controller::ARIA_LABEL     => $button->cta->aria_label,
			Link_Controller::CLASSES        => array_filter( [
				'b-links__list-link',
				$style,
			] ),
		] );
	} ?>
</div>
