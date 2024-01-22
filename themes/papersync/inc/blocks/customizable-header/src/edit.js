import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { useState } from '@wordpress/element';
import { PanelBody, SelectControl } from '@wordpress/components';
import CustomizableHeader from './themes';
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
			<h1>Header</h1>
			<CustomizableHeader type={style} />
		</div>
	);
}
