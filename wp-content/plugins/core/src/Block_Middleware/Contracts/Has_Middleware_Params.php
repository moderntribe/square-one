<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Contracts;

/**
 * Utilize this interface with Block Config classes to provide additional
 * parameters for block middleware.
 *
 * @mixin \Tribe\Libs\ACF\Block_Config
 */
interface Has_Middleware_Params {

	/**
	 * Return the additional parameters to be used during middleware
	 * pipeline processing.
	 *
	 * @see \Tribe\Project\Block_Middleware\Contracts\Field_Middleware
	 *
	 * @return array<int, array<string, array<int, mixed>>>
	 */
	public function get_middleware_params(): array;

}
