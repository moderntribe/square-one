import { createStore, applyMiddleware } from 'redux';
import createSagaMiddleware from 'redux-saga';

/* TODO: add in redux devtools and stuff*/
export default function configureStore(reducer, mainSaga, childrenSagas) {
	const sagaMiddleware = createSagaMiddleware();

	const store = createStore(
		reducer,
		applyMiddleware(
			sagaMiddleware,
		),
	);

	sagaMiddleware.run(mainSaga, childrenSagas);

	return store;
}

