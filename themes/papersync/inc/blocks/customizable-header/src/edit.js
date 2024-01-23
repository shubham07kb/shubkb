import {
	useBlockProps,
	InnerBlocks,
	InspectorControls,
} from '@wordpress/block-editor';
import { useState } from '@wordpress/element';
import { PanelBody, SelectControl } from '@wordpress/components';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const { style } = attributes;
	const [styleValue, styleTest] = useState(style);
	return (
		<div {...useBlockProps()}>
			<InspectorControls>
				<PanelBody title="Header Design">
					<SelectControl
						label="Header Design"
						value={styleValue}
						options={[
							{ label: 'Simple', value: 'simple' },
							{ label: 'Dark', value: 'dark' },
							{ label: 'Purple', value: 'Purple' },
						]}
						onChange={(selectedValue) => {
							styleTest(selectedValue);
							setAttributes({ style: selectedValue });
						}}
					/>
				</PanelBody>
			</InspectorControls>
			<h4>Header</h4>
			<div>
				{styleValue === 'simple' && (
					<InnerBlocks
						allowedBlocks={['papersync/customizable-header-simple']}
						orientation="horizontal"
						template={[['papersync/customizable-header-simple']]}
					/>
				)}
				{styleValue === 'dark' && (
					<InnerBlocks
						allowedBlocks={['papersync/customizable-header-dark']}
						orientation="horizontal"
						template={[['papersync/customizable-header-dark']]}
					/>
				)}
				{styleValue === 'purple' && (
					<InnerBlocks
						allowedBlocks={['papersync/customizable-header-purple']}
						orientation="horizontal"
						template={[['papersync/customizable-header-purple']]}
					/>
				)}
			</div>
		</div>
	);
}
