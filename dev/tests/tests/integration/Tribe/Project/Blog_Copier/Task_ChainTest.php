<?php

namespace Tribe\Project\Blog_Copier;

use Tribe\Project\Blog_Copier\Tasks\Send_Notifications;
use Tribe\Project\Blog_Copier\Tasks\Create_Blog;
use Tribe\Project\Blog_Copier\Tasks\Replace_Options;
use Tribe\Project\Blog_Copier\Tasks\Replace_Tables;
use Tribe\Tests\Test_Case;

class Task_ChainTest extends Test_Case {

	public function test_get_next() {
		$chain = new Task_Chain( [
			Create_Blog::class,
			Replace_Tables::class,
			Send_Notifications::class,
			Replace_Options::class,
		] );

		$this->assertEquals( Create_Blog::class, $chain->get_first() );
		$this->assertEquals( Send_Notifications::class, $chain->get_next( Replace_Tables::class ) );
	}

	public function test_set_step() {
		$chain = new Task_Chain( [
			Create_Blog::class,
			Replace_Tables::class,
			Send_Notifications::class,
		] );
		$this->assertEquals( '', $chain->get_next( Send_Notifications::class ) );
		$chain->set_step( Send_Notifications::class, Replace_Options::class );
		$this->assertEquals( Replace_Options::class, $chain->get_next( Send_Notifications::class ) );

		$this->assertEquals( Replace_Tables::class, $chain->get_next( Create_Blog::class ) );
		$chain->set_step( Create_Blog::class, Send_Notifications::class );
		$this->assertEquals( Send_Notifications::class, $chain->get_next( Create_Blog::class ) );
	}

	public function test_insert_step() {
		$chain = new Task_Chain( [
			Create_Blog::class,
			Replace_Tables::class,
			Replace_Options::class,
		] );

		$this->assertEquals( Replace_Options::class, $chain->get_next( Replace_Tables::class ) );
		$chain->insert_step( Replace_Tables::class, Send_Notifications::class );
		$this->assertEquals( Replace_Options::class, $chain->get_next( Send_Notifications::class ) );
		$this->assertEquals( Send_Notifications::class, $chain->get_next( Replace_Tables::class ) );
	}

}