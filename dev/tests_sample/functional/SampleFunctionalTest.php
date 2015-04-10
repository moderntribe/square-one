<?php

class SampleFunctionalTest extends \WP_UnitTestCase {

	/**
	 * @test
	 * it should allow using WordPress functions
	 */
	public function it_should_allow_using_word_press_functions() {
		update_option( 'some option', 'some value' );

		$this->assertEquals( 'some value', get_option( 'some option' ) );
	}

	/**
	 * @test
	 * it should allow manipulating posts
	 */
	public function it_should_allow_manipulating_posts() {
		$post_id = wp_insert_post( [ 'post_title' => 'Hello there!' ] );
		$post    = get_post( $post_id );

		$this->assertEquals( 'Hello there!', $post->post_title );
	}

	/**
	 * @test
	 * it should allow hooking
	 */
	public function it_should_allow_hooking() {
		$post_id = wp_insert_post( [ 'post_title' => 'Hello there!' ] );

		add_filter( 'the_title', function ( $title ) {
			return str_replace( 'there', 'here', $title );
		}, 10, 1 );

		$this->assertEquals( 'Hello here!', get_the_title( $post_id ) );
	}

	/**
	 * @test
	 * it should allow failing tests
	 */
	public function it_should_allow_failing_tests() {
		$post_id = wp_insert_post( [ 'post_title' => 'Hello there!' ] );

		$this->assertEquals( 'Hello here!', get_the_title( $post_id ) );
	}

}