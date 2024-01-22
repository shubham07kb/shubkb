import { InnerBlocks } from '@wordpress/block-editor';
import './simple';
import './dark';
import './purple';

export default function CustomizableHeader({ type }) {
	if (type === 'simple') {
		return (
			<InnerBlocks
				allowedBlocks={['papersync/customizable-header-simple']}
				orientation="horizontal"
				template={[['papersync/customizable-header-simple']]}
			/>
		);
	} else if (type === 'dark') {
		return (
			<InnerBlocks
				allowedBlocks={['papersync/customizable-header-dark']}
				orientation="horizontal"
				template={[['papersync/customizable-header-dark']]}
			/>
		);
	} else if (type === 'purple') {
		return (
			<InnerBlocks
				allowedBlocks={['papersync/customizable-header-purple']}
				orientation="horizontal"
				template={[['papersync/customizable-header-purple']]}
			/>
		);
	}
}
