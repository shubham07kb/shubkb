import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
import Save from './save';

registerBlockType('papersync/customizable-header-dark', {
	edit: Edit,
	save: Save,
});
