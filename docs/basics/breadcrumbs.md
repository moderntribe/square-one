# Breadcrumbs

## Default functionality

Default breadcrumb functionality is in `Tribe\Project\Templates\Components\Traits\Breadcrumbs` in the `get_breadcrumbs` method. By default, including this trait in your class will take the current post or archive page and build out breadcrumb links accordingly. If the post type or taxonomy is hierarchical, breadcrumbs will be built using the parent items.

## Overriding Breadcrumbs
Breadcrumbs can be overridden in the child components. For example, if you want to override default breadcrumb functonality in for the subheader the breadcrumbs should be overridden in `Tribe\Project\Templates\Components\header\subheader\Subheader_Controller`.
```php
<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\header\subheader;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Traits\Breadcrumbs;
use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;
use Tribe\Project\Templates\Models\Breadcrumb;

class Subheader_Controller extends Abstract_Controller {

	use Breadcrumbs;

	/**
	 * Returns breadcrumbs for the post.
	 *
	 * @return array \Tribe\Project\Templates\Models\Breadcrumb[] Breadcrumbs for the post.
	 */
	public function get_breadcrumbs(): array {

		return [
			Breadcrumbs_Controller::BREADCRUMBS => [
				new Breadcrumb( get_site_url(), esc_html__( 'Home', 'tribe' ) ),
			],
		];
	}

}

```
