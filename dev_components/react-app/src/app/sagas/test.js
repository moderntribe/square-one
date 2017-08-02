import { call, put } from 'redux-saga/effects';
import request from 'axios';
import {
	reqTest,
	errTest,
	recTest,
} from 'app/ducks/test';

export function parseTest({ data }) {
	return data;
}

export function fetchTest() {
	return request('http://localhost:3004/test').then(parseTest);
}

export default function* getTest() {
	yield put(reqTest());

	try {
		const data = yield call(fetchTest);
		yield put(recTest({ data }));
	} catch (e) {
		yield put(errTest(e));
	}
}
