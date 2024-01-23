import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Save() {
	return (
		<div {...useBlockProps.Save()}>
			<InnerBlocks.Content />
		</div>
	);
}
