<?php
/**
 * Block: feed-item-link, render.
 *
 * Global vars: $attributes, $content, $block.
 *
 * @package feed-block
 */

use function FeedBlock\Util\get_block_border_attributes;

$text = $attributes['text'] ?? '';

if ( empty( $text ) ) {
	return;
}

$atts = get_block_border_attributes( $attributes );

if ( ! empty( $block->context['feed-block/itemLinkRel'] ) ) {
	$atts['rel'] = esc_attr( $block->context['feed-block/itemLinkRel'] );
}

if ( ! empty( $block->context['feed-block/itemLinkTarget'] ) ) {
	$atts['target'] = esc_attr( $block->context['feed-block/itemLinkTarget'] );
}

printf(
	'<a href="%1$s" %2$s>%3$s</a>',
	esc_url( $block->context['feed-block/item/url'] ),
	get_block_wrapper_attributes( $atts ),
	esc_html( $text )
);
