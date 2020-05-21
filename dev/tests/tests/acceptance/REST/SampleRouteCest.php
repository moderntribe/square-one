<?php
/**
 * Test the Sample Route.
 *
 * @package AAN
 */

use Codeception\Util\HttpCode;
use PHPUnit\Framework\Assert;
use Tribe\Project\Post_Types\Sample\Sample;

/**
 * Class SampleRouteCest.
 */
class SampleRouteCest extends BaseAcceptanceCest {

	/**
	 * The test ID for inserting additional meta per-test.
	 *
	 * @var int The ID of an inserted test post.
	 */
	protected $test_id = 0;

	const ENDPOINT_URL = 'square-one/v1/sample/get';
	const SAMPLE_TITLE = 'Sample Sample';

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

	public function i_see_sample_in_json( AcceptanceTester $I ) {
		$I->haveHttpHeader( 'Content-Type', 'application/json' );
		$I->sendGET( self::ENDPOINT_URL );
		$I->seeResponseCodeIs( HttpCode::OK ); // 200
		$I->seeResponseIsJson();

		// Contains various elements.
		$I->seeResponseContains( $this->test_id );
	}
}
