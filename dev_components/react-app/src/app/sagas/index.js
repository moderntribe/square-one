import map from 'lodash/map';
import { fork } from 'redux-saga/effects';
import testSaga from 'app/sagas/test';

export const childSagas = { testSaga };

export function* mainSaga(childrenSagas) {
	yield map(childrenSagas, childSaga => (fork(childSaga)));
}
