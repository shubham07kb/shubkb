import {
	useBlockProps,
	BlockControls,
	InspectorControls,
} from '@wordpress/block-editor';
import { useState } from '@wordpress/element';
import { ToolbarButton, ToolbarGroup } from '@wordpress/components';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const { type } = attributes;
	const [typeValue, setType] = useState(type);
	return (
		<div {...useBlockProps()}>
			<InspectorControls></InspectorControls>
			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton
						icon="admin-users"
						label="Register"
						isPressed={typeValue === 'register'}
						onClick={() => {
							setType('register');
							setAttributes({ type: 'register' });
						}}
					/>
					<ToolbarButton
						icon="admin-tools"
						label="Validate"
						isPressed={typeValue === 'validate'}
						onClick={() => {
							setType('validate');
							setAttributes({ type: 'validate' });
						}}
					/>
				</ToolbarGroup>
			</BlockControls>
			<div>
				{typeValue === 'register' && <button>Register</button>}
				{typeValue === 'validate' && <button>Validate</button>}
			</div>
		</div>
	);
}
