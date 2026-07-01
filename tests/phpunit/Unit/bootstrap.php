<?php
/**
 * PHPUnit bootstrap file for Unit tests.
 *
 * Unit tests do not load WordPress; WordPress functions are mocked with
 * Brain\Monkey via Yoast\WPTestUtils.
 *
 * @package feed-block
 */

require_once dirname( __DIR__, 2 ) . '/../vendor/autoload.php';
