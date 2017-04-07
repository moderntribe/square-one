import 'babel-polyfill';
import React from 'react';
import { render } from 'react-dom';
import { Provider } from 'react-redux';

import configureStore from 'app/store';
import reducers from 'app/ducks';
import { mainSaga, childSagas } from 'app/sagas';

import App from 'app/containers';

const store = configureStore(reducers, mainSaga, childSagas);
render(
	<Provider store={store}>
		<App />
	</Provider>,
	document.getElementById('mount'),
);
