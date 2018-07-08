<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Components_Docs\Component_Item;
use Tribe\Project\Components_Docs\Registry;
use Tribe\Project\Components_Docs\Router;
use Tribe\Project\Components_Docs\Templates\Component_Docs;
use Tribe\Project\Templates\Components\Accordion;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Card;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Quote;

class Components_Docs_Provider implements ServiceProviderInterface {

	protected $panels     = [];
	protected $components = [
		Accordion::class,
		Button::class,
		Card::class,
		Content_Block::class,
		Quote::class,
	];

	public function register( Container $container ) {

		$this->add_template_paths( $container );

		$container['component_docs.router'] = function ( $container ) {
			return new Router();
		};

		$container['component_docs.registry'] = function ( $container ) {
			return new Registry();
		};

		foreach ( $this->components as $component ) {
			$component_item = new Component_Item( $component );
			$container['component_docs.registry']->add_item( $component_item->get_slug(), $component_item );
		}

		$container['component_docs.template'] = function ( $container ) {
			$twig_path = 'main.twig';
			return new Component_Docs( $twig_path, null, $container['component_docs.registry'] );
		};

		add_action( 'init', function () use ( $container ) {
			$container['component_docs.router']->add_rewrite_rule();
		}, 10, 0 );

		add_filter( 'template_include', function ( $template ) use ( $container ) {
			return $container['component_docs.router']->show_components_docs_page( $template );
		}, 10, 1 );

	}

	protected function add_template_paths( Container $container ) {
		add_filter( 'tribe/project/twig', function ( $twig ) use ( $container ) {
			$template_path = dirname( dirname( __FILE__ ) ) . '/Components_Docs/Twig/';
			$container['twig.loader']->addPath( $template_path );

			$twig = new \Twig_Environment( $container['twig.loader'], $container['twig.options'] );
			$twig->addExtension( $container['twig.extension'] );

			return $twig;
		}, 10, 1 );
	}
}