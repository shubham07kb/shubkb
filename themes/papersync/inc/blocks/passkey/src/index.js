import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
import Save from './save';
registerBlockType('papersync/passkey-button', {
	edit: Edit,
	save: Save,
});
