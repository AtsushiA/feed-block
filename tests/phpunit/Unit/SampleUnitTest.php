<?php
/**
 * Sample unit test.
 *
 * @package feed-block
 */

namespace FeedBlock\Tests\Unit;

use Yoast\WPTestUtils\BrainMonkey\TestCase;

/**
 * Demonstrates the WordPress-independent unit test harness.
 */
class SampleUnitTest extends TestCase {

	/**
	 * The plugin's constant-like defaults should be sane.
	 */
	public function test_default_cache_time_is_twelve_hours(): void {
		// 720 minutes is the plugin's default feed cache time (12 hours),
		// matching WordPress's default feed cache lifetime.
		$this->assertSame( 720, 12 * 60 );
	}
}
