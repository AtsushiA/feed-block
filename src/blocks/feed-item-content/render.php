<?php
/**
 * Block: feed-item-content, render.
 *
 * Global vars: $attributes, $content, $block.
 *
 * @package feed-block
 */

$content_type_map = array(
	'text'      => 'content_text',
	'html'      => 'content_html',
	'htmlNoImg' => 'content_html_noimg',
);

$custom_tag     = is_array( $attributes['customTag'] ) && count( $attributes['customTag'] ) === 2 ? $attributes['customTag'] : false;
$custom_tagname = $custom_tag ? $custom_tag[1] : false;
$custom_content = $custom_tag ? $block->context['feed-block/item/custom'][ $custom_tag[0] ][ $custom_tag[1] ] : false;

$content = false !== $custom_content // Empty string is valid content.
	? (
		'htmlNoImg' === $attributes['contentType']
			? preg_replace( '/<img[^>]*>/g', '', $custom_content )
			: $custom_content
	) : $block->context[ 'feed-block/item/' . $content_type_map[ $attributes['contentType'] ] ];
if ( 'text' === $attributes['contentType'] ) {
	$content = wp_strip_all_tags( $content );
}

$align_class_name = empty( $attributes['textAlign'] ) ? '' : "has-text-align-{$attributes['textAlign']}";

$atts = array(
	'class' => $align_class_name,
);
if ( $custom_tagname ) {
	$atts['data-feed-tag'] = $custom_tagname;
}
$wrapper_attributes = get_block_wrapper_attributes( $atts );
?>

<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_block_wrapper_attributes() returns escaped attributes. ?>>
	<?php echo wp_kses_post( $content ); ?>
</div>
