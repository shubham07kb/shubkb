import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
import Save from './save';

registerBlockType('papersync/customizable-header-purple', {
	edit: Edit,
	save: Save,
});
