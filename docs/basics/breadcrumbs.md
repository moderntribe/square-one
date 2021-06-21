# Breadcrumbs

## Default functionality

Default breadcrumb functionality is in `Tribe\Project\Templates\Component\Abstract_Controller` in the `render_breadcrumbs` and `get_breadcrumbs` functions. By default, this will take the current post or archive page and build out breadcrumb links accordingly. If the post type or taxonomy is hierarchical, breadcrumbs will be built using the parent items.

## Overriding Breadcrumbs
Breadcrumbs can be overridden in the child component controllers. For example, if you want to override default breadcrumb functonality in for the pages the breadcrumbs should be overridden in `Tribe\Project\Templates\Components\routes\page\Page_Controller.php`.
```php
<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\routes\page;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;
use Tribe\Project\Templates\Models\Breadcrumb;

class Page_Controller extends Abstract_Controller {

	public function render_breadcrumbs(): void {
		//TODO: let's make this get_breadcrumb_args() and render in template
		get_template_part(
			'components/breadcrumbs/breadcrumbs',
			'page',
			[ Breadcrumbs_Controller::BREADCRUMBS => $this->get_breadcrumbs() ]
		);
	}

	/**
	 * @return \Tribe\Project\Templates\Models\Breadcrumb[]
	 */
	protected function get_breadcrumbs(): array {
		$page = get_the_ID();
		$url  = $page ? get_permalink( $page ) : home_url();

		return [
			new Breadcrumb( $url, get_the_title( $page ) ),
		];
	}

}
```
