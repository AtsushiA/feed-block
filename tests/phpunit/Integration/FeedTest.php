<?php
/**
 * Integration tests for the feed helpers.
 *
 * @package feed-block
 */

namespace FeedBlock\Tests\Integration;

use WP_UnitTestCase;

use function FeedBlock\Feed\get_feed;

/**
 * Tests that run against a loaded WordPress environment.
 */
class FeedTest extends WP_UnitTestCase {

	/**
	 * The plugin's feed helper should be loaded.
	 */
	public function test_feed_helper_is_available(): void {
		$this->assertTrue( function_exists( 'FeedBlock\\Feed\\get_feed' ) );
	}

	/**
	 * The editor AJAX endpoint should be registered.
	 */
	public function test_ajax_endpoint_is_registered(): void {
		$this->assertNotFalse( has_action( 'wp_ajax_feed_block_get_feed' ) );
	}

	/**
	 * get_feed() should short-circuit to the object cache when a value is
	 * present, confirming the get/set cache group names match.
	 */
	public function test_get_feed_uses_object_cache(): void {
		$url      = 'https://example.com/feed-block-phpunit.xml';
		$expected = array(
			'version' => 'https://jsonfeed.org/version/1.1',
			'items'   => array(),
		);

		wp_cache_set( $url, $expected, 'feed-block' );

		// No HTTP request should be made because the cached value is returned.
		$this->assertSame( $expected, get_feed( $url ) );
	}
}
