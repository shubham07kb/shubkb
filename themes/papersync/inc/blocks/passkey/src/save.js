import { useBlockProps } from '@wordpress/block-editor';

export default function Save({ attributes }) {
	const { type } = attributes;
	return (
		<div {...useBlockProps.save()}>
			{type === 'register' && (
				// eslint-disable-next-line no-undef
				<button className="passkey_reg">Register</button>
			)}
			{type === 'validate' && (
				// eslint-disable-next-line no-undef
				<button className="passkey_val">Validate</button>
			)}
		</div>
	);
}
