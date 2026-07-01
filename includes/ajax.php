<?php
/**
 * Feed Block AJAX endpoints.
 *
 * @package feed-block
 */

namespace FeedBlock\AJAX;

use function FeedBlock\Feed\get_feed;

defined( 'ABSPATH' ) || exit;

/**
 * Fetches a feed's contents. Admin only.
 */
function get_feed_action() {
	check_ajax_referer( 'feed-block' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( 'Unauthorized' );
	}

	$url = filter_input( INPUT_POST, 'url', FILTER_SANITIZE_URL );

	if ( ! $url ) {
		wp_send_json_error( 'Invalid URL' );
	}

	// Cache time is provided in minutes; convert to seconds for get_feed().
	$cache_time    = filter_input( INPUT_POST, 'cacheTime', FILTER_VALIDATE_INT );
	$cache_seconds = ( is_int( $cache_time ) && $cache_time > 0 ) ? $cache_time * MINUTE_IN_SECONDS : null;

	$json = get_feed( $url, $cache_seconds );

	if ( is_wp_error( $json ) ) {
		wp_send_json_error( $json->get_error_message() );
	}

	wp_send_json_success( $json );
}
add_action( 'wp_ajax_feed_block_get_feed', __NAMESPACE__ . '\\get_feed_action' );
