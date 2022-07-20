<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Libs\Field_Models\Models\Image;
use Tribe\Libs\Field_Models\Models\Link;
use Tribe\Tests\Test_Case;
use WP_Post;

final class PostProxyTest extends Test_Case {

	public function test_it_creates_and_proxies_wp_post_objects(): void {
		$post = $this->factory()->post->create_and_get();

		$this->assertInstanceOf( WP_Post::class, $post );

		$cta              = new Cta();
		$cta->link        = new Link();
		$cta->link->url   = 'https://square1.tribe';
		$cta->link->title = 'Test Link';

		$post_data        = $post->to_array();
		$post_data['cta'] = $cta;

		$post_proxy = new Post_Proxy( $post_data );

		$this->assertInstanceOf( WP_Post::class, $post_proxy->post() );
		$this->assertNotEmpty( $post->post_content );
		$this->assertNotEmpty( $post->post_title );
		$this->assertNotEmpty( $post->post_name );
		$this->assertSame( $post->ID, $post_proxy->ID );
		$this->assertSame( $post->post_author, $post_proxy->post_author );
		$this->assertSame( $post->post_date, $post_proxy->post_date );
		$this->assertSame( $post->post_date_gmt, $post_proxy->post_date_gmt );
		$this->assertSame( $post->post_content, $post_proxy->post_content );
		$this->assertSame( $post->post_title, $post_proxy->post_title );
		$this->assertSame( $post->post_excerpt, $post_proxy->post_excerpt );
		$this->assertSame( $post->post_status, $post_proxy->post_status );
		$this->assertSame( $post->comment_status, $post_proxy->comment_status );
		$this->assertSame( $post->ping_status, $post_proxy->ping_status );
		$this->assertSame( $post->post_password, $post_proxy->post_password );
		$this->assertSame( $post->post_name, $post_proxy->post_name );
		$this->assertSame( $post->post_modified, $post_proxy->post_modified );
		$this->assertSame( $post->post_modified_gmt, $post_proxy->post_modified_gmt );
		$this->assertSame( $post->guid, $post_proxy->guid );

		$this->assertSame( 'https://square1.tribe', $post_proxy->cta->link->url );
		$this->assertSame( 'Test Link', $post_proxy->cta->link->title );
		$this->assertInstanceOf( Image::class, $post_proxy->image );
		$this->assertSame( 0, $post_proxy->image->id );
		$this->assertFalse( $post_proxy->is_faux_post() );

		// Test some template functions
		$original_date = get_the_date( '', $post );
		$this->assertNotEmpty( $original_date );
		$this->assertSame( $original_date, get_the_date( '', $post_proxy->post() ) );
		$this->assertSame( get_the_title( $post ), get_the_title( $post_proxy->post() ) );
		$this->assertSame( get_the_permalink( $post ), get_the_permalink( $post_proxy->post() ) );
	}

	public function test_it_detects_faux_posts(): void {
		$post_proxy = new Post_Proxy( [
			'ID' => -9998,
		] );

		$this->assertTrue( $post_proxy->is_faux_post() );
	}

}
