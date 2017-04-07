import { createAction } from 'redux-actions';

export const REQ_TEST = 'app/test/request';
export const REC_TEST = 'app/test/receive';
export const ERR_TEST = 'app/test/error';
export const TOGGLE_CHECKBOX = 'app/test/toggle-checkbox';

export const reqTest = createAction(REQ_TEST);
export const recTest = createAction(REC_TEST);
export const errTest = createAction(ERR_TEST);
export const toggleCheckbox = createAction(TOGGLE_CHECKBOX);

export const INITIAL_STATE = {
	isLoading: false,
	data: null,
	error: null,
	checked: false,
};

export default function testReducer(state = INITIAL_STATE, action) {
	switch (action.type) {
		case REQ_TEST: {
			return {
				...state,
				isLoading: true,
			};
		}
		case REC_TEST: {
			const { data } = action.payload;
			return {
				...state,
				isLoading: false,
				data,
			};
		}
		case TOGGLE_CHECKBOX: {
			return {
				...state,
				checked: !state.checked,
			};
		}
		default:
			return state;
	}
}
