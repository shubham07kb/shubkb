import { useBlockProps } from '@wordpress/block-editor';
import CustomizableHeader from './themes';

export default function Save({ attributes }) {
	const { style } = attributes;
	return (
		<div {...useBlockProps.save()}>
			<CustomizableHeader type={style} />
		</div>
	);
}
