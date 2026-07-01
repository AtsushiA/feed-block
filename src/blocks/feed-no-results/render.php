<?php
/**
 * Block: feed-no-results, render.
 *
 * Displays blocks when no feed items are found.
 *
 * @package feed-block
 */

namespace FeedBlock\Blocks\FeedNoResults;

use function FeedBlock\Feed\get_feed;

defined( 'ABSPATH' ) || exit;

$cache_time    = isset( $block->context['feed-block/cacheTime'] ) ? (int) $block->context['feed-block/cacheTime'] : 0;
$cache_seconds = ( $cache_time > 0 ) ? $cache_time * MINUTE_IN_SECONDS : null;

$feed = get_feed( $block->context['feed-block/feedURL'], $cache_seconds );

// If there are feed items, do not render this block.
if ( ( ! is_wp_error( $feed ) && ! empty( $feed['items'] ) ) ) {
	return '';
}

// If no content is available, do not render this block.
if ( empty( $content ) ) {
	return '';
}

$classes            = ( isset( $attributes['style']['elements']['link']['color']['text'] ) ) ? 'has-link-color' : '';
$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $classes ) );
return sprintf(
	'<div %1$s>%2$s</div>',
	$wrapper_attributes,
	$content
);
