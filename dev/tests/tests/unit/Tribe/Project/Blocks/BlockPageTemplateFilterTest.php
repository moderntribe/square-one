<?php declare( strict_types=1 );

use Codeception\Test\Unit;
use Tribe\Project\Blocks\Block_Page_Template_Filter;

final class BlockPageTemplateFilterTest extends Unit {

	public function test_it_prefixes_blocks(): void {
		$block_list = [
			'page-templates/custom-template-one.php' => [
				'block_one',
				'block_two',
			],
			'page-templates/custom-template-two.php' => [
				'block_three',
				'block_four',
			],
		];

		$filter = new Block_Page_Template_Filter( $block_list );
		$list   = $filter->get_block_list();

		$this->assertCount(2, $list );

		$this->assertSame( [
			'acf/block_one',
			'acf/block_two',
		], $list['page-templates/custom-template-one.php'] );

		$this->assertSame( [
			'acf/block_three',
			'acf/block_four',
		], $list['page-templates/custom-template-two.php'] );
	}

}
