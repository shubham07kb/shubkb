import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	return (
		<div {...useBlockProps()}>
			Index will Visible on Post or Page and Provide Edit Option as per
			Permission to Edit Post.
		</div>
	);
}
