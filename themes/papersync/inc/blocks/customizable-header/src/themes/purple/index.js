import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
import Save from './save';

registerBlockType('papersync/customizable-header-purple', {
	title: 'Customizable Header Purple',
	description: 'A team member item',
	icon: 'admin-users',
	parent: ['papersync/customizable-header'],
	supports: {
		reusable: false,
		html: false,
	},
	edit: Edit,
	save: Save,
});
