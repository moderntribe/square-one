<?php declare(strict_types=1);

use Tribe\Tests\Unit;

/**
 * Test lefthook git hook ticket prefixing.
 */
final class PrefixJiraTicketTest extends Unit {

	protected function setUp(): void {
		parent::setUp();

		require_once dirname( __DIR__, 4 ) . '/.lefthook/prepare-commit-msg/prefix-with-jira-ticket.php';
	}

	public function test_it_finds_tickets_in_branches():void {
		$ticket = parse_ticket( 'feature/SQONE-698/my-feature' );
		$this->assertSame( 'SQONE-698', $ticket );

		$ticket = parse_ticket( 'feature/MIIID23-36/my-feature' );
		$this->assertSame( 'MIIID23-36', $ticket );

		$ticket = parse_ticket( 'test/MSL233-15/my-feature' );
		$this->assertSame( 'MSL233-15', $ticket );

		$ticket = parse_ticket( 'test/EEY88-776/my-feature' );
		$this->assertSame( 'EEY88-776', $ticket );

		$ticket = parse_ticket( 'test/YEAH-44/my-feature' );
		$this->assertSame( 'YEAH-44', $ticket );
	}

	public function test_it_does_not_find_a_ticket_in_invalid_branches(): void {
		$ticket = parse_ticket( 'feature/with-a-number-but-no-ticket-22' );
		$this->assertEmpty( $ticket );

		$ticket = parse_ticket( 'feature/test/with-a-number-but-no-ticket-23' );
		$this->assertEmpty( $ticket );

		$ticket = parse_ticket( 'security/update-wp-to-6.0.2' );
		$this->assertEmpty( $ticket );

		$ticket = parse_ticket( 'feature/22-test/my-feature' );
		$this->assertEmpty( $ticket );

		$ticket = parse_ticket( 'SQONE-698' );
		$this->assertEmpty( $ticket );
	}

	public function test_it_finds_protected_branches(): void {
		$this->assertTrue( is_protected_branch( 'main' ) );
		$this->assertTrue( is_protected_branch( 'master' ) );
		$this->assertTrue( is_protected_branch( 'develop' ) );
		$this->assertTrue( is_protected_branch( 'sprint/22.08' ) );
		$this->assertTrue( is_protected_branch( 'sprint/cookie' ) );
		$this->assertTrue( is_protected_branch( 'server/development' ) );
		$this->assertTrue( is_protected_branch( 'server/staging' ) );
		$this->assertTrue( is_protected_branch( 'server/production' ) );
	}

	public function test_it_allows_unprotected_branches(): void {
		$this->assertFalse( is_protected_branch( 'chore/SQONE-698/test-branch') );
		$this->assertFalse( is_protected_branch( 'random-name') );
		$this->assertFalse( is_protected_branch( 'servers/test') );
	}

}
