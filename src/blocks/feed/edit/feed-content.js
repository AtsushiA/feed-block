/**
 * Block: feed, edit.feed-content.
 */

import { useSelect } from '@wordpress/data';
import {
	BlockControls,
	InspectorControls,
	useSetting,
	useBlockProps,
	useInnerBlocksProps,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	TextControl,
	ToggleControl,
	ToolbarGroup,
	SelectControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { edit, list, grid } from '@wordpress/icons';

const TEMPLATE = [
	[ 'feed-block/feed-item-template' ],
	[ 'feed-block/feed-no-results' ],
];
const DEFAULT_MIN_ITEMS = 1;
const DEFAULT_MAX_ITEMS = 20;
// Minimum cache time in minutes. A value of 0 would make the feed cache never
// expire, so we keep the lower bound at 1 minute.
const DEFAULT_MIN_CACHE_TIME = 1;

export default function FeedContent( {
	attributes,
	setAttributes,
	setIsEditing,
} ) {
	const {
		itemsToShow,
		cacheTime,
		displayLayout,
		tagName: Tag = 'div',
		layout = {},
		itemLinkRel,
		itemLinkTarget,
	} = attributes;

	const { themeSupportsLayout } = useSelect( ( select ) => {
		const { getSettings } = select( blockEditorStore );
		return { themeSupportsLayout: getSettings()?.supportsLayout };
	}, [] );
	const defaultLayout = useSetting( 'layout' ) || {};
	const usedLayout = ! layout?.type
		? { ...defaultLayout, ...layout, type: 'default' }
		: { ...defaultLayout, ...layout };
	const blockProps = useBlockProps();
	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		template: TEMPLATE,
		__experimentalLayout: themeSupportsLayout ? usedLayout : undefined,
	} );

	const showColumnsControl = displayLayout?.type === 'flex';

	const updateDisplayLayout = ( newDisplayLayout ) => {
		setAttributes( {
			displayLayout: { ...displayLayout, ...newDisplayLayout },
		} );
	};

	const toolbarControls = [
		{
			icon: edit,
			title: __( 'Edit Feed URL' ),
			onClick: () => setIsEditing( true ),
		},
		{
			icon: list,
			title: __( 'List view' ),
			onClick: () => updateDisplayLayout( { type: 'list' } ),
			isActive: displayLayout?.type === 'list',
		},
		{
			icon: grid,
			title: __( 'Grid view' ),
			onClick: () =>
				updateDisplayLayout( {
					type: 'flex',
					columns: displayLayout?.columns || 3,
				} ),
			isActive: displayLayout?.type === 'flex',
		},
	];

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Feed Loop Settings', 'feed-block' ) }>
					<RangeControl
						__nextHasNoMarginBottom
						label={ __( 'Number of items' ) }
						value={ itemsToShow }
						onChange={ ( value ) =>
							setAttributes( { itemsToShow: value } )
						}
						min={ DEFAULT_MIN_ITEMS }
						max={ DEFAULT_MAX_ITEMS }
						required
					/>
					<TextControl
						__nextHasNoMarginBottom
						type="number"
						label={ __( 'Cache time (minutes)', 'feed-block' ) }
						help={ __(
							'How long the fetched feed is cached before it is retrieved again. Default is 720 minutes (12 hours).',
							'feed-block'
						) }
						value={ cacheTime }
						min={ DEFAULT_MIN_CACHE_TIME }
						onChange={ ( value ) => {
							const parsed = parseInt( value, 10 );
							setAttributes( {
								cacheTime: Number.isNaN( parsed )
									? undefined
									: Math.max(
											DEFAULT_MIN_CACHE_TIME,
											parsed
									  ),
							} );
						} }
					/>
					{ showColumnsControl && (
						<>
							<RangeControl
								__nextHasNoMarginBottom
								label={ __( 'Columns' ) }
								value={ displayLayout.columns }
								onChange={ ( value ) =>
									updateDisplayLayout( { columns: value } )
								}
								min={ 2 }
								max={ Math.max( 6, displayLayout.columns ) }
							/>
							{ displayLayout.columns > 6 && (
								<Notice
									status="warning"
									isDismissible={ false }
								>
									{ __(
										'This column count exceeds the recommended amount and may cause visual breakage.'
									) }
								</Notice>
							) }
						</>
					) }
				</PanelBody>
				<PanelBody title={ __( 'Link Settings' ) }>
					<p className="description">
						These link settings apply to any feed-specific link
						elements within the Feed Loop.
					</p>
					<ToggleControl
						label={ __( 'Open in new tab' ) }
						checked={ itemLinkTarget === '_blank' }
						onChange={ ( nextIsNewTab ) => {
							setAttributes( {
								itemLinkTarget: nextIsNewTab
									? '_blank'
									: '_self',
							} );
						} }
					/>
					<TextControl
						label={ __( 'Link rel' ) }
						value={ itemLinkRel }
						onChange={ ( nextRel ) => {
							setAttributes( { itemLinkRel: nextRel } );
						} }
					/>
				</PanelBody>
			</InspectorControls>
			<InspectorControls __experimentalGroup="advanced">
				<SelectControl
					label={ __( 'HTML element' ) }
					options={ [
						{ label: __( 'Default (<div>)' ), value: 'div' },
						{ label: '<main>', value: 'main' },
						{ label: '<section>', value: 'section' },
						{ label: '<aside>', value: 'aside' },
					] }
					value={ Tag }
					onChange={ ( value ) =>
						setAttributes( { tagName: value } )
					}
				/>
			</InspectorControls>
			<BlockControls>
				<ToolbarGroup controls={ toolbarControls } />
			</BlockControls>
			<Tag { ...innerBlocksProps } />
		</>
	);
}
