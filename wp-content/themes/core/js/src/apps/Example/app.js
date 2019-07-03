import React from 'react';
import { hot } from 'react-hot-loader';
import Example from './Example/containers';

import styles from './app.pcss';

const App = () => (
	<div className={ styles.main }>
		<Example />
	</div>
);

export default hot( module )( App );
