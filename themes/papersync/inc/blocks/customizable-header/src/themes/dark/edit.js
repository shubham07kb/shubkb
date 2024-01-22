import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Edit() {
	return (
		<div {...useBlockProps({ className: 'header-dark' })}>
			<InnerBlocks
				allowedBlocks={['core/navigation']}
				orientation="horizontal"
				template={[['core/navigation']]}
			/>
		</div>
	);
}
