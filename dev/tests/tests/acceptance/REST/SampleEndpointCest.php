<?php
/**
 * Test the Sample endpoint.
 *
 * @package Square1-REST
 */

use Codeception\Util\HttpCode;
use PHPUnit\Framework\Assert;
use Tribe\Project\Post_Types\Sample\Sample;

/**
 * Class SampleEndpointCest.
 */
class SampleEndpointCest extends BaseAcceptanceCest {

	/**
	 * @var int The ID of an inserted test post.
	 */
	protected $test_id = 0;

	public const ENDPOINT_URL = 'wp/v2/sample';
	public const SAMPLE_TITLE = 'Sample Sample';

	public function _before( AcceptanceTester $I ): void {
		$postmeta = [];

		$this->test_id = $I->havePostInDatabase(
			[
				'post_type'  => Sample::NAME,
				'post_title' => self::SAMPLE_TITLE,
			]
		);

		parent::_before( $I );
	}

	public function i_see_correct_endpoint_status( AcceptanceTester $I ) {
		$I->sendGET( self::ENDPOINT_URL );
		$I->seeResponseCodeIs( HttpCode::OK ); // 200
	}
}
