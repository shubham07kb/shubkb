import {
	useBlockProps,
	BlockControls,
	InspectorControls,
} from '@wordpress/block-editor';
import { useState } from '@wordpress/element';
import {
	PanelBody,
	ToolbarButton,
	ToolbarGroup,
	TextControl,
} from '@wordpress/components';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const { type, id } = attributes;
	const [typeValue, setType] = useState(type);
	const [idValue, setId] = useState(id);
	return (
		<div {...useBlockProps()}>
			{typeValue === 'validate' && (
				<InspectorControls>
					<PanelBody>
						<TextControl
							label="Target Field ID"
							value={idValue}
							onChange={(value) => {
								setId(value);
								setAttributes({ id: value });
							}}
						/>
					</PanelBody>
				</InspectorControls>
			)}
			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton
						icon="admin-users"
						label="Register"
						isPressed={typeValue === 'register'}
						onClick={() => {
							setType('register');
							setId('user-login');
							setAttributes({
								type: 'register',
								id: 'user-login',
							});
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
