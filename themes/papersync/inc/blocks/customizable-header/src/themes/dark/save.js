import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Save() {
	return (
		<div {...useBlockProps.save({ className: 'header-dark' })}>
			<InnerBlocks.Content />
		</div>
	);
}
